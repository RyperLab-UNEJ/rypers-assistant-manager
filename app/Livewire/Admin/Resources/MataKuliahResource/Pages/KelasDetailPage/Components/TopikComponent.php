<?php

namespace App\Livewire\Admin\Resources\MataKuliahResource\Pages\KelasDetailPage\Components;

use Livewire\Component;
use Filament\Tables\Table;
use App\Models\MataKuliahKelas;
use Filament\Infolists\Infolist;
use Filament\Actions\CreateAction;
use App\Models\MataKuliahKelasTopik;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Actions\Contracts\HasActions;
use Filament\Infolists\Components\Section;
use LaraZeus\Accordion\Infolists\Accordion;
use Filament\Infolists\Components\TextEntry;
use LaraZeus\Accordion\Infolists\Accordions;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use App\Filament\Admin\Resources\MataKuliahResource\Pages\Topik\CreateTopikPage;
use App\Filament\Admin\Resources\MataKuliahResource\Pages\Topik\EditTopikPage;
use Filament\Infolists\Components\Actions\Action;
use Filament\Notifications\Notification;

class TopikComponent extends Component implements HasForms, HasActions, HasInfolists
{
    use InteractsWithForms, InteractsWithActions, InteractsWithInfolists;

    public $matkulKelas;

    // init 16 topik
    public $initTopik;

    public function mount($matkul, $kelas)
    {
        $this->matkulKelas = MataKuliahKelas::whereKelas($kelas)->whereHas('mataKuliah', function($query) use ($matkul)
        {
            $query->whereSlug($matkul);
        })->first();

        if (!MataKuliahKelasTopik::where('mata_kuliah_kelas_id', $this->matkulKelas->id)->exists()) {
            $topics = [
                [
                    'mata_kuliah_kelas_id' => $this->matkulKelas->id,
                    'judul_topik' => 'General',
                ],
            ];
            
            for ($i = 1; $i <= 16; $i++) {
                $topics[] = [
                    'mata_kuliah_kelas_id' => $this->matkulKelas->id,
                    'judul_topik' => 'Topik ' . $i,
                ];
            }
            
            MataKuliahKelasTopik::insert($topics);
        }
    }

    public function render()
    {
        return view('livewire.admin.resources.mata-kuliah-resource.pages.kelas-detail-page.components.topik-component');
    }

    public function infolist(Infolist $infolist): Infolist
    {
        $topikData = MataKuliahKelasTopik::with('mataKuliahKelasTopiks')->whereMataKuliahKelasId($this->matkulKelas->id)->get()->toArray();
        $formattedArray = ['data' => $topikData];
        // dd($formattedArray);
        return $infolist
            ->state($formattedArray)
            ->schema([
                RepeatableEntry::make('data')
                    ->contained(false)
                    ->hiddenLabel()
                    ->schema([
                        Section::make(fn($state) => $state['judul_topik'])
                            ->schema([
                                RepeatableEntry::make('mata_kuliah_kelas_topiks')
                                ->label('')
                                ->schema([
                                    TextEntry::make('judul_topik_detail')->label(''),
                                ])
                            ])
                            ->collapsed()
                            ->headerActions([
                                Action::make('editTopik')
                                    ->label('Edit')
                                    ->icon('heroicon-m-pencil-square')
                                    ->url(fn(Action $action) => EditTopikPage::getUrl(['matkul' => $this->matkulKelas->mataKuliah->slug, 'kelas' => $this->matkulKelas->kelas, 'topik' => $action->getComponent()->getState()['id']])),
                            ])
                    ])
            ]);
    }
}
