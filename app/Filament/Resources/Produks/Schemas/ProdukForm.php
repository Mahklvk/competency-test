<?php

namespace App\Filament\Resources\Produks\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Filters\SelectFilter;

class ProdukForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make('Informasi Produk')->columnSpanFull()
        ->columns([
            'default' => 2,
            'md' => 1
        ])    
        ->schema([
            TextInput::make('name')->required()->maxLength(225),
            TextInput::make('price')->prefix('Rp')->required()->numeric(),
            FileUpload::make('thumbnail')->directory('produk-thumbnail')->image()->imageEditor()->previewAble(true)->required(),
            Repeater::make('Photos')->required()
            ->schema([
                FileUpload::make('photo')->directory('produk-photos')->image()->imageEditor()->previewAble(true)->columnSpanFull(),
                
            ]),

            Repeater::make('sizes')->required()
            ->schema([
                TextInput::make('size')->required()->maxLength(225),
            ]),

            Fieldset::make('informasi tambahan')->columnSpanFull()
            ->columns([
                'default' => 2,
                'md' => 1
            ])
            ->schema([
                Textarea::make('about')->required(),
                Select::make('is_popular')->required()->options([
                    true => 'true',
                    false => 'false'
                ]),
                Select::make('category_id')->required()
                ->relationship('category','name'),
                Select::make('brand_id')->required()
                ->relationship('brand','name'),
                TextInput::make('stock')->required()->prefix('Pcs'),
            ])
        ]),
            ]);
    }
}
