<?php

namespace App\Filament\Resources\PromoCodes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class PromoCodesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code') // show kode
                    ->searchable()
                    ->label('Kode'),

                TextColumn::make('discount_amount') // isi diskonnya
                    ->label('Total Diskon')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('created_at') // created at 
                    ->label('Dibuat Tanggal')
                    ->searchable()
                    ->sortable()
                    ->dateTime(timezone: 'Asia/Jakarta'),

                TextColumn::make('updated_at') // updated at
                    ->label('Diperbarui Tanggal')
                    ->searchable()
                    ->sortable()
                    ->dateTime(timezone: 'Asia/Jakarta'),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make()
                    ->color('success'),
                EditAction::make(),
                DeleteAction::make()
                    ->color('danger'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
