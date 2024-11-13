<?php

namespace App\Filament\Admin\Resources\MataKuliahResource\Pages\Topik;

use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Actions\Action;
use App\Models\MataKuliahKelas;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\DB;
use App\Models\MataKuliahKelasTopik;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\RichEditor;
use App\Models\MataKuliahKelasTopikDetail;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use App\Filament\Admin\Resources\MataKuliahResource;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use App\Filament\Admin\Resources\MataKuliahResource\Pages\KelasDetailPage;

class EditTopikPage extends Page implements HasForms
{
    use InteractsWithForms;

    public $matkulKelas;
    public array $data;
    public $topikData;

    public function mount()
    {
        $this->matkulKelas = MataKuliahKelas::whereKelas(request()->route('kelas'))->whereHas('mataKuliah', function($query)
        {
            $query->whereSlug(request()->route('matkul'));
        })->first();
        // get topik data
        $this->topikData = MataKuliahKelasTopik::with('mataKuliahKelasTopiks')->find(request()->route('topik'));
        $this->form->fill([
            'judul_topik' => $this->topikData->judul_topik,
            'topik' => $this->topikData->mataKuliahKelasTopiks->map(function($item) {
                return [
                    'judul_topik_detail' => $item->judul_topik_detail,
                    'jenis' => $item->jenis,
                    'deskripsi' => $item->deskripsi,
                    'file' => $item->getFirstMediaUrl('topik-attachment'),
                    'waktu_mulai' => $item->waktu_mulai,
                    'waktu_selesai' => $item->waktu_selesai,
                ];
            })->toArray(),
        ]);
    }
    
    protected static string $resource = MataKuliahResource::class;

    protected static string $view = 'filament.admin.resources.mata-kuliah-resource.pages.topik.edit-topik-page';

    public function form(Form $form): Form
    {
        return $form
            ->model($this->topikData)
            ->schema([
                Section::make([
                    Hidden::make('mata_kuliah_kelas_id')
                        ->default($this->matkulKelas->id)
                        ->rules(['exists:mata_kuliah_kelas,id']),
                    TextInput::make('judul_topik')
                        ->label('Judul Topik')
                        ->required()
                        ->rules(['string', 'max:255', 'required']),
                    Repeater::make('topik')
                        ->required()
                        ->label('Topik')
                        ->rules(['array', 'required'])
                        ->relationship('mataKuliahKelasTopiks')
                        ->schema([
                            TextInput::make('judul_topik_detail')
                                ->label('Judul Detail Topik')
                                ->required()
                                ->rules(['string', 'max:255', 'required']),
                            Select::make('jenis')
                                ->label('Jenis')
                                ->options([
                                    'forum' => 'Forum',
                                    'file' => 'File',
                                    'url' => 'URL',
                                    'quiz' => 'Quiz',
                                    'assignment' => 'Assignment',
                                ])
                                ->live()
                                ->required()
                                ->rules(['required', 'in:forum,file,url,quiz,assignment']),
                            RichEditor::make('deskripsi')
                                ->label('Deskripsi')
                                ->nullable(),
                            SpatieMediaLibraryFileUpload::make('file')
                                ->label('File Topik')
                                ->collection('topik-attachment')
                                ->required(fn(Get $get) => $get('jenis') === 'file')
                                ->hidden(fn(Get $get) => $get('jenis') !== 'file')
                                ->rules(['required_if:jenis,file']),
                            DateTimePicker::make('waktu_mulai')
                                ->label('Waktu Mulai')
                                ->required(fn(Get $get) => $get('jenis') === 'quiz' || $get('jenis') === 'assignment')
                                ->hidden(fn(Get $get) => $get('jenis') !== 'quiz' && $get('jenis') !== 'assignment')
                                ->rules(['required_if:jenis,quiz,assignment']),
                            DateTimePicker::make('waktu_selesai')
                                ->label('Waktu Selesai')
                                ->required(fn(Get $get) => $get('jenis') === 'quiz' || $get('jenis') === 'assignment')
                                ->hidden(fn(Get $get) => $get('jenis') !== 'quiz' && $get('jenis') !== 'assignment')
                                ->rules(['required_if:jenis,quiz,assignment']),
                        ])
                        ->defaultItems(1)
                        ->minItems(1)
                ])
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
            ->label(__('Edit Topik'))
                ->submit('save'),
        ];
    }

    public function save()
    {
        DB::beginTransaction();
        try {
            //code...
            $matkulKuliahKelasTopik = $this->topikData;

            $matkulKuliahKelasTopik->update([
                'judul_topik' => $this->data['judul_topik'],
            ]);

            $topik = [];
            foreach($this->data['topik'] as $key => $value)
            {
                $topik[] = [
                    'mata_kuliah_kelas_topik_id' => $matkulKuliahKelasTopik->id,
                    'judul_topik_detail' => $value['judul_topik_detail'],
                    'jenis' => $value['jenis'],
                    'deskripsi' => $value['deskripsi'] ?? null,
                    'waktu_mulai' => $value['waktu_mulai'] ?? null,
                    'waktu_selesai' => $value['waktu_selesai'] ?? null,
                ];
            }

            $matkulKuliahKelasTopik->mataKuliahKelasTopiks()->delete();

            MataKuliahKelasTopikDetail::insert($topik);

            DB::commit();

            Notification::make()
                ->success()
                ->title('Topik berhasil diubah')
                ->send();

            $this->redirect(KelasDetailPage::getUrl(['matkul' => $this->matkulKelas->mataKuliah->slug, 'kelas' => $this->matkulKelas->kelas]));
            
        } catch (\Exception $e) {
            DB::rollBack();
            Notification::make()
                ->title('Gagal membuat topik karena ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }
}
