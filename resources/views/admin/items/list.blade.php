@extends('layouts.app')
@section('title', 'Pub! Items')

@section('content')
    <div class="breadcrumb">
        <div class="flex-grow-1"></div>
        <a href="{{ route('admin.item.creator') }}" class="btn btn-primary"><i class="fas fa-fw fa-plus"></i> Create item</a>
    </div>
    <h1 class="text-center mb-3 mt-4">Items</h1>

    <div id="order-list">
        @foreach ($items as $item)
            <li class="list-group-item d-flex align-items-center" id="order-list">
                <div class="item-id text-center" style="width: 50px;">#{{ $item->id }}</div>
                <div class="item-img border rounded"
                     style="background: url({{ $item->getImage() }}) center no-repeat;"></div>
                <div class="item-name flex-grow-1">{{ $item->name }}</div>
                <div class="item-tools mr-2">
                    <a href="{{ route('admin.item.view', $item) }}" class="btn btn-primary px-2 py-1 mr-1">
                        <i class="fas fa-fw fa-pencil text-white"></i>
                    </a>
                </div>
            </li>
        @endforeach
    </div>


    <style>.pagination {
            justify-content: center
        }</style>
    <div class="pagination-holder">
        {{ $items->links() }}
    </div>
@endsection

@section('script')
@endsection
