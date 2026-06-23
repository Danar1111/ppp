<?php

namespace App\Filament\Member\Resources\Attendances\Pages;

use App\Filament\Member\Resources\Attendances\AttendanceResource;
use Filament\Resources\Pages\ManageRecords;

class ManageAttendances extends ManageRecords
{
    protected static string $resource = AttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Read-only, no header actions (like create)
        ];
    }
}
