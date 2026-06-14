<?php

namespace App\Filament\Customer\Resources\Products\Pages;

use App\Filament\Customer\Resources\Products\ProductResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProducts extends ListRecords
{
    protected static ?string $title = 'Daftar Stok';

    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No actions
        ];
    }

    public function getTabs(): array
    {
        $streamingKeywords = ['Netflix', 'Disney', 'Spotify', 'YouTube', 'Vidio', 'Bstation', 'Prime', 'HBO', 'Viu'];
        $educationKeywords = ['Canva', 'Grammarly', 'CapCut', 'Zoom', 'Scribd', 'Duolingo'];

        return [
            'Semua' => \Filament\Schemas\Components\Tabs\Tab::make('Semua')
                ->icon('heroicon-m-squares-2x2'),
            'Streaming' => \Filament\Schemas\Components\Tabs\Tab::make('Streaming')
                ->icon('heroicon-m-play-circle')
                ->modifyQueryUsing(function ($query) use ($streamingKeywords) {
                    $query->where(function ($q) use ($streamingKeywords) {
                        foreach ($streamingKeywords as $keyword) {
                            $q->orWhere('name', 'like', "%{$keyword}%");
                        }
                    });
                }),
            'Edukasi & Desain' => \Filament\Schemas\Components\Tabs\Tab::make('Edukasi & Desain')
                ->icon('heroicon-m-academic-cap')
                ->modifyQueryUsing(function ($query) use ($educationKeywords) {
                    $query->where(function ($q) use ($educationKeywords) {
                        foreach ($educationKeywords as $keyword) {
                            $q->orWhere('name', 'like', "%{$keyword}%");
                        }
                    });
                }),
            'Lainnya' => \Filament\Schemas\Components\Tabs\Tab::make('Lainnya')
                ->icon('heroicon-m-ellipsis-horizontal-circle')
                ->modifyQueryUsing(function ($query) use ($streamingKeywords, $educationKeywords) {
                    $query->where(function ($q) use ($streamingKeywords, $educationKeywords) {
                        foreach (array_merge($streamingKeywords, $educationKeywords) as $keyword) {
                            $q->where('name', 'not like', "%{$keyword}%");
                        }
                    });
                }),
        ];
    }
}
