<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (empty($data['account_info'])) {
            $accounts = [];
            $order = $this->getRecord();
            
            if ($order->items) {
                foreach ($order->items as $item) {
                    $productName = $item->product ? $item->product->name : 'Unknown Product';
                    for ($i = 0; $i < $item->quantity; $i++) {
                        $accounts[] = [
                            'product_name' => $productName . ($item->quantity > 1 ? ' (#' . ($i + 1) . ')' : ''),
                            'type' => null,
                        ];
                    }
                }
            }
            
            $data['account_info'] = $accounts;
        }
        
        return $data;
    }
}
