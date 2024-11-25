<?php

namespace App\Livewire;

use App\Enumerations\CertificateType;
use App\Models\Certificate;
use Carbon\Carbon;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Grouping\Group;
use GrahamCampbell\ResultType\Error;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Filament\Tables\Table;

class ShowCertificates extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function render(
    ): \Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\View\View
    {
        return view('livewire.show-certificates');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Certificate::query())
            ->columns([
                TextColumn::make('gateway.name')
                    ->sortable(),
                TextColumn::make('type')
                    ->badge(),
                TextColumn::make('common_name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('valid_from')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('valid_to')
                    ->sortable(),
                IconColumn::make('is_valid')
                    ->label('Valid')
                    ->boolean()
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Cert Type')
                    ->options(CertificateType::class),
                SelectFilter::make('gateway')
                    ->relationship('gateway', 'name'),
                TernaryFilter::make('is_valid')
                    ->queries(
                        true: fn(Builder $query) => $query->where('valid_to', '>=', Carbon::now()),
                        false: fn(Builder $query) => $query->where('valid_to', '<', Carbon::now()),
                    ),
            ], layout: FiltersLayout::AboveContent)
            ->actions([

            ])
            ->bulkActions([
                // ...
            ])
            ->groups([
                Group::make('gateway.name')
                    ->getDescriptionFromRecordUsing(fn(Certificate $certificate) => $certificate->gateway->host)
            ])
            ->defaultGroup('gateway.name');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('gateway.name'),
            ]);
    }

}
