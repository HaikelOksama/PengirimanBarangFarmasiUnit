<?php

namespace App\Filament\Resources\FarmalkesResource\Pages;

use App\Filament\Resources\FarmalkesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFarmalkes extends EditRecord
{
    protected static string $resource = FarmalkesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
