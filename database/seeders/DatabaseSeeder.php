<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // 1. Seed Roles, Permissions, and default Super Admin
        $this->call(RoleAndPermissionSeeder::class);

        // 2. Create Master Territories (Sumedang, West Java)
        $province = \App\Models\Province::firstOrCreate(
            ['code' => '32'],
            ['name' => 'Jawa Barat']
        );

        $regency = \App\Models\Regency::firstOrCreate(
            ['code' => '3211'],
            [
                'province_id' => $province->id,
                'name' => 'Kabupaten Sumedang'
            ]
        );

        $districts = [];
        $districtNames = [
            '321101' => 'Sumedang Selatan',
            '321102' => 'Sumedang Utara',
            '321103' => 'Jatinangor',
        ];

        foreach ($districtNames as $code => $name) {
            $districts[$name] = \App\Models\District::firstOrCreate(
                ['code' => $code],
                [
                    'regency_id' => $regency->id,
                    'name' => $name
                ]
            );
        }

        $villages = [];
        $villages['Kota Kulon'] = \App\Models\Village::firstOrCreate(
            ['code' => '3211011'],
            [
                'district_id' => $districts['Sumedang Selatan']->id,
                'name' => 'Kota Kulon'
            ]
        );
        $villages['Regol'] = \App\Models\Village::firstOrCreate(
            ['code' => '3211012'],
            [
                'district_id' => $districts['Sumedang Selatan']->id,
                'name' => 'Regol'
            ]
        );
        $villages['Cibeusi'] = \App\Models\Village::firstOrCreate(
            ['code' => '3211031'],
            [
                'district_id' => $districts['Jatinangor']->id,
                'name' => 'Cibeusi'
            ]
        );
        $villages['Hegarmanah'] = \App\Models\Village::firstOrCreate(
            ['code' => '3211032'],
            [
                'district_id' => $districts['Jatinangor']->id,
                'name' => 'Hegarmanah'
            ]
        );

        // 3. Create Positions
        $positions = [];
        $posData = [
            ['name' => 'Ketua DPC', 'level' => 'DPC', 'description' => 'Ketua Dewan Pimpinan Cabang Kabupaten Sumedang'],
            ['name' => 'Sekretaris DPC', 'level' => 'DPC', 'description' => 'Sekretaris Dewan Pimpinan Cabang Kabupaten Sumedang'],
            ['name' => 'Bendahara DPC', 'level' => 'DPC', 'description' => 'Bendahara Dewan Pimpinan Cabang Kabupaten Sumedang'],
            ['name' => 'Ketua PAC', 'level' => 'PAC', 'description' => 'Ketua Pimpinan Anak Cabang Tingkat Kecamatan'],
            ['name' => 'Sekretaris PAC', 'level' => 'PAC', 'description' => 'Sekretaris Pimpinan Anak Cabang Tingkat Kecamatan'],
            ['name' => 'Bendahara PAC', 'level' => 'PAC', 'description' => 'Bendahara Pimpinan Anak Cabang Tingkat Kecamatan'],
        ];

        foreach ($posData as $pos) {
            $positions[$pos['name']] = \App\Models\Position::firstOrCreate(
                ['name' => $pos['name']],
                [
                    'level' => $pos['level'],
                    'description' => $pos['description']
                ]
            );
        }

        // 4. Create 3 Offices (1 explicit DPC Sumedang for Geofencing, 2 via factory)
        \App\Models\Office::firstOrCreate(
            ['name' => 'Kantor DPC PPP Kabupaten Sumedang'],
            [
                'type' => 'Cabang',
                'address' => 'Jl. Prabu Geusan Ulun No. 25, Sumedang',
                'latitude' => -6.8388,
                'longitude' => 107.9253,
                'radius_meters' => 50,
            ]
        );
        \App\Models\Office::factory()->count(2)->create();

        // 5. Create 100 Members (using User model/factory)
        $members = \App\Models\User::factory()->count(100)->create();

        // 6. Create Committees (assign random active members to positions)
        $activeMembers = \App\Models\User::where('status', 'Aktif')
            ->where('email', '!=', 'admin@ppp.com')
            ->inRandomOrder()
            ->get();

        $index = 0;
        foreach ($positions as $name => $position) {
            if ($activeMembers->has($index)) {
                \App\Models\Committee::create([
                    'member_id' => $activeMembers[$index]->id,
                    'position_id' => $position->id,
                    'sk_number' => 'SK/DPC-SUMEDANG/' . now()->year . '/' . sprintf('%03d', $index + 1),
                    'start_date' => now()->startOfYear(),
                    'end_date' => now()->addYears(5)->endOfYear(),
                    'province_id' => $province->id,
                    'regency_id' => $regency->id,
                    'district_id' => \App\Models\District::inRandomOrder()->first()?->id,
                    'village_id' => \App\Models\Village::inRandomOrder()->first()?->id,
                ]);
                $index++;
            }
        }

        // 7. Create 30 TPS
        \App\Models\Tps::factory()->count(30)->create();

        // 8. Create 10 Events
        \App\Models\Event::factory()->count(10)->create();

        // 9. Seed Articles (Berita Publikasi) for clean homepage presentation
        $articlesData = [
            [
                'title' => 'Konsolidasi Kader DPC PPP Sumedang Siap Sambut Pilkada 2026',
                'slug' => 'konsolidasi-kader-dpc-ppp-sumedang-siap-sambut-pilkada-2026',
                'content' => '<p>Kader DPC PPP Kabupaten Sumedang merapatkan barisan menyambut agenda politik daerah...</p>',
                'status' => 'Published',
                'published_at' => now(),
            ],
            [
                'title' => 'Pelatihan Saksi TPS Tingkat PAC Jatinangor Berjalan Sukses',
                'slug' => 'pelatihan-saksi-tps-tingkat-pac-jatinangor-berjalan-sukses',
                'content' => '<p>Pemberian pembekalan kepada para saksi TPS guna mengawal perolehan suara partai...</p>',
                'status' => 'Published',
                'published_at' => now()->subDays(2),
            ],
            [
                'title' => 'Ketua DPC Resmikan Program Bantuan Sembako untuk Masyarakat',
                'slug' => 'ketua-dpc-resmikan-program-bantuan-sembako-untuk-masyarakat',
                'content' => '<p>Program bakti sosial pembagian sembako menyasar masyarakat prasejahtera...</p>',
                'status' => 'Published',
                'published_at' => now()->subDays(4),
            ],
        ];

        foreach ($articlesData as $art) {
            \App\Models\Article::firstOrCreate(
                ['slug' => $art['slug']],
                [
                    'title' => $art['title'],
                    'content' => $art['content'],
                    'status' => $art['status'],
                    'published_at' => $art['published_at'],
                ]
            );
        }
    }
}
