@extends('layouts.app')
@section('title', 'Item Creator')

@section('content')
    <div class="breadcrumb">
        <a href="{{ route('admin.item.list') }}" class="btn btn-primary"><i class="fas fa-fw fa-chevron-left"></i> All items</a>

        <div class="flex-grow-1"></div>
    </div>
    <h1 class="text-center mb-3 mt-4">Item Creator</h1>


@endsection
