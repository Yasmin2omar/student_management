<?php

namespace App\Filament\Resources\Students\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StudentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->required(),
                TextInput::make('email')->required()->email()->unique(ignoreRecord:true),
                TextInput::make('phone')->length(11)->numeric()->unique(ignoreRecord:true),
                TextInput::make('age')->required()->length(2)->numeric(),
                Select::make('gender')->required()->options([
                    'male'=>'Male',
                    'female'=>'Female'
                ])
            ]);
    }
}
