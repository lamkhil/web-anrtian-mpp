<?php

namespace App\Filament\Pages;

use App\Models\AntrianSkck;
use DateTime;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\SimplePage;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;

class AntrianSkckPage extends SimplePage implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.antrian-skck-page';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $title = 'Antrian SKCK MPP Siola';

    const limit1 = '04:30';

    const limit2 = '16:00';

    const limitAntrian = 100;

    public function hasLogo(): bool
    {
        return false;
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make([
                    Section::make()
                        ->schema([Placeholder::make('Kuota')
                            ->extraAttributes([
                                'class' => 'font-bold text-xl text-center',
                            ])
                            ->content(AntrianSkckPage::limitAntrian.' / Hari')])
                        ->columnSpan(1),
                    Section::make()
                        ->schema([Placeholder::make('Terisi')
                            ->extraAttributes([
                                'class' => 'font-bold text-xl text-center',
                            ])
                            ->content(function () {
                                $today = now()->toDateString();
                                $count = AntrianSkck::whereDate('created_at', $today)->count();

                                return $count.' Antrian';
                            })])
                        ->columnSpan(1),
                ])->visible(function () {
                    $now = new DateTime;
                    $limit = new DateTime(AntrianSkckPage::limit1);
                    $limit2 = new DateTime(AntrianSkckPage::limit2);

                    return $now > $limit && $now < $limit2;
                })
                    ->columns(),

                Group::make([
                    TextInput::make('nama')
                        ->label('Nama Lengkap Sesuai KTP')
                        ->required(),
                    TextInput::make('nik')
                        ->required()
                        ->unique()
                        ->minLength(16)
                        ->label('NIK'),
                    TextInput::make('nomor_whatsapp')
                        ->required()
                        ->unique()
                        ->label('Nomor Whatsapp'),

                    Actions::make([
                        Action::make('Kirim')
                            ->extraAttributes([
                                'class' => 'w-full',
                            ])
                            ->requiresConfirmation()
                            ->modalHeading(new HtmlString('Pastikan data sesuai'))
                            ->modalDescription(new HtmlString('Jika data tidak sesuai nomor antrian anda akan dibatalkan!'))
                            ->action(function () {
                                $data = $this->form->getState();
                                $antrian = null;
                                DB::transaction(function () use ($data, &$antrian) {
                                    $today = now()->toDateString();

                                    if (now()->day == 17) {
                                        $today = '2025-09-18';
                                    }

                                    // Cari antrian terakhir HARI INI dengan lock
                                    $last = AntrianSkck::lockForUpdate()
                                        ->whereDate('created_at', $today)
                                        ->orderByDesc('antrian')
                                        ->first();

                                    $next = $last ? $last->antrian + 1 : 1;

                                    if ($next > AntrianSkckPage::limitAntrian) {
                                        Notification::make('fail')
                                            ->title('Oops!')
                                            ->body('Antrian hari ini sudah penuh, silakan datang besok.')
                                            ->danger()
                                            ->send();

                                        return;
                                    }

                                    $antrian = AntrianSkck::create([
                                        'nama' => $data['nama'],
                                        'nik' => $data['nik'],
                                        'nomor_whatsapp' => $data['nomor_whatsapp'],
                                        'antrian' => $next,
                                    ]);

                                    if (now()->day == 17) {
                                        $antrian->update([
                                            'created_at' => '2025-09-18 04:30:02',
                                        ]);
                                    }

                                });

                                if ($antrian != null) {
                                    $this->redirect('/print/SKCK'.base64_encode($antrian->id));
                                }
                            }),

                        Action::make('Cek Antrian Terdaftar')
                            ->extraAttributes([
                                'class' => 'w-full',
                            ])
                            ->color(Color::Green)
                            ->action(function ($data) {

                                $this->js('window.open("'.'/terdaftar", "_blank");');
                            })
                            ->visible(false),
                    ]),
                ])
                    ->visible(function () {
                        $now = new DateTime;
                        $limit = new DateTime(AntrianSkckPage::limit1);
                        $limit2 = new DateTime(AntrianSkckPage::limit2);

                        $today = now()->toDateString();
                        $count = AntrianSkck::whereDate('created_at', $today)->count();

                        return $now > $limit && $now < $limit2 && $count < AntrianSkckPage::limitAntrian;
                    }),
                Placeholder::make('tutup')
                    ->label('')
                    ->extraAttributes([
                        'class' => 'mt-8 text-center font-bold text-xl text-red-600',
                    ])
                    ->content('Antrian SKCK Masih Tutup')
                    ->visible(function () {
                        $now = new DateTime;
                        $limit = new DateTime(AntrianSkckPage::limit1);
                        $limit2 = new DateTime(AntrianSkckPage::limit2);

                        $today = now()->toDateString();
                        $count = AntrianSkck::whereDate('created_at', $today)->count();

                        return $now < $limit || $now > $limit2 || $count >= AntrianSkckPage::limitAntrian;
                    }),
                Placeholder::make('tutup')
                    ->label('')
                    ->extraAttributes([
                        'class' => 'text-center',
                    ])
                    ->content('Pendaftaran antrian SKCK dimulai pukul '.AntrianSkckPage::limit1.' WIB sampai dengan pukul '.AntrianSkckPage::limit2.' WIB selama kuota masih tersedia. Silahkan mengisikan data anda dengan benar!')
                    ->visible(function () {
                        $now = new DateTime;
                        $limit = new DateTime(AntrianSkckPage::limit1);
                        $limit2 = new DateTime(AntrianSkckPage::limit2);

                        $today = now()->toDateString();
                        $count = AntrianSkck::whereDate('created_at', $today)->count();

                        return $now < $limit || $now > $limit2 || $count >= AntrianSkckPage::limitAntrian;
                    }),
                Actions::make([
                    Action::make('Cetak Ulang Antrian')
                        ->extraAttributes([
                            'class' => 'w-full',
                        ])
                        ->color(Color::Blue)
                        ->form(function ($form) {
                            return $form->schema([
                                TextInput::make('nik')->label('NIK')->required(),
                            ]);
                        })
                        ->action(function ($data) {
                            $nik = $data['nik'];
                            $antrian = AntrianSkck::where('nik', $nik)->first();

                            if ($antrian == null) {
                                Notification::make('fail')
                                    ->title('Oops!')
                                    ->body('NIK Anda belum terdaftar')
                                    ->danger()
                                    ->send();

                                return;
                            }

                            $this->redirect('/print/SKCK'.base64_encode($antrian->id));
                        }),
                ]),
            ])
            ->statePath('data');
    }
}
