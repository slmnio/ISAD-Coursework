@extends('layouts.app')
@section('title', $item->name)

@section('content')
    <div class="breadcrumb">
        <a href="{{ route('admin.item.list') }}" class="btn btn-primary"><i class="fas fa-fw fa-chevron-left"></i> All items</a>

        <div class="flex-grow-1"></div>
        <div class="btn btn-danger" id="item-delete"><i class="fas fa-fw fa-trash"></i> Delete item</div>
    </div>
    <h1 class="text-center mb-1 mt-4">{{ $item->name }}</h1>
    <h5 class="subtitle text-center text-muted mb-3">Item #{{ $item->id }}</h5>


@endsection


@section('scripts')
    <script>
        (function () {
            let actuator = new Actuator("#item-delete", `{{ route('admin.item.delete', $item) }}`, "DELETE");
            actuator.setMiddleware(function (el) {
                return confirm("Are you sure you want to delete this item?");
            })
            actuator.setSuccess(function (data) {
                window.location.href = data.redirect;
            })
            actuator.setFailure(function (data) {
                notyf.error("There was an error deleting this order.");
            })
        })();
    </script>
@endsection
