<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            self::logToDb($model, 'created', 'Menambahkan data baru');
        });

        static::updated(function ($model) {
            self::logToDb($model, 'updated', 'Memperbarui data');
        });

        static::deleted(function ($model) {
            self::logToDb($model, 'deleted', 'Menghapus data');
        });
    }

    protected static function logToDb($model, $action, $description)
    {
        // Skip log jika dijalankan lewat seeder/console tanpa user login (opsional)
        // tapi kita simpan saja dengan user_id null (System)
        
        $properties = null;
        if ($action === 'updated') {
            $properties = [
                'old' => $model->getOriginal(),
                'new' => $model->getAttributes(),
            ];
        } else {
            $properties = [
                'attributes' => $model->getAttributes(),
            ];
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'description' => $description,
            'properties' => $properties,
            'ip_address' => request()->ip(),
        ]);
    }
}
