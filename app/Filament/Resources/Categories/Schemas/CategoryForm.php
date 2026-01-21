<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
//***********************************************
// **       Create category Form               **
// **********************************************
            ->components([
                TextInput::make('name') // input name for categories
                ->required() // required input
                ->maxLength(225), // max length is 225
                FileUpload::make('icon') // fileupload for icon
                ->image() // image
                ->directory('categories') // directory files
                ->maxSize(1024) // max size is 1MB 
                ->required(), // required
            ]);
    }
}
