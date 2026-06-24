<?php

namespace App\Filament\Pages;

use App\Models\Office;
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
        // Try to default to the first office if exists
        $firstOffice = Office::first();
        $this->form->fill([
            'office_id' => $firstOffice?->id,
        ]);
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Select::make('office_id')
                    ->label('Pilih Kantor / Posko')
                    ->options(Office::pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live(),
            ])
            ->statePath('data');
    }

    /**
     * Generate the daily check-in URL based on selected office.
     */
    public function getQrUrl(): string
    {
        $officeId = $this->data['office_id'] ?? null;

        if (!$officeId) {
            return '';
        }

        return route('absen.harian', ['office' => $officeId]);
    }

    /**
     * Get human-readable office name.
     */
    public function getOfficeName(): string
    {
        $officeId = $this->data['office_id'] ?? null;

        if ($officeId) {
            $office = Office::find($officeId);
            return $office ? $office->name : 'Kantor Sekretariat PPP';
        }

        return 'Kantor Sekretariat PPP';
    }
}
