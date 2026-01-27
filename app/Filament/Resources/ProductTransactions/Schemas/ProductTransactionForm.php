<?php

namespace App\Filament\Resources\ProductTransactions\Schemas;

use App\Models\ProductTransaction;
use App\Models\Produk;
use App\Models\ProdukSize;
use App\Models\PromoCode;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\Layout\Grid;

class ProductTransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Step::make('Product And Price')

                        ->schema([
                            Select::make('produk_id')
                                ->relationship('Produk', 'name')
                                ->label('Nama Produk')
                                ->required()
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set) {
                                    $product = Produk::find($state);

                                    $price = $product?->price ?? 0;

                                    $set('price', $price);
                                    $set('quantity', 1);
                                    $set('sub_total_amount', $price);
                                    $set('grand_total_amount', $price);
                                }),

                            TextInput::make('price')
                                ->prefix('IDR')
                                ->label('Harga Satu Produk')
                                ->numeric()
                                ->disabled()
                                ->dehydrated(),

                            TextInput::make('quantity')
                                ->required()
                                ->label('Jumlah Barang')
                                ->numeric()
                                ->default(1)
                                ->live()
                                ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                    $productId = $get('produk_id');

                                    if (! $productId) {
                                        return;
                                    }

                                    $produk = Produk::find($productId);

                                    if (! $produk) {
                                        return;
                                    }

                                    if ($state > $produk->stock) {
                                        $set('quantity', $produk->stock);

                                        Notification::make()
                                            ->title('Stok tidak cukup')
                                            ->body("Stok tersedia hanya {$produk->stock}")
                                            ->danger()
                                            ->send();
                                    }

                                    $price = $get('price') ?? 0;
                                    $subtotal = $price * $get('quantity');

                                    $set('sub_total_amount', $subtotal);
                                    $set('grand_total_amount', max($subtotal - ($get('promo_code_id') ?? 0), 0));
                                }),

                            Select::make('produk_size')
                                ->label('Size')
                                ->required()
                                ->options(fn (Get $get) => ProdukSize::where('produk_id', $get('produk_id'))
                                    ->pluck('size', 'id')
                                )
                                ->searchable()
                                ->reactive()
                                ->dehydrated()
                                ->disabled(fn (Get $get) => ! $get('produk_id')),

                            TextInput::make('grand_total_amount')
                                ->prefix('IDR')
                                ->label('Total Sebelum Diskon')
                                ->dehydrated()
                                ->disabled(),

                            Select::make('promo_code_id')
                                ->label('Kode Promo')
                                ->relationship('PromoCode', 'code')
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                    if (! $state) {
                                        $set('discount_amount', 0);
                                        $subtotal = $get('sub_total_amount') ?? 0;
                                        $set('grand_total_amount', $subtotal);

                                        return;
                                    }

                                    $promoCode = PromoCode::find($state);

                                    if (! $promoCode) {
                                        $set('discount_amount', 0);
                                        $subtotal = $get('sub_total_amount') ?? 0;
                                        $set('grand_total_amount', $subtotal);

                                        return;
                                    }

                                    $discountAmount = $promoCode->discount_amount;
                                    $subtotal = $get('grand_total_amount') ?? 0;

                                    $set('discount_amount', $discountAmount);
                                    $set('sub_total_amount', max($subtotal - $discountAmount, 0));
                                }),
                            TextInput::make('discount_amount')
                                ->prefix('IDR')
                                ->label('Total diskon')
                                ->disabled(),

                            TextInput::make('sub_total_amount')
                                ->prefix('IDR')
                                ->label('Total harga')
                                ->dehydrated()
                                ->disabled(),

                        ]),

                    Step::make('Costumer Information')
                        ->schema([
                            TextInput::make('name')
                                ->label('Nama Pelanggan')
                                ->required(),
                            TextInput::make('phone')
                                ->label('Nomor Handphone Pelanggan')
                                ->required()
                                ->numeric(),
                            TextInput::make('email')
                                ->label('Email Pelanggan')
                                ->required()
                                ->email(),
                            TextInput::make('city')
                                ->label('Kota')
                                ->required(),
                            TextInput::make('post_code')
                                ->label('Kode Pos')
                                ->required()
                                ->numeric()
                                ->columnSpanFull(),
                            Textarea::make('address')
                                ->label('Alamat lengkap')
                                ->required()
                                ->columnSpanFull(),

                        ]),

                    Step::make('Payment')
                        ->schema([
                            Toggle::make('is_paid')
                                ->label('Sudah terbayar?')
                                ->onIcon(Heroicon::Banknotes)
                                ->onColor('success')
                                ->inline(false),
                            TextInput::make('booking_trx_id')
                                ->label('ID Booking TRX')
                                ->disabled()
                                ->dehydrated()
                                ->default(fn () => (ProductTransaction::generateUniqueTrxId())),
                            FileUpload::make('proof')
                                ->label('Bukti Pembayaran')
                                ->image()
                                ->columnSpanFull()
                                ->directory('buktiPembayaran'),
                        ]),
                    ])
                    ->columns(2)
                ->columnSpanFull(),
            ]);
    }
}
