<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('inventaris', function (Blueprint $table) {
            $table->foreignId('inventaris_category_id')->nullable()->constrained('inventaris_categories')->onDelete('set null');
        });

        // Insert default categories
        $categories = [
            ['name' => 'Elektronik', 'prefix' => 'E'],
            ['name' => 'Furniture', 'prefix' => 'F'],
            ['name' => 'Alat Kerja', 'prefix' => 'A'],
            ['name' => 'Kendaraan', 'prefix' => 'K'],
        ];

        foreach ($categories as $cat) {
            \App\Models\InventarisCategory::create($cat);
        }

        // Map existing categories
        $invetaris = \App\Models\Inventaris::all();
        foreach ($invetaris as $item) {
            // Find category
            $catLabel = match ($item->kategori) {
                'elektronik' => 'Elektronik',
                'furniture' => 'Furniture',
                'alat_kerja' => 'Alat Kerja',
                'kendaraan' => 'Kendaraan',
                default => $item->kategori,
            };
            
            $catId = \App\Models\InventarisCategory::where('name', $catLabel)->first();
            if (!$catId) {
                $catId = \App\Models\InventarisCategory::create([
                    'name' => $catLabel,
                    'prefix' => strtoupper(substr($catLabel, 0, 1))
                ]);
            }
            
            $item->inventaris_category_id = $catId->id;
            $item->save();
        }

        Schema::table('inventaris', function (Blueprint $table) {
            $table->dropColumn('kategori');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventaris', function (Blueprint $table) {
            $table->string('kategori')->default('elektronik');
            $table->dropForeign(['inventaris_category_id']);
            $table->dropColumn('inventaris_category_id');
        });
    }
};
