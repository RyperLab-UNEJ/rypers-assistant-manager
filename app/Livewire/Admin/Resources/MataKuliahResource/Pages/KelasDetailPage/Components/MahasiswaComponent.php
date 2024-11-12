<?php

namespace App\Livewire\Admin\Resources\MataKuliahResource\Pages\KelasDetailPage\Components;

use App\Imports\Admin\ImportMahasiswa;
use Livewire\Component;
use Filament\Tables\Table;
use App\Models\MataKuliahKelas;
use Filament\Actions\CreateAction;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use App\Models\MataKuliahKelasMahasiswa;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Actions\DeleteAction;
use Filament\Actions\Contracts\HasActions;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Actions\Concerns\InteractsWithActions;

class MahasiswaComponent extends Component implements HasTable, HasForms, HasActions
{
    use InteractsWithTable, InteractsWithForms, InteractsWithActions;

    public $matkulKelas;

    public function mount($matkul, $kelas)
    {
        $this->matkulKelas = MataKuliahKelas::whereKelas($kelas)->whereHas('mataKuliah', function($query) use ($matkul)
        {
            $query->whereSlug($matkul);
        })->first();
    }

    public function render()
    {
        return view('livewire.admin.resources.mata-kuliah-resource.pages.kelas-detail-page.components.mahasiswa-component');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(MataKuliahKelasMahasiswa::query()->with('mahasiswa')->whereMataKuliahKelasId($this->matkulKelas->id))
            ->columns([
                TextColumn::make('mahasiswa.name')
                    ->label('Nama Mahasiswa')
                    ->searchable(),
                TextColumn::make('mahasiswa.email')
                    ->label('Email Mahasiswa')
                    ->searchable(),
            ])
            ->actions([
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public function createMahasiswaAction(): CreateAction
    {
        return CreateAction::make('createMahasiswa')
            ->label('Atur Mahasiswa')
            ->color('success')
            ->form([
                FileUpload::make('file')
                    ->label('File Mahasiswa')
                    ->acceptedFileTypes(['application/vnd.ms-excel', 'text/plain', 'text/csv', 'text/tsv', 'text/comma-separated-values', 'text/tab-separated-values', 'application/csv', 'application/excel', 'application/vnd.ms-excel', 'application/vnd.msexcel', 'text/anytext', 'application/octet-stream', 'application/txt', 'text/plain', 'application/xls', 'application/xlsx'])
                    ->rules('required', 'mimes:csv')
                    ->directory('Admin/Import')
            ])
            ->action(function(array $data)
            {
                // get file path
                $csvPath = public_path('storage/' . $data['file']);

                // import data
                Excel::import(new ImportMahasiswa($this->matkulKelas->id), $csvPath);
                
                // delete file
                File::delete($csvPath);
            })
            ->createAnother(false);
    }
}
