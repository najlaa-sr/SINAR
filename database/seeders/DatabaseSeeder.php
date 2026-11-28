<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use App\Models\Location;
use App\Models\StockMutation;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@sinar.local'],
            [
                'name' => 'Admin Studio SINAR',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        $petugas = User::updateOrCreate(
            ['email' => 'petugas@sinar.local'],
            [
                'name' => 'Petugas Inventaris SINAR',
                'password' => Hash::make('password123'),
                'role' => 'petugas',
            ]
        );

        $catKamera = Category::firstOrCreate(
            ['code' => 'KAT-KAM'],
            ['name' => 'Kamera', 'description' => 'Kamera foto dan video produksi studio']
        );

        $catLensa = Category::firstOrCreate(
            ['code' => 'KAT-LEN'],
            ['name' => 'Lensa', 'description' => 'Lensa prime, zoom, dan cine']
        );

        $catLighting = Category::firstOrCreate(
            ['code' => 'KAT-LGT'],
            ['name' => 'Lighting', 'description' => 'Lampu studio, softbox, dan modifier']
        );

        $catAudio = Category::firstOrCreate(
            ['code' => 'KAT-AUD'],
            ['name' => 'Audio', 'description' => 'Microphone, wireless mic, dan audio recorder']
        );

        $locStudioA = Location::firstOrCreate(
            ['code' => 'LOC-STDA'],
            ['name' => 'Studio Utama A', 'floor_or_building' => 'Lantai 1 - Gedung Produksi']
        );

        $locStudioB = Location::firstOrCreate(
            ['code' => 'LOC-STDB'],
            ['name' => 'Studio Podcast B', 'floor_or_building' => 'Lantai 1 - Gedung Produksi']
        );

        $locGudang = Location::firstOrCreate(
            ['code' => 'LOC-GDG1'],
            ['name' => 'Gudang Perlengkapan', 'floor_or_building' => 'Lantai 2 - Gedung Produksi']
        );

        $supplier1 = Supplier::firstOrCreate(
            ['code' => 'SUP-001'],
            [
                'name' => 'CV Cahaya Visual Nusantara',
                'phone' => '081234567801',
                'email' => 'info@cahayavisual.co.id',
                'address' => 'Jl. Jenderal Sudirman No. 24, Makassar',
            ]
        );

        $supplier2 = Supplier::firstOrCreate(
            ['code' => 'SUP-002'],
            [
                'name' => 'PT Swara Media Perkasa',
                'phone' => '081234567802',
                'email' => 'contact@swaramedia.co.id',
                'address' => 'Jl. Pengayoman No. 15, Makassar',
            ]
        );

        $kamera = Item::firstOrCreate(
            ['sku' => 'SKU-CAM-001'],
            [
                'category_id' => $catKamera->id,
                'location_id' => $locStudioA->id,
                'name' => 'Sony FX3 Cinema Camera',
                'unit' => 'unit',
                'stock' => 4,
                'minimum_stock' => 2,
                'condition' => 'baik',
            ]
        );

        $lensa = Item::firstOrCreate(
            ['sku' => 'SKU-LNS-001'],
            [
                'category_id' => $catLensa->id,
                'location_id' => $locStudioA->id,
                'name' => 'Sony FE 24-70mm f/2.8 GM II',
                'unit' => 'unit',
                'stock' => 2,
                'minimum_stock' => 2,
                'condition' => 'baik',
            ]
        );

        $lighting = Item::firstOrCreate(
            ['sku' => 'SKU-LGT-001'],
            [
                'category_id' => $catLighting->id,
                'location_id' => $locGudang->id,
                'name' => 'Aputure 600d Pro LED Light',
                'unit' => 'unit',
                'stock' => 1,
                'minimum_stock' => 2,
                'condition' => 'baik',
            ]
        );

        $mic = Item::firstOrCreate(
            ['sku' => 'SKU-AUD-001'],
            [
                'category_id' => $catAudio->id,
                'location_id' => $locStudioB->id,
                'name' => 'Shure SM7B Dynamic Vocal Mic',
                'unit' => 'unit',
                'stock' => 5,
                'minimum_stock' => 2,
                'condition' => 'baik',
            ]
        );

        StockMutation::firstOrCreate(
            ['reference_no' => 'MUT-20261128-001'],
            [
                'item_id' => $kamera->id,
                'user_id' => $admin->id,
                'supplier_id' => $supplier1->id,
                'from_location_id' => null,
                'to_location_id' => $locStudioA->id,
                'type' => 'masuk',
                'quantity' => 4,
                'mutation_date' => now()->subDays(3),
                'notes' => 'Pengadaan awal kamera utama studio',
            ]
        );

        StockMutation::firstOrCreate(
            ['reference_no' => 'MUT-20261128-002'],
            [
                'item_id' => $lensa->id,
                'user_id' => $petugas->id,
                'supplier_id' => null,
                'from_location_id' => $locGudang->id,
                'to_location_id' => $locStudioA->id,
                'type' => 'pindah',
                'quantity' => 2,
                'mutation_date' => now()->subDays(1),
                'notes' => 'Pemindahan lensa ke Studio Utama A untuk sesi shooting',
            ]
        );

        StockMutation::firstOrCreate(
            ['reference_no' => 'MUT-20261128-003'],
            [
                'item_id' => $mic->id,
                'user_id' => $petugas->id,
                'supplier_id' => null,
                'from_location_id' => $locStudioB->id,
                'to_location_id' => null,
                'type' => 'keluar',
                'quantity' => 1,
                'mutation_date' => now(),
                'notes' => 'Penggunaan mic untuk produksi konten luar studio',
            ]
        );
    }
}
