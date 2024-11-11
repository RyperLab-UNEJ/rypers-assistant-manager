<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\AsprakResource\Pages;
use App\Filament\Admin\Resources\AsprakResource\RelationManagers;

class AsprakResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Asisten Praktikum';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    TextInput::make('name')
                        ->label('Nama')
                        ->unique(ignoreRecord: true)
                        ->required()
                        ->autofocus(),
                    TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->unique(ignoreRecord: true)
                        ->required(),
                    TextInput::make('password')
                        ->label('Password')
                        ->revealable()
                        ->password()
                        ->maxLength(255)
                        ->dehydrateStateUsing(fn(string $state): string => bcrypt($state))
                        ->dehydrated(fn(?string $state): bool => filled($state))
                        ->required(fn(string $operation): bool => $operation === 'create'),
                    Select::make('roles')
                        ->relationship('roles', 'name')
                        ->label('Peran')
                        ->multiple()
                        ->preload()
                        ->searchable(),
                ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(User::query()->with('roles')->whereHas('roles', function (Builder $query) {
                $query->where('name', 'asprak');
            }))
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Asisten Praktikum')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email Asisten Praktikum')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAspraks::route('/'),
            'create' => Pages\CreateAsprak::route('/create'),
            'edit' => Pages\EditAsprak::route('/{record}/edit'),
        ];
    }

    public static function getLabel(): ?string
    {
        return 'Asisten Praktikum';
    }
}
