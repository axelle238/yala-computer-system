<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CyberShield
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();
        $ua = $request->userAgent();
        $path = '/'.trim($request->path(), '/');

        // 1. Check Lockdown Mode
        if (Setting::get('security_lockdown_mode')) {
            if (! $request->is('admin*')) { // Strict lockdown for StoreFront
                return response()->view('errors.maintenance', ['message' => 'System under lockdown due to security threat.'], 503);
            }
        }

        // 2. Check Blocked IPs
        $blockedIps = json_decode(Setting::get('firewall_blocked_ips', '[]'), true) ?? [];
        if (collect($blockedIps)->contains('ip', $ip)) {
            $this->logThreat($request, 'Blocked IP Access Attempt');
            abort(403, 'Access Denied: Your IP is blacklisted.');
        }

        // 3. Check Honeypots (Deception)
        $traps = json_decode(Setting::get('security_honeypots', '[]'), true) ?? [];
        foreach ($traps as $trap) {
            if ($trap['status'] === 'active' && $path === $trap['path']) {
                $this->logThreat($request, "Honeypot Triggered: {$trap['type']}");

                if (Setting::get('security_auto_ban')) {
                    $this->autoBan($ip, "Trapped by Honeypot: $path");
                }

                return response('System Error', 500);
            }
        }

        // 4. Check User Agent
        $blockedUas = json_decode(Setting::get('firewall_blocked_ua', '[]'), true) ?? [];
        foreach ($blockedUas as $blocked) {
            if (stripos($ua, $blocked) !== false) {
                $this->logThreat($request, "Blocked User Agent: $blocked");
                abort(403, 'Access Denied: Unacceptable User Agent.');
            }
        }

        // 6. WAF (SQLi / XSS Patterns) - If ATM enabled
        if (Setting::get('security_atm_sqli') || Setting::get('security_atm_xss')) {
            $input = json_encode($request->all());
            $patterns = [
                '/UNION\s+SELECT/i' => 'SQL Injection',
                '/<script>/i' => 'XSS Attempt',
                '/javascript:/i' => 'XSS Attempt',
                '/base64_/i' => 'Suspicious Payload',
            ];

            foreach ($patterns as $pattern => $type) {
                if (preg_match($pattern, $input)) {
                    $this->logThreat($request, "WAF Block: $type detected");

                    // Auto-ban if enabled
                    if (Setting::get('security_auto_ban')) {
                        $this->autoBan($ip, "Auto-banned due to $type");
                    }

                    abort(403, "Security Violation: $type detected.");
                }
            }
        }

        return $next($request);
    }

    private function logThreat(Request $request, $description)
    {
        ActivityLog::create([
            'user_id' => auth()->id(), // Null if guest
            'action' => 'threat_detected',
            'description' => $description,
            'ip_address' => $request->ip(),
        ]);
    }

    private function autoBan($ip, $reason)
    {
        $blocked = json_decode(Setting::get('firewall_blocked_ips', '[]'), true) ?? [];
        if (! collect($blocked)->contains('ip', $ip)) {
            $blocked[] = [
                'ip' => $ip,
                'note' => $reason,
                'added_at' => now()->toDateTimeString(),
                'added_by' => 'CyberShield AI',
            ];
            Setting::set('firewall_blocked_ips', json_encode($blocked));
        }
    }
}
