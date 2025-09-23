<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductImport;
use Filament\Notifications\Notification;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Import Products')
                ->label('Import Product')
                ->icon('heroicon-s-arrow-down-tray')
                ->color('danger')
                ->form([
                    FileUpload::make('attachment')
                        ->label('Upload Template Product')
                ])
                ->action(function (array $data){
                    $file = public_path('storage/'. $data['attachment']);

                    try{
                        Excel::import(new ProductImport, $file);
                        Notification::make()
                            ->title('Product success imported')
                            ->success()
                            ->send();
                    }
                     catch(\Exeption $e) {
                        Notification::make()
                            ->title('Product failed to import')
                            ->danger()
                            ->send();
                    }
                }),
            Action::make("Download Template")
                ->url(route('download-template'))
                ->color('success'),
            Actions\CreateAction::make(),
        ];
    }
}
