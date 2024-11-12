<?php

namespace App\Filament\Admin\Resources\AsprakResource\Pages;

use App\Filament\Admin\Resources\AsprakResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAsprak extends EditRecord
{
    protected static string $resource = AsprakResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
