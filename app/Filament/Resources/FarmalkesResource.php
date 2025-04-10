<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FarmalkesResource\Pages;
use App\Filament\Resources\FarmalkesResource\RelationManagers;
use App\Models\Farmalkes;
use Dom\Text;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FarmalkesResource extends Resource
{
    protected static ?string $model = Farmalkes::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama')->required(),
                Select::make('tipe')->options([
                    'Obat' => 'Obat',
                    'Alkes' => 'Alkes',
                ])->required(),
                Select::make('kategori')->options([
                    'GENERIK' => 'GENERIK',
                    'BRAND' => 'BRAND',
                    '-' => '-',
                ])->nullable(),
                TextInput::make('satuan')->required(),
                TextInput::make('hna')->prefix('Rp. ')->numeric()->required(),
                TextInput::make('ppn')->prefix('%')->default(0)->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')->sortable()->searchable(),
                TextColumn::make('tipe')->sortable()->searchable(),
                TextColumn::make('kategori')->sortable()->searchable(),
                TextColumn::make('satuan')->sortable(),
                TextColumn::make('hna')->prefix('Rp. ')->sortable(),
                TextColumn::make('ppn')->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListFarmalkes::route('/'),
            'create' => Pages\CreateFarmalkes::route('/create'),
            'edit' => Pages\EditFarmalkes::route('/{record}/edit'),
        ];
    }
}
