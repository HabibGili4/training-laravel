@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
    <!-- Welcome Card -->
    <div class="col-span-full rounded-lg bg-white p-6 shadow-theme-xs dark:bg-gray-900">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
            Selamat datang, {{ Auth::user()->name }}!
        </h2>
        <p class="mt-2 text-gray-500 dark:text-gray-400">
            Kelola produk Anda dari dashboard ini.
        </p>
    </div>

    <!-- Stats Card -->
    <div class="rounded-lg bg-white p-6 shadow-theme-xs dark:bg-gray-900">
        <div class="flex items-center gap-4">
            <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-brand-50 dark:bg-brand-500/10">
                <svg class="w-6 h-6 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Total Produk</p>
                <p class="text-2xl font-bold text-gray-800 dark:text-white" id="product-count">-</p>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="rounded-lg bg-white p-6 shadow-theme-xs dark:bg-gray-900">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Quick Actions</h3>
        <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white rounded-lg bg-brand-500 hover:bg-brand-600 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Kelola Produk
        </a>
    </div>

    <!-- User Info -->
    <div class="rounded-lg bg-white p-6 shadow-theme-xs dark:bg-gray-900">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Akun Anda</h3>
        <div class="space-y-2">
            <p class="text-sm text-gray-500 dark:text-gray-400">Nama: <span class="text-gray-800 dark:text-white font-medium">{{ Auth::user()->name }}</span></p>
            <p class="text-sm text-gray-500 dark:text-gray-400">Email: <span class="text-gray-800 dark:text-white font-medium">{{ Auth::user()->email }}</span></p>
        </div>
    </div>
</div>

<script>
    fetch('/api/products')
        .then(r => r.json())
        .then(data => {
            document.getElementById('product-count').textContent = data.data ? data.data.length : 0;
        })
        .catch(() => {
            document.getElementById('product-count').textContent = '0';
        });
</script>
@endsection
