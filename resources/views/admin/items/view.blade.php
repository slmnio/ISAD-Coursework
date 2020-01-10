@extends('layouts.app')
@section('title', $item->name)

@section('content')
    <div class="breadcrumb">
        <a href="{{ route('admin.item.list') }}" class="btn btn-primary"><i class="fas fa-fw fa-chevron-left"></i> All items</a>

        <div class="flex-grow-1"></div>

        @if ($item->enabled)
            <div class="btn btn-success ml-2" id="item-toggle"><i class="fas fa-fw fa-toggle-on"></i> Enabled</div>
        @else
            <div class="btn btn-danger ml-2" id="item-toggle"><i class="fas fa-fw fa-toggle-off"></i> Disabled</div>
        @endif

        <div class="btn btn-danger ml-2" id="item-delete"><i class="fas fa-fw fa-trash"></i> Delete item</div>
    </div>

    <div class="item-large-img border rounded mt-2" style="background: url({{ $item->getImage() }}) center no-repeat;"></div>

    <h1 class="text-center mb-1 mt-1">{{ $item->name }}</h1>
    <h5 class="subtitle text-center text-muted mb-3">Item #{{ $item->id }}<br>{{ $item->description }}</h5>


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
                notyf.error("There was an error deleting this item.");
            })
        })();
        (function () {
            let actuator = new Actuator("#item-toggle", `{{ route('admin.item.toggle', $item) }}`, "POST");
            actuator.setSuccess(function (data) {
                if (data.reload) window.location.reload();
            })
            actuator.setFailure(function (data) {
                notyf.error("There was an error altering this item.");
            })
        })();
    </script>
@endsection
