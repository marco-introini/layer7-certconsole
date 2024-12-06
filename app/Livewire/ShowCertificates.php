<?php

namespace App\Livewire;

use App\Enumerations\CertificateType;
use App\Models\Certificate;
use Carbon\Carbon;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Grouping\Group;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Component;
use Filament\Tables\Table;

class ShowCertificates extends Component implements HasForms, HasTable, HasInfolists
{
    use InteractsWithForms;
    use InteractsWithTable;
    use InteractsWithInfolists;

    public function render(
    ): View
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
                ViewAction::make()
                    ->infolist([
                        Section::make('Certificate Info')
                        ->schema([
                            TextEntry::make('gateway.name'),
                            TextEntry::make('type')
                                ->badge(),
                            TextEntry::make('common_name')
                                ->columnSpanFull(),
                            TextEntry::make('valid_from'),
                            TextEntry::make('valid_to'),
                        ])->columns(),
                    Section::make('Certificate Content')
                        ->schema([
                            TextEntry::make('formatted_certificate')
                                ->columnSpanFull()
                                ->html(),
                        ])->collapsed()

                    ])
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

}
