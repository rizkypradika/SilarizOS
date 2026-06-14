<?php
require __DIR__ . '/vendor/autoload.php';
try {
    \Filament\Forms\Components\TextInput::make('test')->live(onBlur: false);
    echo "Live parameter OK\n";
} catch (\Throwable $e) {
    echo "Live parameter ERROR: " . $e->getMessage() . "\n";
}
