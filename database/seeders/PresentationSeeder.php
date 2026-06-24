<?php

namespace Database\Seeders;

use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;
use App\Models\Position;
use App\Models\User as Member;
use App\Models\Committee;
use App\Models\Article;
use App\Models\Event;
use App\Models\Tps;
use App\Models\Office;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class PresentationSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // 1. Seed Master Wilayah (Sumedang, Jawa Barat)
        $province = Province::firstOrCreate(
            ['code' => '32'],
            ['name' => 'Jawa Barat']
        );

        $regency = Regency::firstOrCreate(
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
            $districts[$name] = District::firstOrCreate(
                ['code' => $code],
                [
                    'regency_id' => $regency->id,
                    'name' => $name
                ]
            );
        }

        $villages = [];
        // Sumedang Selatan villages
        $villages['Kota Kulon'] = Village::firstOrCreate(
            ['code' => '3211011'],
            [
                'district_id' => $districts['Sumedang Selatan']->id,
                'name' => 'Kota Kulon'
            ]
        );
        $villages['Regol'] = Village::firstOrCreate(
            ['code' => '3211012'],
            [
                'district_id' => $districts['Sumedang Selatan']->id,
                'name' => 'Regol'
            ]
        );

        // Jatinangor villages
        $villages['Cibeusi'] = Village::firstOrCreate(
            ['code' => '3211031'],
            [
                'district_id' => $districts['Jatinangor']->id,
                'name' => 'Cibeusi'
            ]
        );
        $villages['Hegarmanah'] = Village::firstOrCreate(
            ['code' => '3211032'],
            [
                'district_id' => $districts['Jatinangor']->id,
                'name' => 'Hegarmanah'
            ]
        );

        // 2. Seed Jabatan (Positions)
        $positions = [];
        $posData = [
            ['name' => 'Ketua DPC', 'level' => 'DPC', 'description' => 'Ketua Dewan Pimpinan Cabang Kabupaten Sumedang'],
            ['name' => 'Sekretaris DPC', 'level' => 'DPC', 'description' => 'Sekretaris Dewan Pimpinan Cabang Kabupaten Sumedang'],
            ['name' => 'Bendahara DPC', 'level' => 'DPC', 'description' => 'Bendahara Dewan Pimpinan Cabang Kabupaten Sumedang'],
            ['name' => 'Ketua PAC', 'level' => 'PAC', 'description' => 'Ketua Pimpinan Anak Cabang Tingkat Kecamatan'],
        ];

        foreach ($posData as $pos) {
            $positions[$pos['name']] = Position::firstOrCreate(
                ['name' => $pos['name']],
                [
                    'level' => $pos['level'],
                    'description' => $pos['description']
                ]
            );
        }

        // 3. Seed Ahmad Fauzi (Test Member)
        $testMember = Member::firstOrCreate(
            ['nik' => '3211012345678901'],
            [
                'name' => 'Ahmad Fauzi',
                'email' => 'ahmad@ppp.com',
                'phone' => '081234567890',
                'address' => 'Jl. Prabu Geusan Ulun No. 12, Kel. Kota Kulon, Sumedang',
                'village_id' => $villages['Kota Kulon']->id,
                'status' => 'Aktif',
                'password' => Hash::make('password'),
            ]
        );

        // 4. Seed 20 Faker Members (Indonesian Names)
        $members = [$testMember];
        $villageKeys = array_keys($villages);

        for ($i = 0; $i < 20; $i++) {
            $status = $faker->randomElement(['Aktif', 'Aktif', 'Aktif', 'Pending', 'Nonaktif']);
            $randomVillage = $villages[$faker->randomElement($villageKeys)];
            
            $members[] = Member::create([
                'nik' => $faker->unique()->numerify('3211############'),
                'name' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'phone' => $faker->numerify('08##########'),
                'address' => $faker->address(),
                'village_id' => $randomVillage->id,
                'status' => $status,
                'password' => Hash::make('password'),
            ]);
        }

        // 5. Seed Committee (Assign Ahmad Fauzi as Ketua DPC)
        Committee::firstOrCreate(
            [
                'member_id' => $testMember->id,
                'position_id' => $positions['Ketua DPC']->id,
            ],
            [
                'sk_number' => 'SK/DPC-SUMEDANG/2026/001',
                'start_date' => '2026-01-01',
                'end_date' => '2031-01-01',
            ]
        );

        // Assign Sekretaris DPC and Bendahara DPC to other active members
        $activeMembers = array_filter($members, fn($m) => $m->status === 'Aktif' && $m->id !== $testMember->id);
        $activeMembers = array_values($activeMembers);

        if (isset($activeMembers[0])) {
            Committee::firstOrCreate(
                [
                    'member_id' => $activeMembers[0]->id,
                    'position_id' => $positions['Sekretaris DPC']->id,
                ],
                [
                    'sk_number' => 'SK/DPC-SUMEDANG/2026/002',
                    'start_date' => '2026-01-01',
                    'end_date' => '2031-01-01',
                ]
            );
        }

        if (isset($activeMembers[1])) {
            Committee::firstOrCreate(
                [
                    'member_id' => $activeMembers[1]->id,
                    'position_id' => $positions['Bendahara DPC']->id,
                ],
                [
                    'sk_number' => 'SK/DPC-SUMEDANG/2026/003',
                    'start_date' => '2026-01-01',
                    'end_date' => '2031-01-01',
                ]
            );
        }

        // 6. Seed Articles (Berita Publikasi)
        $articlesData = [
            [
                'title' => 'Konsolidasi Kader DPC PPP Sumedang Siap Sambut Pilkada 2026',
                'slug' => 'konsolidasi-kader-dpc-ppp-sumedang-siap-sambut-pilkada-2026',
                'content' => '<p>' . implode('</p><p>', $faker->paragraphs(3)) . '</p>',
                'status' => 'Published',
                'published_at' => now(),
            ],
            [
                'title' => 'Pelatihan Saksi TPS Tingkat PAC Jatinangor Berjalan Sukses',
                'slug' => 'pelatihan-saksi-tps-tingkat-pac-jatinangor-berjalan-sukses',
                'content' => '<p>' . implode('</p><p>', $faker->paragraphs(3)) . '</p>',
                'status' => 'Published',
                'published_at' => now()->subDays(2),
            ],
            [
                'title' => 'Ketua DPC Resmikan Program Bantuan Sembako untuk Masyarakat',
                'slug' => 'ketua-dpc-resmikan-program-bantuan-sembako-untuk-masyarakat',
                'content' => '<p>' . implode('</p><p>', $faker->paragraphs(3)) . '</p>',
                'status' => 'Published',
                'published_at' => now()->subDays(4),
            ],
        ];

        foreach ($articlesData as $art) {
            Article::firstOrCreate(
                ['slug' => $art['slug']],
                [
                    'title' => $art['title'],
                    'content' => $art['content'],
                    'status' => $art['status'],
                    'published_at' => $art['published_at'],
                ]
            );
        }

        // 7. Seed Events (Agenda)
        // Past Event
        Event::firstOrCreate(
            ['name' => 'Rapat Kerja Cabang (Rakercab) PPP Sumedang'],
            [
                'description' => 'Evaluasi program kerja tahunan dan pemantapan visi misi partai tingkat kabupaten.',
                'location' => 'Gedung Islamic Center Sumedang',
                'latitude' => '-6.8402',
                'longitude' => '107.9234',
                'start_datetime' => now()->subMonth()->setHour(9)->setMinute(0),
                'end_datetime' => now()->subMonth()->setHour(16)->setMinute(0),
                'status' => 'Selesai',
            ]
        );

        // Upcoming Event
        Event::firstOrCreate(
            ['name' => 'Pelatihan Kader Madya PPP Sumedang'],
            [
                'description' => 'Materi kepemimpinan taktis partai, ideologi kebangsaan, dan manajemen kampanye modern.',
                'location' => 'Kantor DPC PPP Kabupaten Sumedang',
                // Coordinates for Sumedang (Office location for Geofence test)
                'latitude' => '-6.8388',
                'longitude' => '107.9253',
                'start_datetime' => now()->addWeek()->setHour(8)->setMinute(0),
                'end_datetime' => now()->addWeek()->setHour(17)->setMinute(0),
                'status' => 'Akan Datang',
            ]
        );

        // 8. Seed TPS (Tempat Pemungutan Suara)
        $tpsData = [
            [
                'name' => 'TPS 001 Kota Kulon',
                'village_name' => 'Kota Kulon',
                'latitude' => '-6.8350',
                'longitude' => '107.9210',
                'status' => 'Selesai',
            ],
            [
                'name' => 'TPS 002 Kota Kulon',
                'village_name' => 'Kota Kulon',
                'latitude' => '-6.8410',
                'longitude' => '107.9290',
                'status' => 'Pending',
            ],
            [
                'name' => 'TPS 001 Regol',
                'village_name' => 'Regol',
                'latitude' => '-6.8450',
                'longitude' => '107.9180',
                'status' => 'Selesai',
            ],
            [
                'name' => 'TPS 001 Cibeusi',
                'village_name' => 'Cibeusi',
                'latitude' => '-6.9290',
                'longitude' => '107.7690',
                'status' => 'Pending',
            ],
            [
                'name' => 'TPS 002 Hegarmanah',
                'village_name' => 'Hegarmanah',
                'latitude' => '-6.9330',
                'longitude' => '107.7760',
                'status' => 'Selesai',
            ],
        ];

        foreach ($tpsData as $data) {
            $villageRecord = Village::where('name', $data['village_name'])->first();
            if ($villageRecord) {
                Tps::firstOrCreate(
                    ['name' => $data['name']],
                    [
                        'village_id' => $villageRecord->id,
                        'latitude' => $data['latitude'],
                        'longitude' => $data['longitude'],
                        'status' => $data['status'],
                    ]
                );
            }
        }

        // 9. Seed Offices (Kantor / Posko)
        $officesData = [
            [
                'name' => 'Kantor Pusat DPP PPP Jakarta',
                'type' => 'Utama',
                'address' => 'Jl. Diponegoro No. 60, Menteng, Jakarta Pusat',
                'latitude' => -6.2008,
                'longitude' => 106.8438,
                'radius_meters' => 100,
            ],
            [
                'name' => 'Kantor DPW PPP Jawa Barat',
                'type' => 'Cabang',
                'address' => 'Jl. Pelajar Pejuang 45 No. 45, Bandung',
                'latitude' => -6.9292,
                'longitude' => 107.6253,
                'radius_meters' => 75,
            ],
            [
                'name' => 'Kantor DPC PPP Kabupaten Sumedang',
                'type' => 'Cabang',
                'address' => 'Jl. Prabu Geusan Ulun No. 25, Sumedang',
                'latitude' => -6.8388,
                'longitude' => 107.9253,
                'radius_meters' => 50,
            ],
            [
                'name' => 'Posko PAC PPP Jatinangor',
                'type' => 'Posko',
                'address' => 'Jl. Raya Bandung-Sumedang No. 88, Jatinangor',
                'latitude' => -6.9290,
                'longitude' => 107.7690,
                'radius_meters' => 30,
            ],
        ];

        foreach ($officesData as $off) {
            Office::firstOrCreate(
                ['name' => $off['name']],
                [
                    'type' => $off['type'],
                    'address' => $off['address'],
                    'latitude' => $off['latitude'],
                    'longitude' => $off['longitude'],
                    'radius_meters' => $off['radius_meters'],
                ]
            );
        }
    }
}
