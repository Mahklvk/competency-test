<?php

namespace App\Filament\Resources\Produks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ProduksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnail')
                ->searchable(),
                TextColumn::make('name')
                ->label('Nama Produk')
                ->searchable()
                ->sortable(),
                TextColumn::make('price')
                ->label('Harga Produk')
                ->searchable()
                ->sortable()
                ->money('IDR'),
                TextColumn::make('category_id')
                ->searchable()
                ->sortable()
                ->label('Kategori'),
                TextColumn::make('brand_id')
                ->label('Merek')
                ->searchable()
                ->sortable(),
                TextColumn::make('stock')
                ->label('Stok')
                ->searchable()
                ->sortable(),
                IconColumn::make('is_popular')
                ->label('Populer')
                ->boolean()
                ->trueIcon(Heroicon::OutlinedCheckBadge)
                ->falseIcon(Heroicon::OutlinedXMark)
                ->searchable()
                ->sortable()

            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
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
