<?php

namespace App\Filament\Resources\Brands\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BrandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // ********************************************
                // **       Create Brand Form               **
                // *******************************************
                TextInput::make('name')->required()->maxLength(225), // input for name
                FileUpload::make('logo')->image()->directory('Brands')->maxSize(1024)->required(), // file upload icon
            ]);
    }
}
