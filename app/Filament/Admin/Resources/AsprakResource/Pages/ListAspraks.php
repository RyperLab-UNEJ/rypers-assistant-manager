<?php

namespace App\Filament\Admin\Resources\AsprakResource\Pages;

use App\Filament\Admin\Resources\AsprakResource;
use App\Imports\Admin\ImportAsistenPraktikum;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;

class ListAspraks extends ListRecords
{
    protected static string $resource = AsprakResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('importAsisten')
                ->label('Import Asisten Praktikum')
                ->color('success')
                ->modal('importAsisten')
                ->form([
                    FileUpload::make('file')
                        ->label('File Asisten Praktikum')
                        ->acceptedFileTypes(['application/vnd.ms-excel', 'text/plain', 'text/csv', 'text/tsv', 'text/comma-separated-values', 'text/tab-separated-values', 'application/csv', 'application/excel', 'application/vnd.ms-excel', 'application/vnd.msexcel', 'text/anytext', 'application/octet-stream', 'application/txt', 'text/plain', 'application/xls', 'application/xlsx'])
                        ->rules('required', 'mimes:csv')
                        ->directory('Admin/Import')
                ])
                ->action(function(array $data)
                {
                    // get file path
                    $csvPath = public_path('storage/' . $data['file']);

                    // import data
                    Excel::import(new ImportAsistenPraktikum(), $csvPath);
                    
                    // delete file
                    File::delete($csvPath);
                }),
            Actions\CreateAction::make('createAsisten'),
        ];
    }
}
