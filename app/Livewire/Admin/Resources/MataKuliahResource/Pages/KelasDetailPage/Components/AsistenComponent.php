<?php

namespace App\Livewire\Admin\Resources\MataKuliahResource\Pages\KelasDetailPage\Components;

use App\Models\MataKuliahKelas;
use Livewire\Component;
use Filament\Tables\Table;
use App\Models\MataKuliahKelasAsprak;
use App\Models\User;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Actions\DeleteAction;
use Filament\Actions\Contracts\HasActions;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;

class AsistenComponent extends Component implements HasTable, HasForms, HasActions
{
    use InteractsWithTable, InteractsWithForms, InteractsWithActions;

    public $kelas;

    public function mount($kelas)
    {
        $this->kelas = MataKuliahKelas::where('kelas', $kelas)->first();
    }

    public function render()
    {
        return view('livewire.admin.resources.mata-kuliah-resource.pages.kelas-detail-page.components.asisten-component');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(MataKuliahKelasAsprak::query()->with('asprak')->whereHas('mataKuliahKelas', function($query)
            {
                $query->where('kelas', $this->kelas->kelas);
            }))
            ->columns([
                TextColumn::make('asprak.name')
                    ->label('Nama Asisten')
                    ->searchable(),
                TextColumn::make('asprak.email')
                    ->label('Email Asisten')
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

    public function createAsistenAction(): CreateAction
    {
        return CreateAction::make('createAsisten')
            ->label('Atur Asisten')
            ->model(MataKuliahKelasAsprak::class)
            ->form([
                Section::make([
                    Hidden::make('mata_kuliah_kelas_id')
                        ->default($this->kelas->id),
                    Select::make('asprak_id')
                        ->label('Asisten')
                        ->options(User::whereHas('roles', function($query)
                        {
                            $query->where('name', 'asprak');
                        })->get()->pluck('name', 'id'))
                        ->searchable()
                        ->multiple()
                        ->preload()
                        ->required(),
                ])
            ])
            ->using(function(array $data)
            {
                foreach($data['asprak_id'] as $asprak)
                {
                    MataKuliahKelasAsprak::firstOrCreate([
                        'mata_kuliah_kelas_id' => $data['mata_kuliah_kelas_id'],
                        'asprak_id' => $asprak,
                    ]);
                }
            })
            ->createAnother(false);
    }
}
