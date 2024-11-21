<?php

namespace App\Livewire;

use App\Models\Certificate;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Livewire\Component;
use Filament\Tables\Table;

class Home extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;


    public function render()
    {
        return view('livewire.home');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Certificate::query())
            ->columns([
                TextColumn::make('name'),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                // ...
            ])
            ->bulkActions([
                // ...
            ]);
    }
}
