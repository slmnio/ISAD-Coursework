@extends('layouts.app')
@section('title', 'Basket')

@section('content')
    <h1 class="text-center">Basket</h1>
    <div class="card mt-3" id="basket" style="max-width: 600px; margin: 0 auto;">
        <ul class="list-group list-group-flush">
            @foreach ($basketItems as $bitem)
                <li class="list-group-item d-flex">
                    <div class="item-quantity">{{ $bitem->quantity }} &times;</div>
                    <div class="item-img border rounded" style="background: url({{ $bitem->item->getImage() }}) center no-repeat;"></div>
                    <div class="item-name flex-grow-1">{{ $bitem->item->name }}</div>
                    @if ($bitem->quantity > 1)
                        <div class="item-subtotal text-muted mr-5">{{ $bitem->quantity }} @ {{ $bitem->item->getFormattedPrice() }}</div>
                    @endif
                    <div class="item-cost">{{ \App\Item::formatPrice($bitem->item->cost_pence * $bitem->quantity) }}</div>
                </li>
            @endforeach
            <li class="list-group-item d-flex border-info" id="total">
                <div class="flex-grow-1"></div>
                <div class="mr-3 text-muted">Total</div>
                <div class="text-info"><b>{{ \App\Item::formatPrice($total) }}</b></div>
            </li>
        </ul>
    </div>

    <div class="row d-flex mt-3" style="max-width: 600px; margin: 0 auto">
        <div class="flex-grow-1"></div>
        <div class="btn btn-success" id="order">Order</div>
    </div>


@endsection

@section('scripts')
    <script>
        Array.from(document.querySelectorAll('#order')).forEach(el => {
            el.addEventListener('click', function() {
                if(this.classList.contains('disabled')) return;

                let tableNumber = prompt("What's your table number?");

                this.innerHTML = `<i class="fas fa-spinner fa-pulse"></i> Ordering...`;
                this.classList.add('disabled');

                fetch(`{{ route('api.order') }}`, {
                    method: "POST",
                    headers: {"Content-Type": "application/json", 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content},
                })
                    .then(res => res.json())
                    .then(data => {
                        console.log(data);
                        notyf.success(`Order placed.`);

                        setTimeout(function() {
                            el.classList.remove('item--processing');
                        }, 1500);
                    })
                .catch(e => {
                    notyf.error("An error occured");
                })
            })
        })
    </script>
@endsection
