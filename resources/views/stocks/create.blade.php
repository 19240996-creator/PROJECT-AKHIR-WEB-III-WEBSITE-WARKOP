@extends('layouts.app')

@section('title', 'Tambah Stok')

@section('content')
    <div class="card form-card">
        <h1>Tambah Stok Barang</h1>
        <form action="{{ route('stocks.store') }}" method="POST">
            @csrf
            @include('stocks.form', ['stockItem' => null])
        </form>
    </div>
@endsection
