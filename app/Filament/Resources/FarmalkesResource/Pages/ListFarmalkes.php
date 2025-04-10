<?php

namespace App\Filament\Resources\FarmalkesResource\Pages;

use App\Filament\Resources\FarmalkesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFarmalkes extends ListRecords
{
    protected static string $resource = FarmalkesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
