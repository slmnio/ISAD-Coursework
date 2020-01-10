@extends('layouts.app')
@section('title', 'Your Orders')

@section('content')
    <h1 class="text-center mb-3">Your orders</h1>

    <table class="table table-bordered" id="order-list">
        <tbody>
        @foreach ($orders as $order)
            <tr>
                <td>
                    <div class="order-top order-date mb-2 text-center d-inline-block w-100">
                        <a href="{{ route('order.view', $order) }}" class="">Order #{{ $order->id }}</a>{{ $order->table_number ? " at table #" . $order->table_number : "" }}<br> {{ $order->created_at->format('g:ia jS F Y') }}
                    </div>


                    <ul class="list-group">
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
                    </ul>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>


    <style>.pagination{justify-content: center}</style>
    <div class="pagination-holder">
        {{ $orders->links() }}
    </div>
@endsection
