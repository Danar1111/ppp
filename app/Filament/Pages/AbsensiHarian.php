<?php

namespace App\Filament\Pages;

use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Pages\Page;

class AbsensiHarian extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-qr-code';

    protected static string|\UnitEnum|null $navigationGroup = 'Operasional & Arsip';

    protected static ?int $navigationSort = 4;

    protected static ?string $title = 'QR Absen Harian';

    protected string $view = 'filament.pages.absensi-harian';

    // State container for form data
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'level' => 'DPP',
        ]);
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Section::make('Konfigurasi Kantor Sekretariat')
                    ->description('Pilih tingkat kantor sekretariat untuk menghasilkan QR Code presensi harian.')
                    ->schema([
                        Select::make('level')
                            ->label('Tingkat Kantor')
                            ->options([
                                'DPP' => 'Pusat (DPP)',
                                'DPW' => 'Wilayah (DPW)',
                                'DPC' => 'Cabang (DPC)',
                                'PAC' => 'Kecamatan (PAC)',
                            ])
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($set) {
                                $set('province_code', null);
                                $set('regency_code', null);
                                $set('district_code', null);
                            }),

                        Select::make('province_code')
                            ->label('Provinsi')
                            ->options(Province::pluck('name', 'code'))
                            ->required()
                            ->visible(fn ($get) => $get('level') === 'DPW')
                            ->live(),

                        Select::make('regency_code')
                            ->label('Kabupaten / Kota')
                            ->options(Regency::pluck('name', 'code'))
                            ->required()
                            ->visible(fn ($get) => $get('level') === 'DPC')
                            ->live(),

                        Select::make('district_code')
                            ->label('Kecamatan')
                            ->options(District::pluck('name', 'code'))
                            ->required()
                            ->visible(fn ($get) => $get('level') === 'PAC')
                            ->live(),
                    ])
                    ->columns(2)
            ])
            ->statePath('data');
    }

    /**
     * Generate the daily check-in URL based on selected level and region.
     */
    public function getQrUrl(): string
    {
        $level = $this->data['level'] ?? 'DPP';
        $code = 'nasional';

        if (empty($level)) {
            $level = 'DPP';
        }

        if ($level === 'DPW') {
            $code = $this->data['province_code'] ?? 'test-dpw';
        } elseif ($level === 'DPC') {
            $code = $this->data['regency_code'] ?? 'test-dpc';
        } elseif ($level === 'PAC') {
            $code = $this->data['district_code'] ?? 'test-pac';
        }

        if (empty($code)) {
            $code = 'test-' . strtolower($level);
        }

        return route('absen.harian', ['level' => $level, 'code' => $code]);
    }

    /**
     * Get human-readable office name.
     */
    public function getOfficeName(): string
    {
        $level = $this->data['level'] ?? 'DPP';

        if (empty($level)) {
            $level = 'DPP';
        }

        if ($level === 'DPP') {
            return 'Kantor Pusat DPP PPP';
        }

        if ($level === 'DPW' && ($this->data['province_code'] ?? null)) {
            $province = Province::where('code', $this->data['province_code'])->first();
            return 'Kantor DPW PPP Provinsi ' . ($province ? $province->name : '');
        }

        if ($level === 'DPC' && ($this->data['regency_code'] ?? null)) {
            $regency = Regency::where('code', $this->data['regency_code'])->first();
            return 'Kantor DPC PPP ' . ($regency ? $regency->name : '');
        }

        if ($level === 'PAC' && ($this->data['district_code'] ?? null)) {
            $district = District::where('code', $this->data['district_code'])->first();
            return 'Kantor PAC PPP Kecamatan ' . ($district ? $district->name : '');
        }

        return 'Kantor Sekretariat PPP';
    }
}
