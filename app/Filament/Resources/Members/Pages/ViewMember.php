<?php

namespace App\Filament\Resources\Members\Pages;

use App\Filament\Resources\Members\MemberResource;
use App\Models\User as Member;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewMember extends ViewRecord
{
    protected static string $resource = MemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            Action::make('lihat_kta')
                ->label('Lihat KTA')
                ->icon('heroicon-o-identification')
                ->color('success')
                ->modalContent(fn () => view('components.kta-card', ['member' => $this->getRecord()]))
                ->modalSubmitAction(false)
                ->modalCancelAction(false),
        ];
    }
}
