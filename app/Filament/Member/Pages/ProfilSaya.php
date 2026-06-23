<?php

namespace App\Filament\Member\Pages;

use App\Models\User as Member;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Pages\Page;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Facades\Hash;
use Filament\Notifications\Notification;

class ProfilSaya extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-user';

    protected static ?string $title = 'Profil Saya';

    protected string $view = 'filament.member.pages.profil-saya';

    public ?array $data = [];

    public Member $member;

    public function mount(): void
    {
        $this->member = auth()->user();
        $this->form->fill();
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                TextInput::make('current_password')
                    ->password()
                    ->required()
                    ->label('Password Saat Ini')
                    ->rule('current_password'),
                TextInput::make('new_password')
                    ->password()
                    ->required()
                    ->label('Password Baru')
                    ->minLength(8),
                TextInput::make('new_password_confirmation')
                    ->password()
                    ->required()
                    ->label('Konfirmasi Password Baru')
                    ->same('new_password'),
            ])
            ->statePath('data');
    }

    public function updatePassword(): void
    {
        try {
            $data = $this->form->getState();

            $this->member->update([
                'password' => Hash::make($data['new_password']),
            ]);

            $this->form->fill();

            Notification::make()
                ->title('Password berhasil diperbarui')
                ->success()
                ->send();
        } catch (\Exception $exception) {
            return;
        }
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Perbarui Password')
                ->submit('updatePassword')
                ->color('primary'),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('downloadKta')
                ->label('Unduh KTA Digital')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(function () {
                    $pdf = Pdf::loadView('pdf.kta', ['member' => $this->member])
                        ->setPaper([0, 0, 242.65, 153.01]);
                    return response()->streamDownload(function () use ($pdf) {
                        echo $pdf->output();
                    }, 'kta_' . $this->member->nik . '.pdf');
                }),
        ];
    }
}
