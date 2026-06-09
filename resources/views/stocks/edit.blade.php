@extends('layouts.app')

@section('title', 'Edit Stok')

@section('content')
    <div class="card">
        <h1>Edit Stok Barang</h1>
        <form action="{{ route('stocks.update', $stockItem) }}" method="POST">
            @csrf
            @method('PUT')
            @include('stocks.form', ['stockItem' => $stockItem])
        </form>
    </div>
@endsection
