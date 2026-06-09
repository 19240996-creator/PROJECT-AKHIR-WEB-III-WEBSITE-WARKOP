@extends('layouts.app')

@section('title', 'Edit Menu')

@section('content')
    <div class="card">
        <h1>Edit Menu</h1>
        <form action="{{ route('menus.update', $menu) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('menus.form', ['menu' => $menu])
        </form>
    </div>
@endsection
