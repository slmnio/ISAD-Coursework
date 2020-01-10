@extends('layouts.app')
@section('title', 'Order #' . $order->id)

@section('content')
    <div class="breadcrumb">
        <a href="{{ route('order.list') }}" class="btn btn-primary"><i class="fas fa-fw fa-chevron-left"></i> All orders</a>

        <div class="flex-grow-1"></div>
        <div class="btn btn-danger" id="order-delete"><i class="fas fa-fw fa-trash"></i> Delete order</div>
    </div>
    <h1 class="text-center mb-1 mt-4">Order #{{ $order->id }}</h1>
    <h5 class="subtitle text-center text-muted mb-3">
        @if ($order->table_number)
            at table #{{ $order->table_number }}<br>
        @endif
        {{ $order->created_at->format('g:ia jS F Y') }}
    </h5>

    <div id="order-list">
        @foreach ($order->items as $item)
            <li class="list-group-item d-flex align-items-center">
                <div class="item-quantity">{{ $item->pivot->quantity }} &times;</div>
                <div class="item-img border rounded" style="background: url({{ $item->getImage() }}) center no-repeat;"></div>
                <div class="item-name flex-grow-1">{{ $item->name }}</div>
                @if ($item->pivot->quantity > 1)
                    <div class="item-subtotal text-muted mr-5">{{ $item->pivot->quantity }} @ {{ $item->getFormattedPrice() }}</div>
                @endif
                <div class="item-cost">{{ \App\Item::formatPrice($item->cost_pence * $item->pivot->quantity) }}</div>
            </li>
            @endforeach

            <li class="list-group-item d-flex" id="total">
                <div class="flex-grow-1"></div>
                <div class="mr-3 text-muted">Total</div>
                <div class="text-info"><b>{{ \App\Item::formatPrice($order->getTotal()) }}</b></div>
            </li>
    </div>

@endsection


@section('scripts')
    <script>
        let actuator = new Actuator("#order-delete", `{{ route('order.delete', $order) }}`, "DELETE");
        actuator.setMiddleware(function(el) {
            return confirm("Are you sure you want to delete this order?");
        })
        actuator.setSuccess(function(data) {
            notyf.success("Order deleted.")
            setTimeout(function() {
                window.location.href = data.redirect;
            }, 1500);
        })
        actuator.setFailure(function(data) {
            notyf.error("There was an error deleting this order.");
        })
    </script>
@endsection
