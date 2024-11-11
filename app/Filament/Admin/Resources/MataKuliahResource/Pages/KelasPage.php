<?php

namespace App\Filament\Admin\Resources\MataKuliahResource\Pages;

use App\Models\MataKuliah;
use Filament\Tables\Table;
use App\Models\MataKuliahKelas;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\Page;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\DeleteAction;
use Filament\Actions\Contracts\HasActions;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Actions\Concerns\InteractsWithActions;
use App\Filament\Admin\Resources\MataKuliahResource;
use Filament\Forms\Components\Hidden;

class KelasPage extends Page implements HasTable, HasForms, HasActions
{
    use InteractsWithTable, InteractsWithForms, InteractsWithActions;

    public $matkulId;

    public function mount(): void
    {
        $this->matkulId = MataKuliah::whereSlug(request()->route('matkul'))->first()->id;
    }

    protected static string $resource = MataKuliahResource::class;

    protected static string $view = 'filament.admin.resources.mata-kuliah-resource.pages.kelas-page';

    public function table(Table $table): Table
    {
        return $table
            ->query(MataKuliahKelas::query()->whereMataKuliahId($this->matkulId))
            ->columns([
                TextColumn::make('kelas')
                    ->label('Kelas')
                    ->searchable()
                    ->sortable(),
            ])
            ->actions([
                EditAction::make()
                    ->form([
                        Section::make([
                            Hidden::make('mata_kuliah_id')
                                ->default($this->matkulId),
                            TextInput::make('kelas')
                                ->label('Kelas')
                                ->required(),
                        ]),
                    ]),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ])
            ]);
    }

    public function createKelasAction(): CreateAction
    {
        return CreateAction::make('createKelas')
            ->model(MataKuliahKelas::class)
            ->form([
                Section::make([
                    Hidden::make('mata_kuliah_id')
                        ->default($this->matkulId),
                    TextInput::make('kelas')
                        ->label('Kelas')
                        ->required(),
                ]),
            ])
            ->createAnother(false);
    }
}
