@extends('layouts.app')
@section('title', $category->name)

@section('content')
    <h1>{{ $category->name }}</h1>

    @foreach($category->items->chunk(2) as $chunk)
        <div class="row">
            @foreach($chunk as $item)
                <div class="col-sm-12 col-md-6">
                    @include('snippets.item', $item)
                </div>
            @endforeach
        </div>
    @endforeach
@endsection
