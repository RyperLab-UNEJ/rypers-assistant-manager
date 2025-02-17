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
use Filament\Tables\Actions\Action;

class KelasPage extends Page implements HasTable, HasForms, HasActions
{
    use InteractsWithTable, InteractsWithForms, InteractsWithActions;

    public $matkul;

    public function mount(): void
    {
        $this->matkul = MataKuliah::whereSlug(request()->route('matkul'))->first();
    }

    protected static string $resource = MataKuliahResource::class;

    protected static string $view = 'filament.admin.resources.mata-kuliah-resource.pages.kelas-page';

    public function table(Table $table): Table
    {
        return $table
            ->query(MataKuliahKelas::query()->whereMataKuliahId($this->matkul->id))
            ->columns([
                TextColumn::make('kelas')
                    ->label('Kelas')
                    ->searchable()
                    ->sortable(),
            ])
            ->actions([
                Action::make('kelas-detail')
                    ->label('Atur Detail')
                    ->icon('heroicon-o-academic-cap')
                    ->color('info')
                    ->url(fn(MataKuliahKelas $kelas) => KelasDetailPage::getUrl(['matkul' => $this->matkul->slug, 'kelas' => $kelas])),
                EditAction::make()
                    ->form([
                        Section::make([
                            Hidden::make('mata_kuliah_id')
                                ->default($this->matkul->id),
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
                        ->default($this->matkul->id),
                    TextInput::make('kelas')
                        ->label('Kelas')
                        ->required(),
                ]),
            ])
            ->createAnother(false);
    }
}
