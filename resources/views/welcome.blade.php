<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Silariz Online Store</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="font-sans antialiased bg-gray-100 text-gray-900">
        <div class="min-h-screen bg-gray-100 selection:bg-red-500 selection:text-white">
            <div class="p-6 text-right sm:fixed sm:top-0 sm:right-0 w-full bg-white shadow-sm flex justify-end gap-4 items-center">
                @auth
                    <a href="{{ auth()->user()->role === 'owner' ? url('/admin') : url('/customer') }}" class="font-semibold text-gray-600 hover:text-gray-900">Dashboard</a>
                @else
                    <a href="{{ url('/login') }}" class="font-semibold text-gray-600 hover:text-gray-900 border border-gray-300 px-3 py-1 rounded">Log in</a>
                @endauth
            </div>

            <div class="max-w-7xl mx-auto p-6 lg:p-8 mt-12">
                <div class="flex justify-center mt-8">
                    <h1 class="text-4xl font-bold text-gray-900">Silariz Online Store</h1>
                </div>
                <p class="text-center text-gray-500 mt-2">Pusat Layanan Aplikasi Premium Termurah & Terpercaya</p>

                <div class="mt-16">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($products as $product)
                            <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
                                <h2 class="text-xl font-semibold mb-2">{{ $product->name }}</h2>
                                <p class="text-gray-600 mb-4">{{ Str::limit($product->description, 100) }}</p>
                                <div class="flex justify-between items-center">
                                    <span class="font-bold text-lg">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                    <span class="text-sm {{ $product->stock > 0 ? 'text-green-600' : 'text-red-600' }}">Stok: {{ $product->stock }}</span>
                                </div>
                                <div class="mt-4">
                                    <a href="{{ route('checkout', $product->id) }}" class="inline-block w-full text-center bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 transition">Buat Pesanan</a>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center text-gray-500 py-8">
                                Belum ada produk / layanan yang tersedia.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
