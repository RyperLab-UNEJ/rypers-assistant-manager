<?php

namespace App\Filament\Admin\Resources\MataKuliahResource\Pages;

use Filament\Infolists\Infolist;
use Filament\Resources\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\Tabs;
use Filament\Tables\Contracts\HasTable;
use Filament\Actions\Contracts\HasActions;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Infolists\Components\Livewire;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Actions\Concerns\InteractsWithActions;
use App\Filament\Admin\Resources\MataKuliahResource;
use App\Livewire\Admin\Resources\MataKuliahResource\Pages\KelasDetailPage\Components\AsistenComponent;
use App\Livewire\Admin\Resources\MataKuliahResource\Pages\KelasDetailPage\Components\MahasiswaComponent;

class KelasDetailPage extends Page
{
    public $matkul;
    public $kelas;

    public function mount()
    {
        $this->matkul = request()->route('matkul');
        $this->kelas = request()->route('kelas');
    }

    protected static string $resource = MataKuliahResource::class;

    protected static string $view = 'filament.admin.resources.mata-kuliah-resource.pages.kelas-detail-page';

    public function getTitle(): string|Htmlable
    {
        return 'Detail Kelas';
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Tabs::make()
                    ->tabs([
                        Tabs\Tab::make('topik')->label('Topik')
                            ->schema([
                                // Livewire::make(MateriComponent::class, ['kelasId' => $this->kelasId])
                            ]),
                        Tabs\Tab::make('asisten')->label('Asisten')
                            ->schema([
                                Livewire::make(AsistenComponent::class, ['matkul' => $this->matkul, 'kelas' => $this->kelas])->lazy()
                            ]),
                        Tabs\Tab::make('mahasiswa')->label('Mahasiswa')
                            ->schema([
                                Livewire::make(MahasiswaComponent::class, ['matkul' => $this->matkul, 'kelas' => $this->kelas])->lazy()
                            ]),
                    ])->contained(false),
            ]);
    }
}
