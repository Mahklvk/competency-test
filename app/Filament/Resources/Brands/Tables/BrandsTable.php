<?php

namespace App\Filament\Resources\Brands\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class BrandsTable
{
    public static function configure(Table $table): Table
    {
        return $table
        // ********************************************
        // **       Show Brand tables               **
        // *******************************************
            ->columns([
                TextColumn::make('name') // showing table brand name at tables
                    ->searchable() // function for searchable at search input
                    ->sortable(), // function for can be sortable at a top of table
                TextColumn::make('slug')
                    ->searchable(), // slug
                ImageColumn::make('logo'), // showing logo
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make()->color('success'), // Action for viewing object
                EditAction::make(), // action button for editing object
                DeleteAction::make()->color('danger'), // action button for deleting object
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
