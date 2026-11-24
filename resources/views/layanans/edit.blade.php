@extends('layouts.app')

@section('title', 'Ubah Layanan')

@section('content')
    <div class="max-w-2xl space-y-6">
        <div class="rounded-2xl bg-gradient-to-r from-sky-600 via-cyan-600 to-blue-600 p-5 sm:p-6 text-white shadow-md shadow-sky-600/15">
            <a href="{{ route('layanans.index') }}" class="text-xs font-bold text-sky-100 hover:text-white">
                ← Kembali ke Data Layanan
            </a>
            <h1 class="mt-2 text-xl sm:text-2xl font-extrabold tracking-tight text-white">Ubah Layanan: {{ $layanan->nama_layanan }}</h1>
            <p class="mt-1 text-sm text-sky-100">Perbarui tarif atau estimasi pengerjaan layanan ini.</p>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-sky-200/80 border-t-4 border-t-sky-500">
            <form method="POST" action="{{ route('layanans.update', $layanan) }}">
                @csrf
                @method('PUT')
                @include('layanans._form', ['layanan' => $layanan, 'submitLabel' => 'Simpan Perubahan'])
            </form>
        </div>
    </div>
@endsection
