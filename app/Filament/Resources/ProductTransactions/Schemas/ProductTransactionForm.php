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
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ProductTransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make('Informasi Umum') // fieldset style 
                    ->columnSpanFull()
                    ->columns([
                        'default' => 1, // for responsivity
                        'md' => 1,
                    ])
                    ->schema([
                        TextInput::make('name') // form name
                            ->label('Nama Pelanggan')
                            ->required(),

                        TextInput::make('phone') // form phone
                            ->label('Nomor Handphone Pelanggan')
                            ->required()
                            ->numeric(),

                        TextInput::make('email') // form email
                            ->label('Email Pelanggan')
                            ->required()
                            ->email(),

                        TextInput::make('booking_trx_id') // form booking trx id disabled and dehydrated
                            ->label('ID Booking TRX')
                            ->disabled()
                            ->dehydrated()
                            ->default(fn () => (ProductTransaction::generateUniqueTrxId())), // get generate function at models

                        Fieldset::make('Informasi Produk')
                            ->columnSpanFull()
                            ->columns([
                                'default' => 2,
                                'md' => 1,
                            ])
                            ->schema([
                                Select::make('produk_id') // form produk
                                    ->relationship('Produk', 'name') // get relation at models produk
                                    ->label('Nama Produk')
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) { // value di dalamnya akan dijalankan setiap kali berubah
                                        $product = Produk::find($state); //nilai terbaru dari field itu, jadi ambil data sesuai dengan ID

                                        $price = $product?->price ?? 0; // null safe operator, jadi tidak error, kalo price tidak ada otomatis nilai 0

                                        $set('price', $price); // setting form price secara  otomatis dengan data
                                        $set('quantity', 1); // setting form quantity otomatis 1
                                        $set('sub_total_amount', $price); 
                                        $set('grand_total_amount', $price);
                                    }),

                                TextInput::make('price') // form price
                                    ->prefix('IDR')
                                    ->label('Harga Satu Produk')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated(),

                                
                                TextInput::make('quantity') // form quantity
                                    ->required()
                                    ->label('Jumlah Barang')
                                    ->numeric()
                                    ->default(1)
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $get, callable $set) { // otomatis dijalankan kketika value diubah
                                        $productId = $get('produk_id'); // get produk_id

                                        if (! $productId) {
                                            return;
                                        } // function ketika tidak ada produk hentikan biar tidak error

                                        $produk = Produk::find($productId); // mencari produk berdasarkan id

                                        if (! $produk) {
                                            return;
                                        } // kalau tidak ketemu stop.

                                        if ($state > $produk->stock) {
                                            $set('quantity', $produk->stock);

                                            Notification::make()
                                                ->title('Stok tidak cukup')
                                                ->body("Stok tersedia hanya {$produk->stock}")
                                                ->danger()
                                                ->send();
                                        } // valodasi stock

                                        $price = $get('price') ?? 0; // get price jika tidak ada price otomatis 0 agar tidak error
                                        $subtotal = $price * $get('quantity'); // price dikali quantity untuk sub total nya

                                        $set('sub_total_amount', $subtotal); //  isi ototmatis form sub_total_amount
                                        $set('grand_total_amount', max($subtotal - ($get('promo_code_id') ?? 0), 0)); // sub total - diskon promo code
                                    }),

                                    Select::make('produk_size')
                                    ->label('Size')
                                    ->options(fn (Get $get) => ProdukSize::where('produk_id', $get('produk_id'))
                                        ->pluck('size', 'id')
                                    ) // Isi dropdown diambil dari database, tergantung produk yang dipilih.
                                    ->searchable()
                                    ->reactive()
                                    ->dehydrated()
                                    ->disabled(fn (Get $get) => ! $get('produk_id')), // kalau belum produk dipilih form size bakal disabled

                                TextInput::make('grand_total_amount') // show grand total amount
                                    ->prefix('IDR')
                                    ->label('Total Sebelum Diskon')
                                    ->dehydrated()
                                    ->disabled(),

                                Select::make('promo_code_id')
                                    ->label('Kode Promo')
                                    ->relationship('PromoCode', 'code')
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $get, callable $set) { // $state nilai promo yang dipilih $get ambil nilai field lain $set set field lain
                                        if (! $state) { // User menghapus / belum pilih promo.
                                            $set('discount_amount', 0); // set diskon atau 0 biar ga error
                                            $subtotal = $get('sub_total_amount') ?? 0;  // ambil sub total
                                            $set('grand_total_amount', $subtotal); // set grand total

                                            return;
                                        }

                                        $promoCode = PromoCode::find($state); // ngambil id state promo code

                                        if (! $promoCode) { // kalau id promo tidak ketemu
                                            $set('discount_amount', 0);
                                            $subtotal = $get('sub_total_amount') ?? 0;
                                            $set('grand_total_amount', $subtotal);

                                            return;
                                        }

                                        $discountAmount = $promoCode->discount_amount;
                                        $subtotal = $get('grand_total_amount') ?? 0;

                                        $set('discount_amount', $discountAmount); // ambil nominal diskon dari tabel promo
                                        $set('sub_total_amount', max($subtotal - $discountAmount, 0)); // show sub total - diskon 
                                    }),

                                TextInput::make('discount_amount') //  form diskon amount
                                    ->prefix('IDR')
                                    ->label('Total diskon')
                                    ->columnSpanFull()
                                    ->disabled(),

                                TextInput::make('sub_total_amount') // form sub total amount
                                    ->prefix('IDR')
                                    ->label('Total harga')
                                    ->columnSpanFull()
                                    ->dehydrated()
                                    ->disabled(),

                                Toggle::make('is_paid') // form is paid toggle
                                    ->label('Sudah terbayar?')
                                    ->onIcon(Heroicon::Banknotes)
                                    ->onColor('success')
                                    ->inline(false),

                            ]),

                        Fieldset::make('Alamat Penerima') // fieldset style
                            ->columnSpanFull()
                            ->columns([
                                'default' => 1,
                                'sm' => 1,
                                'md' => 1,
                            ])
                            ->schema([
                                TextInput::make('city')  // form city
                                    ->label('Kota')
                                    ->required(),

                                TextInput::make('post_code') // form kode pos
                                    ->label('Kode Pos')
                                    ->required()
                                    ->numeric(),

                                Textarea::make('address') // form address lengkap
                                    ->label('Alamat lengkap')
                                    ->required()
                                    ->columnSpanFull(),
                            ]),
                            FileUpload::make('proof') // image upload
                            ->label('Bukti Pembayaran')
                            ->image()
                            ->columnSpanFull()
                            ->directory('buktiPembayaran'),
                    ]),
            ]);
    }
}
