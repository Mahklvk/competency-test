<?php

namespace App\Filament\Resources\PromoCodes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PromoCodeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->required()
                    ->maxLength(225),
                TextInput::make('discount_amount')
                    ->required()
                    ->prefix('IDR')
            ]);
    }
}
