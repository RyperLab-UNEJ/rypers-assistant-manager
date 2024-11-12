<?php

namespace App\Filament\Admin\Resources\AsprakResource\Pages;

use App\Filament\Admin\Resources\AsprakResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAsprak extends CreateRecord
{
    protected static string $resource = AsprakResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
