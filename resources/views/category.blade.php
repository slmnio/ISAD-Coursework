@extends('layouts.app')
@section('title', $category->name)

@section('content')
    <h1 class="text-center">{{ $category->name }}</h1>

    @foreach($category->items->chunk(2) as $chunk)
        <div class="row my-3">
            @foreach($chunk as $item)
                <div class="col-sm-12 col-md-6">
                    @include('snippets.item', $item)
                </div>
            @endforeach
        </div>
    @endforeach
@endsection

@section('scripts')
    <script id="basketables">
        console.log("cart", {!! json_encode(session()->get('cart')) !!});

        Array.from(document.querySelectorAll('.Basketable')).forEach(el => {
            el.addEventListener('click', function() {
                if(this.classList.contains('item--processing')) return;

                this.querySelector('.item-icon').innerHTML = `<i class="fas fa-spinner fa-fw fa-pulse"></i>`;
                this.classList.add('item--processing');

                fetch(`{{ route('api.add-to-cart') }}`, {
                    method: "PUT",
                    headers: {"Content-Type": "application/json", 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content},
                    body: JSON.stringify({
                        "item_id": this.dataset.id,
                    })
                })
                    .then(res => res.json())
                    .then(data => {
                        console.log(data);
                        el.querySelector('.item-icon').innerHTML = `<i class="fas fa-check text-success"></i>`;
                        notyf.success(`Added ${data[data.length-1].name} to your basket`);
                        document.querySelector('#basket-count').innerHTML = data.length;
                        setTimeout(function() {
                            el.classList.remove('item--processing');
                            el.querySelector('.item-icon').innerHTML = `<i class="fas fa-plus"></i>`;
                        }, 1500);
                    })
                    .catch(e => {
                        console.error(e);
                        notyf.error("An error prevented you from adding that item to your basket.");
                    })
            })
        })
    </script>
@endsection
