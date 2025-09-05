<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactoResource\Pages;
use App\Filament\Resources\ContactoResource\RelationManagers;
use App\Models\Contacto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Http;
use Filament\Notifications\Notification;
use RalphJSmit\Filament\RecordFinder\Forms\Components\RecordFinder;


class ContactoResource extends Resource
{
    protected static ?string $model = Contacto::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    protected static ?string $navigationLabel = 'Contactos';
    
    protected static ?string $modelLabel = 'Contacto';
    
    protected static ?string $pluralModelLabel = 'Contactos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información Personal')
                    ->schema([
                        Forms\Components\TextInput::make('nombre')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('apellido')
                            ->label('Apellido')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('telefono')
                            ->label('Teléfono')
                            ->tel()
                            ->maxLength(255),
                    ])->columns(2),
                
                Forms\Components\Section::make('Información Adicional')
                    ->schema([
                        Forms\Components\TextInput::make('empresa')
                            ->label('Empresa')
                            ->maxLength(255),
                        Forms\Components\Textarea::make('direccion')
                            ->label('Dirección')
                            ->rows(3)
                            ->maxLength(65535),
                        Forms\Components\Textarea::make('notas')
                            ->label('Notas')
                            ->rows(3)
                            ->maxLength(65535),
                    ])->columns(1),

                Forms\Components\Section::make('Referencias')
                    ->schema([
                        RecordFinder::make('contacto_referencia_id')
                            ->label('Contacto de Referencia')
                            ->query(\App\Models\Contacto::query())
                            ->tableColumns([
                                Tables\Columns\TextColumn::make('nombre')
                                    ->label('Nombre')
                                    ->searchable(),
                                Tables\Columns\TextColumn::make('apellido')
                                    ->label('Apellido')
                                    ->searchable(),
                                Tables\Columns\TextColumn::make('email')
                                    ->label('Email')
                                    ->searchable(),
                                Tables\Columns\TextColumn::make('empresa')
                                    ->label('Empresa')
                                    ->searchable(),
                            ])
                            ->tableFilters([
                                Tables\Filters\SelectFilter::make('empresa')
                                    ->label('Filtrar por Empresa')
                                    ->options(function () {
                                        return \App\Models\Contacto::distinct()
                                            ->pluck('empresa', 'empresa')
                                            ->filter()
                                            ->toArray();
                                    }),
                            ])
                            ->openModalActionLabel('Seleccionar Contacto')
                            ->modalHeading('Seleccionar Contacto de Referencia')
                            ->modalDescription('Busca y selecciona un contacto que sirva como referencia para este contacto.')
                            ->placeholder('Ningún contacto seleccionado')
                            ->badgeColor('success'),
                    ])->columns(1),

                Forms\Components\Placeholder::make('algo')
                    ->label('Nuevo')
                    ->content('Prueba 4'),

                Forms\Components\Repeater::make('items')
                    ->schema([
                        Forms\Components\TextInput::make('item')
                            ->label('Nombre')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('cantidad')
                            ->label('Cantidad')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('precio')
                            ->label('Precio')
                            ->maxLength(255),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->searchDebounce('10ms')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('apellido')
                    ->label('Apellido')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('telefono')
                    ->label('Teléfono')
                    ->searchable(),
                Tables\Columns\TextColumn::make('empresa')
                    ->label('Empresa')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de Creación')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

         
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('empresa')
                    ->label('Filtrar por Empresa')
                    ->options(function () {
                        return \App\Models\Contacto::distinct()
                            ->pluck('empresa', 'empresa')
                            ->filter()
                            ->toArray();
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('ver_contacto')
                    ->label('Ver Contacto')
                    ->action(function () {
                        try {
                            $response = Http::get('https://v4.cargopanel.app/api/admin/contactos/2');

                            if ($response->successful()) {
                                $data = $response->json('data');

                                if ($data) {
                                    Notification::make()
                                        ->title('Contacto encontrado')
                                        ->body("Nombre: {$data['nombre']} {$data['apellido']}")
                                        ->success()
                                        ->send();
                                } else {
                                    Notification::make()
                                        ->title('Error')
                                        ->body('No se encontró el contacto en la respuesta.')
                                        ->danger()
                                        ->send();
                                }
                            } else {
                                Notification::make()
                                    ->title('Error en API')
                                    ->body("Código de error: {$response->status()}")
                                    ->danger()
                                    ->send();
                            }
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Excepción')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    })
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListContactos::route('/'),
            //'create' => Pages\CreateContacto::route('/create'),
            //'edit' => Pages\EditContacto::route('/{record}/edit'),
        ];
    }
}
