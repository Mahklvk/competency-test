<?php

namespace App\Filament\Resources\Produks\Tables;

use App\Models\Produk;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ProduksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnail') // show thumbnail
                    ->searchable(),
                    
                ImageColumn::make('photos.photo') // show photo
                    ->label('Photo')
                    ->limit(1),

                TextColumn::make('name') // show nama produk
                    ->label('Nama Produk')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('price') // show price
                    ->label('Harga Produk')
                    ->searchable()
                    ->sortable()
                    ->money('IDR'),

                TextColumn::make('sizes.size') // show size 
                    ->label('Ukuran')
                    ->badge()
                    ->limitList(2),

                TextColumn::make('category_id') // category
                    ->searchable()
                    ->sortable()
                    ->label('Kategori'),

                TextColumn::make('brand_id') // brand
                    ->label('Merek')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('stock') // stock table
                    ->label('Stok')
                    ->searchable()
                    ->sortable(),

                IconColumn::make('is_popular') // populer ga nih
                    ->label('Populer')
                    ->boolean()
                    ->trueIcon(Heroicon::OutlinedCheckBadge) // kalo populer  ini
                    ->falseIcon(Heroicon::OutlinedXMark)  // kalo engga ini tampilin
                    ->searchable()
                    ->sortable(),

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
