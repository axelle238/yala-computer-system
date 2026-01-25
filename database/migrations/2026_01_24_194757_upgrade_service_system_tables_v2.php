<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Upgrade Header
        Schema::table('service_tickets', function (Blueprint $table) {
            if (! Schema::hasColumn('service_tickets', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('customer_phone')->constrained('users')->nullOnDelete()->comment('Jika customer terdaftar sebagai member');
            }
            if (! Schema::hasColumn('service_tickets', 'serial_number_in')) {
                $table->string('serial_number_in')->nullable()->after('device_name')->comment('SN Perangkat yang diservis');
            }
            if (! Schema::hasColumn('service_tickets', 'passcode')) {
                $table->string('passcode')->nullable()->after('serial_number_in')->comment('PIN/Pattern/Password device');
            }
            if (! Schema::hasColumn('service_tickets', 'warranty_type')) {
                $table->enum('warranty_type', ['store_warranty', 'official_warranty', 'out_of_warranty'])->default('out_of_warranty')->after('status');
            }
            if (! Schema::hasColumn('service_tickets', 'completeness')) {
                $table->json('completeness')->nullable()->after('problem_description')->comment('Kelengkapan: Charger, Tas, Dus, dll');
            }
        });

        // 2. Service Ticket Parts (Sparepart yang dipakai)
        if (! Schema::hasTable('service_ticket_parts')) {
            Schema::create('service_ticket_parts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('service_ticket_id')->constrained()->onDelete('cascade');
                $table->foreignId('product_id')->constrained(); // Barang apa yang dipakai
                $table->integer('quantity')->default(1);
                $table->decimal('price_per_unit', 15, 2); // Harga jual part ke customer
                $table->decimal('subtotal', 15, 2);

                // Jika part tersebut punya SN (misal ganti SSD, SSD barunya ada SN-nya)
                $table->foreignId('product_serial_id')->nullable()->constrained()->nullOnDelete();

                $table->timestamps();
            });
        }

        // 3. Service Ticket Progress (Audit Trail / Tracking Timeline)
        if (! Schema::hasTable('service_ticket_progress')) {
            Schema::create('service_ticket_progress', function (Blueprint $table) {
                $table->id();
                $table->foreignId('service_ticket_id')->constrained()->onDelete('cascade');
                $table->string('status_label'); // Status saat itu (Diagnosing, Waiting Part, etc)
                $table->text('description')->nullable(); // Keterangan detail apa yang dilakukan
                $table->foreignId('technician_id')->constrained('users'); // Siapa yang update
                $table->boolean('is_public')->default(true); // Apakah customer bisa lihat di tracking page
                $table->json('attachment_paths')->nullable(); // Foto bukti pengerjaan (Before/After)
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('service_ticket_progress');
        Schema::dropIfExists('service_ticket_parts');

        Schema::table('service_tickets', function (Blueprint $table) {
            $table->dropColumn(['user_id', 'serial_number_in', 'passcode', 'warranty_type', 'completeness']);
        });
    }
};
