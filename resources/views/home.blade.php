@extends('layouts.app')
@section('title', 'Home')

@section('content')
    <div id="cards" class="d-flex flex-wrap justify-content-center">
        @foreach(\App\Category::all() as $category)
            <a href="{{ route('category', $category->getSlug()) }}" class="card-link-cover m-2">
                <div class="card">
                    <img src="{{ $category->getImage() }}" alt="" class="card-img-top">
                    <div class="card-body">
                        <h4>{{ $category->name }}</h4>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
@endsection
