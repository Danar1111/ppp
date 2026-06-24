<?php

namespace App\Filament\Pages;

use App\Models\Event;
use App\Models\Tps;
use App\Models\Office;
use Filament\Pages\Page;

class CommandCenter extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-map';

    protected static string|\UnitEnum|null $navigationGroup = null;

    protected static ?string $title = 'Peta';

    protected static ?int $navigationSort = -1;

    protected string $view = 'filament.pages.command-center';

    public bool $showTps = true;
    public bool $showEvents = true;
    public bool $showOffices = true;

    public function getTpsData(): array
    {
        if (!$this->showTps) {
            return [];
        }
        return Tps::with('village')->get()->map(function ($tps) {
            return [
                'id' => $tps->id,
                'name' => $tps->name,
                'village_name' => $tps->village?->name ?? 'N/A',
                'latitude' => $tps->latitude,
                'longitude' => $tps->longitude,
                'status' => $tps->status,
            ];
        })->toArray();
    }

    public function getEventsData(): array
    {
        if (!$this->showEvents) {
            return [];
        }
        return Event::all()->map(function ($event) {
            return [
                'id' => $event->id,
                'name' => $event->name,
                'location' => $event->location,
                'latitude' => $event->latitude,
                'longitude' => $event->longitude,
                'status' => $event->status,
            ];
        })->toArray();
    }

    public function getOfficesData(): array
    {
        if (!$this->showOffices) {
            return [];
        }
        return Office::all()->map(function ($office) {
            return [
                'id' => $office->id,
                'name' => $office->name,
                'type' => $office->type,
                'address' => $office->address,
                'latitude' => $office->latitude,
                'longitude' => $office->longitude,
                'radius_meters' => $office->radius_meters,
            ];
        })->toArray();
    }
}
