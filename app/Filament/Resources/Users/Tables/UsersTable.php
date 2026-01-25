<?php

namespace App\Filament\Resources\Users\Tables;

use App\Filament\Exports\UserExporter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                ->label('Nama pengguna')
                ->searchable()
                ->sortable(),
                TextColumn::make('email')
                ->label('Email Pengguna')
                ->searchable(),
            ])
            ->filters([
               //
            ])
            ->recordActions([
                ViewAction::make()
                ->color('success'),
                EditAction::make(),
                DeleteAction::make()
                ->color('danger')
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
            ExportAction::make()
                ->exporter(UserExporter::class),
            ]);
    }
}
