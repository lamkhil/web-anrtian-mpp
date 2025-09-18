<?php

namespace App\Filament\Pages;

use App\Models\AntrianSkck;
use Filament\Pages\SimplePage;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class AntrianSkckBerjalanPage extends SimplePage implements HasTable
{
    use InteractsWithTable;

    protected ?string $maxWidth = 'fit';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.antrian-skck-berjalan-page';

    protected static ?string $title = 'Antrian Terdaftar Hari Ini';

    public function hasLogo(): bool
    {
        return false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('antrian', 'asc')
            ->headerActions([
                Action::make('daftar')
                    ->label('Daftar Antrian')
                    ->action(function () {
                        return $this->redirect('/');
                    }),
                Action::make('print')
                    ->label('Print')
                    ->icon('heroicon-o-printer')
                    ->color('info')
                    ->action(function () {
                        return $this->redirect('/terdaftar/print');
                    }),
            ])
            ->query(
                AntrianSkck::query()
                    ->whereDate('created_at', now()->toDateString())
            )
            ->columns([
                TextColumn::make('nama')->label('Nama')
                    ->searchable(),
                TextColumn::make('nik')->label('NIK')
                    ->searchable(),
                TextColumn::make('antrian')->label('Nomor Antrian')
                    ->alignCenter()
                    ->formatStateUsing(fn ($state) => str_pad($state, 3, '0', STR_PAD_LEFT)),
                TextColumn::make('nomor_whatsapp')->label('Nomor Whatsapp')
                    ->searchable(),
            ])
            ->poll('5s')
            ->paginated(false);
    }
}
