<?php

namespace App\Filament\Resources\Produks\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ProdukForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make('Informasi Produk')
                    ->columnSpanFull()
                    ->columns([
                        'default' => 1,
                        'md' => 2,
                    ])
                    ->schema([
                        TextInput::make('name')->required()->maxLength(225),
                        TextInput::make('price')->prefix('IDR')->required()->numeric(),
                        FileUpload::make('thumbnail')->directory('produk-thumbnail')->image()->imageEditor()->previewAble(true)->required(),
                        Repeater::make('Photos')->required()
                            ->relationship('photos')
                            ->schema([
                                FileUpload::make('photo')->directory('produk-photos')->image()->imageEditor()->previewAble(true)->columnSpanFull(),

                            ]),

                        Repeater::make('sizes')->required()
                            ->relationship('sizes')
                            ->schema([
                                TextInput::make('size')->required()->maxLength(225),
                            ]),

                        Fieldset::make('informasi tambahan')->columnSpanFull()
                            ->columns([
                                'default' => 1,
                                'md' => 2,
                            ])
                            ->schema([
                                Textarea::make('about')->required(),
                                Toggle::make('is_popular')
                                    ->onIcon(HeroIcon::Fire)
                                    ->onColor('danger')
                                    ->required()
                                    ->inline(false)
                                    ->label('Apakah Popular?'),
                                Select::make('category_id')->required()
                                    ->relationship('category', 'name'),
                                Select::make('brand_id')->required()
                                    ->relationship('brand', 'name'),
                                TextInput::make('stock')->required()->prefix('Pcs')->columnSpanFull(),
                            ]),
                    ]),
            ]);
    }
}
