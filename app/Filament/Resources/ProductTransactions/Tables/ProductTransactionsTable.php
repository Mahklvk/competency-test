<?php

namespace App\Filament\Resources\ProductTransactions\Tables;

use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ProductTransactionsTable
{
    public static function configure(Table $table): Table
    {
        // **************************************************
        // **       Showing Categories Table              **
        // *************************************************
        return $table
            ->columns([
                TextColumn::make('name') //showing data name
                    ->label('Nama Pelanggan')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('phone') // showing data phone
                    ->label('Nomor Handphone Pelanggan')
                    ->searchable(),

                TextColumn::make('email')
                    ->label('email pelanggan') // showing data phone
                    ->searchable(),

                TextColumn::make('booking_trx_id') // showing booking id
                    ->label('ID Booking TRX')
                    ->searchable(),

                TextColumn::make('Produk.name') // get relation from produk name
                    ->label('Nama Produk')
                    ->searchable(),

                TextColumn::make('Produk.price') // get relation from produk price
                    ->label('Harga Produk')
                    ->searchable(),

                TextColumn::make('quantity') // showing quantity data
                    ->label('Jumlah Produk')
                    ->searchable(),

                TextColumn::make('produk_size') // showing produk size data
                    ->label('Ukuran Produk')
                    ->searchable(),

                TextColumn::make('promoCode.code') // get relation promoCode
                    ->label('Kode Promo')
                    ->searchable(),

                TextColumn::make('sub_total_amount') // get sub total amount
                    ->label('Total Seluruh Harga')
                    ->searchable(),

                IconColumn::make('is_paid') // showing status 
                    ->label('Sudah/Belum Terbayar')
                    ->boolean()
                    ->trueIcon(Heroicon::CheckCircle)
                    ->falseIcon(Heroicon::XMark),
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
