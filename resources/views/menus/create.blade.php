@extends('layouts.app')

@section('title', 'Tambah Menu')

@section('content')
    <div class="card">
        <h1>Tambah Menu</h1>
        <form action="{{ route('menus.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('menus.form', ['menu' => null])
        </form>
    </div>
@endsection
