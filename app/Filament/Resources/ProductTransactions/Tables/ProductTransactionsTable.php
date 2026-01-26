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
        return $table
            ->columns([
                TextColumn::make('name')
                ->label('Nama Pelanggan')
                ->sortable()
                ->searchable(),
                TextColumn::make('phone')
                ->label('Nomor Handphone Pelanggan')
                ->searchable(),
                TextColumn::make('email')
                ->label('email pelanggan')
                ->searchable(),
                TextColumn::make('booking_trx_id')
                ->label('ID Booking TRX')
                ->searchable(),
                TextColumn::make('Produk.name')
                ->label('Nama Produk')
                ->searchable(),
                TextColumn::make('Produk.price')
                ->label('Harga Produk')
                ->searchable(),
                TextColumn::make('quantity')
                ->label('Jumlah Produk')
                ->searchable(),
                TextColumn::make('produk_size')
                ->label('Ukuran Produk')
                ->searchable(),
                TextColumn::make('promoCode.code')
                ->label('Kode Promo')
                ->searchable(),
                TextColumn::make('sub_total_amount')
                ->label('Total Seluruh Harga')
                ->searchable(),
                IconColumn::make('is_paid')
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
                Action::make('invoice')
                    ->label('Invoice PDF')
                    ->action(function ($record) {
                    $pdf = Pdf::loadView('pdf.transaction-pdf', [
                    'trx' => $record->load('produk'),
                    ]);

                    return response()->streamDownload(
                        fn () => print($pdf->output()),
                        'invoice-'.$record->id.'.pdf'
                    );
                })
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
