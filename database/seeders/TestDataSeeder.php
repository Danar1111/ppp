<?php

namespace Database\Seeders;

use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;
use App\Models\Position;
use App\Models\Member;
use App\Models\Event;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Master Wilayah
        $province = Province::firstOrCreate(
            ['code' => '32'],
            ['name' => 'Jawa Barat']
        );

        $regency = Regency::firstOrCreate(
            ['code' => '3273'],
            [
                'province_id' => $province->id,
                'name' => 'Kota Bandung'
            ]
        );

        $district = District::firstOrCreate(
            ['code' => '3273010'],
            [
                'regency_id' => $regency->id,
                'name' => 'Sumur Bandung'
            ]
        );

        $village = Village::firstOrCreate(
            ['code' => '3273010001'],
            [
                'district_id' => $district->id,
                'name' => 'Braga'
            ]
        );

        // 2. Seed Master Jabatan
        Position::firstOrCreate(
            ['name' => 'Ketua Wilayah'],
            [
                'level' => 'DPW',
                'description' => 'Ketua DPW Wilayah Tingkat Provinsi'
            ]
        );

        // 3. Seed Test Member (Budi Santoso)
        $member = Member::firstOrCreate(
            ['nik' => '3273010203040001'],
            [
                'name' => 'Budi Santoso',
                'phone' => '081234567890',
                'address' => 'Jl. Braga No. 10, Kota Bandung',
                'village_id' => $village->id,
                'status' => 'Aktif',
                'password' => Hash::make('password'),
            ]
        );

        // 4. Seed Test Event (Muktamar Wilayah)
        Event::firstOrCreate(
            ['name' => 'Muktamar Wilayah PPP Jawa Barat'],
            [
                'description' => 'Konsolidasi internal kepengurusan partai tingkat wilayah Jawa Barat.',
                'location' => 'Kantor DPW Jabar',
                // Mock coordinate (matches DPW Jawa Barat mock coordinates for testing proximity!)
                'latitude' => '-6.9175',
                'longitude' => '107.6191',
                'start_datetime' => now()->subHours(2),
                'end_datetime' => now()->addHours(6),
                'status' => 'Sedang Berjalan',
            ]
        );

        // 5. Seed Test Committee (Budi Santoso as Ketua Wilayah)
        $pos = Position::where('name', 'Ketua Wilayah')->first();
        if ($pos && $member) {
            \App\Models\Committee::firstOrCreate(
                [
                    'member_id' => $member->id,
                    'position_id' => $pos->id,
                ],
                [
                    'sk_number' => 'SK/DPW-PPP/2024/001',
                    'start_date' => '2024-01-01',
                    'end_date' => '2029-01-01',
                ]
            );
        }

        // 6. Seed Sample Articles
        \App\Models\Article::firstOrCreate(
            ['slug' => 'harlah-ppp-ke-53-membangun-indonesia'],
            [
                'title' => 'Peringatan Harlah PPP ke-53: Membangun Indonesia dengan Kebersamaan Umat',
                'content' => '<p>DPC Partai Persatuan Pembangunan (PPP) sukses menggelar peringatan Hari Lahir (Harlah) PPP ke-53 dengan serangkaian kegiatan bakti sosial dan doa bersama untuk bangsa. Acara ini dihadiri oleh jajaran fungsionaris partai, tokoh masyarakat, dan ratusan kader daerah.</p><p>Ketua DPC menyampaikan bahwa usia 53 tahun adalah momentum penting untuk melakukan refleksi perjuangan dalam memperjuangkan hak-hak dasar umat dan mewujudkan kemakmuran bangsa yang merata.</p>',
                'status' => 'Published',
                'published_at' => now(),
            ]
        );

        \App\Models\Article::firstOrCreate(
            ['slug' => 'konsolidasi-kader-menyambut-pemilu-2029'],
            [
                'title' => 'Konsolidasi Kader DPC PPP Menyambut Pemilu Legislatif 2029',
                'content' => '<p>Menjelang Pemilu Legislatif 2029, jajaran pengurus DPC PPP melaksanakan rapat koordinasi taktis tingkat wilayah. Kegiatan ini bertujuan memperkuat struktur jaringan ranting hingga PAC di seluruh wilayah administratif.</p><p>Kader-kader ditekankan untuk senantiasa turun langsung mendampingi dan mendengar aspirasi konstituen secara nyata.</p>',
                'status' => 'Published',
                'published_at' => now()->subDays(2),
            ]
        );

        \App\Models\Article::firstOrCreate(
            ['slug' => 'pembagian-paket-sembako-braga'],
            [
                'title' => 'Aksi Nyata Sosial: DPC PPP Bagikan Ratusan Paket Sembako di Braga',
                'content' => '<p>Partai Persatuan Pembangunan berkomitmen untuk terus konsisten melakukan kerja-kerja sosial kemanusiaan. Melalui gerakan "PPP Berbagi", fungsionaris menyalurkan paket kebutuhan pokok kepada keluarga prasejahtera di Kelurahan Braga, Bandung.</p>',
                'status' => 'Published',
                'published_at' => now()->subDays(5),
            ]
        );
    }
}
