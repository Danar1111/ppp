<?php

namespace App\Filament\Pages;

use App\Models\Committee;
use App\Models\District;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Village;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

class StrukturOrganisasi extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Struktur Organisasi';
    protected static string|\UnitEnum|null $navigationGroup = 'Manajemen SDM';
    protected static ?int $navigationSort = 3;
    protected string $view = 'filament.pages.struktur-organisasi';

    public string $activeLevel = 'PAC';
    public ?int $selectedRegionId = null;

    public array $quotas = [
        'DPW' => 20,
        'DPC' => 15,
        'PAC' => 10,
        'Ranting' => 5,
    ];

    public function selectRegion(int $id)
    {
        $this->selectedRegionId = $id;
    }

    public function backToRegions()
    {
        $this->selectedRegionId = null;
    }

    public function updatedActiveLevel()
    {
        $this->selectedRegionId = null;
    }

    public function getRegionsProperty(): Collection
    {
        if ($this->selectedRegionId) {
            return collect();
        }

        switch ($this->activeLevel) {
            case 'DPW':
                return Province::withCount('committees')->get();
            case 'DPC':
                return Regency::withCount('committees')->get();
            case 'PAC':
                return District::withCount('committees')->get();
            case 'Ranting':
                return Village::withCount('committees')->get();
            default:
                return collect();
        }
    }

    public function getMembersProperty(): Collection
    {
        if (!$this->selectedRegionId) {
            return collect();
        }

        $query = Committee::with(['member', 'position'])->whereHas('position', function ($q) {
            $q->where('level', $this->activeLevel);
        });

        switch ($this->activeLevel) {
            case 'DPW':
                $query->where('province_id', $this->selectedRegionId);
                break;
            case 'DPC':
                $query->where('regency_id', $this->selectedRegionId);
                break;
            case 'PAC':
                $query->where('district_id', $this->selectedRegionId);
                break;
            case 'Ranting':
                $query->where('village_id', $this->selectedRegionId);
                break;
        }

        return $query->get();
    }

    public function getRegionNameProperty(): string
    {
        if (!$this->selectedRegionId) {
            return '';
        }

        return match ($this->activeLevel) {
            'DPW' => Province::find($this->selectedRegionId)?->name ?? '',
            'DPC' => Regency::find($this->selectedRegionId)?->name ?? '',
            'PAC' => 'Kec. ' . (District::find($this->selectedRegionId)?->name ?? ''),
            'Ranting' => 'Desa ' . (Village::find($this->selectedRegionId)?->name ?? ''),
            default => '',
        };
    }
}
