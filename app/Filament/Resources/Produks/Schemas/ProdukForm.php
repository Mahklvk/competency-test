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
                Fieldset::make('Informasi Produk') // fieldset style
                    ->columnSpanFull()
                    ->columns([
                        'default' => 1,
                        'md' => 2,
                    ])
                    ->schema([
                        TextInput::make('name') // form name
                            ->required()
                            ->maxLength(225),

                        TextInput::make('price') // form price
                            ->prefix('IDR')
                            ->required()
                            ->numeric(),

                        FileUpload::make('thumbnail') // upload for thumbnail
                            ->directory('produk-thumbnail')
                            ->image()
                            ->imageEditor()
                            ->previewAble(true)
                            ->required(),

                        Repeater::make('Photos') // repeater itunya biar bisa upload foto banyak
                            ->required()
                            ->relationship('photos')
                            ->schema([
                                FileUpload::make('photo')  // upload poto
                                    ->directory('produk-photos')
                                    ->image()
                                    ->imageEditor()
                                    ->previewAble(true)
                                    ->columnSpanFull(),
                            ]),

                        Repeater::make('sizes') // repeater size lagi
                            ->required()
                            ->relationship('sizes')
                            ->schema([
                                TextInput::make('size') // input form size
                                    ->required()
                                    ->maxLength(225),
                            ]),

                        Fieldset::make('informasi tambahan')
                            ->columnSpanFull()
                            ->columns([
                                'default' => 1,
                                'md' => 2,
                            ])
                            ->schema([
                                Textarea::make('about')->required(), // tentang produk itu
                                Toggle::make('is_popular') // toggle popular ga nih
                                    ->onIcon(HeroIcon::Fire)
                                    ->onColor('danger')
                                    ->required()
                                    ->inline(false)
                                    ->label('Apakah Popular?'),

                                Select::make('category_id') // category name
                                    ->required()
                                    ->relationship('category', 'name'),

                                Select::make('brand_id') // brand name
                                    ->required()
                                    ->relationship('brand', 'name'),

                                TextInput::make('stock') // stock form
                                    ->required()
                                    ->prefix('PCS')
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ]);
    }
}
