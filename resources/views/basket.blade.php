@extends('layouts.app')
@section('title', 'Basket')

@section('content')
    <h1 class="text-center">Basket</h1>
    <div class="card mt-3" id="basket" style="max-width: 600px; margin: 0 auto;">
        <ul class="list-group list-group-flush">
            @if (count($basketItems) === 0)
                <li class="list-group-item text-center text-muted">Your basket is empty.</li>
                @endif
            @foreach ($basketItems as $bitem)
                <li class="list-group-item d-flex align-items-center">
                    <div class="item-quantity">{{ $bitem->quantity }} &times;</div>
                    <div class="item-img border rounded" style="background: url({{ $bitem->item->getImage() }}) center no-repeat;"></div>
                    <div class="item-name flex-grow-1">{{ $bitem->item->name }}</div>
                    @if ($bitem->quantity > 1)
                        <div class="item-subtotal text-muted mr-5">{{ $bitem->quantity }} @ {{ $bitem->item->getFormattedPrice() }}</div>
                    @endif
                    <div class="item-cost">{{ \App\Item::formatPrice($bitem->item->cost_pence * $bitem->quantity) }}</div>
                    <div class="item-tools"><div class="btn btn-danger BitemDeleter px-2 py-1 ml-2" data-id="{{ $bitem->item->id }}"><i class="fas fa-fw fa-minus text-white" ></i></div></div>
                </li>
                @endforeach
                @if (count($basketItems) > 0)
                    <li class="list-group-item d-flex border-info" id="total">
                        <div class="flex-grow-1"></div>
                        <div class="mr-3 text-muted">Total</div>
                        <div class="text-info"><b>{{ \App\Item::formatPrice($total) }}</b></div>
                    </li>
                @endif
        </ul>
    </div>

    @if (count($basketItems) > 0)
        <div class="row d-flex mt-3" style="max-width: 600px; margin: 0 auto">
            <div class="btn btn-danger" id="empty">Empty basket</div>
            <div class="flex-grow-1"></div>

            @if (Auth::check())
                <div class="btn btn-success" id="order">Order</div>
            @else
                <div class="text-center text-danger btn "><a class="text-info" href="{{ route('login') }}"><b>Login</b></a> to submit your order</div>
            @endif
        </div>
    @endif


@endsection

@section('scripts')
    <script>
        Array.from(document.querySelectorAll('#empty')).forEach(el => {
            el.addEventListener('click', function() {
                if (this.classList.contains('disabled')) return;
                if (!confirm("Are you sure you want to empty the basket?")) return;

                this.innerHTML = `<i class="fas fa-spinner fa-fw fa-pulse"></i> Emptying...`;
                this.classList.add('disabled');

                fetch(`{{ route('api.empty-cart') }}`, {
                    method: "DELETE",
                    headers: {"Content-Type": "application/json", 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content},
                })
                    .then(res => res.json())
                    .then(data => {
                        console.log(data);
                        if (data.reload) return window.location.reload();
                        window.location.href = data.redirect;
                    })
                .catch(e => {
                    console.error(e);
                    notyf.error("An error occured");
                    el.classList.remove('btn-success')
                    el.classList.add('btn-danger')
                    el.innerHTML = `<i class="fas fa-times"></i> Emptying failed`;
                })
            })
        })
        Array.from(document.querySelectorAll('#order')).forEach(el => {
            el.addEventListener('click', function() {
                if(this.classList.contains('disabled')) return;

                let tableNumber = prompt("What's your table number?");

                this.innerHTML = `<i class="fas fa-spinner fa-fw fa-pulse"></i> Ordering...`;
                this.classList.add('disabled');

                fetch(`{{ route('api.order') }}`, {
                    method: "POST",
                    headers: {"Content-Type": "application/json", 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content},
                    body: JSON.stringify({
                        table_number: tableNumber
                    })
                })
                    .then(res => res.json())
                    .then(data => {
                        console.log(data);
                        notyf.success(`Order #${data.order.id} placed.`);
                        el.innerHTML = `<i class="fas fa-check fa-fw"></i> Ordered`;
                        if (data.redirect) {
                            setTimeout(function() {
                                window.location.href = data.redirect;
                            }, 1500);
                        }
                    })
                .catch(e => {
                    console.error(e);
                    notyf.error("An error occured");
                    el.classList.remove('btn-success')
                    el.classList.add('btn-danger')
                    el.innerHTML = `<i class="fas fa-times"></i> Order failed`;
                })
            })
        })
        Array.from(document.querySelectorAll('.BitemDeleter')).forEach(el => {
            el.addEventListener('click', function() {
                if (this.classList.contains('disabled')) return;
                if (!confirm("Are you sure you want to remove an item from your basket?")) return;

                this.innerHTML = `<i class="fas fa-spinner fa-fw fa-pulse"></i>`;
                this.classList.add('disabled');

                fetch(`{{ route('api.remove-from-cart') }}`, {
                    method: "POST",
                    headers: {"Content-Type": "application/json", 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content},
                    body: JSON.stringify({
                        "item_id": el.dataset.id
                    })
                })
                    .then(res => res.json())
                    .then(data => {
                        console.log(data);
                        notyf.success(`Removed from your basket.`);
                        el.innerHTML = `<i class="fas fa-fw fa-check"></i>`;

                        setTimeout(function() {
                            window.location.reload()
                        }, 1500);
                    })
                .catch(e => {
                    console.error(e);
                    notyf.error("An error occured");
                    el.classList.remove('btn-success')
                    el.classList.add('btn-danger')
                    el.innerHTML = `<i class="fas fa-times"></i>`;
                })
            })
        })
    </script>
@endsection
