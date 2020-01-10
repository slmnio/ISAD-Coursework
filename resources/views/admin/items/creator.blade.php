@extends('layouts.app')
@section('title', 'Item Creator')

@section('content')
    <div class="breadcrumb">
        <a href="{{ route('admin.item.list') }}" class="btn btn-primary"><i class="fas fa-fw fa-chevron-left"></i> All
            items</a>

        <div class="flex-grow-1"></div>
    </div>
    <h1 class="text-center mb-3 mt-4">Item Creator</h1>

    <div class="container">
        <div class="form-group">
            <label for="inputName">Name</label>
            <input type="text" class="form-control" placeholder="Item name" id="inputName">
        </div>
        <div class="form-group">
            <label for="inputDescription">Description</label>
            <input type="text" class="form-control" placeholder="Item description" id="inputDescription">
        </div>
        <div class="form-group">
            <label for="inputCategory">Category</label>
            <select class="form-control" id="inputCategory">
                <option value="-1" selected disabled>Choose a category...</option>
                @foreach(\App\Category::all() as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="inputPence">Cost (pence)</label>
            <div class="row">
                <div class="col-6">
                    <input type="number" step="1" class="form-control" placeholder="300 (£3)" id="inputPence">
                </div>
                <div class="col-6">
                    <input type="text" disabled class="form-control" id="formattedPence">
                </div>
            </div>
        </div>
        <div class="d-flex">
            <div class="flex-grow-1"></div>
            <button class="btn btn-success" id="item-create">Create item</button>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        document.querySelector('#inputPence').addEventListener('input', function() {
            document.querySelector('#formattedPence').value = `£${ (parseInt(this.value || 0) / 100).toFixed(2) }`
        })

            function isEmpty(sel) {
                let el = document.querySelector(sel);
                return (el.value === null || el.value.trim() === "" || el.value === -1)
            }

        (function () {
            let actuator = new Actuator("#item-create", `{{ route('admin.item.create') }}`, "POST");

            actuator.bindElement("name", "#inputName");
            actuator.bindElement("description", "#inputDescription");
            actuator.bindElement("cost_pence", "#inputPence");
            actuator.bindElement("category_id", "#inputCategory");

            actuator.setMiddleware(function (el) {
                // error checking here
                let fail = (isEmpty('#inputName') || isEmpty('#inputDescription') || isEmpty('#inputPence') || isEmpty('#inputCategory'));

                if (fail) {
                    notyf.error("There are parts of the form missing.");
                }
                return !fail;
            })
            actuator.setSuccess(function (data) {
                window.location.href = data.redirect;
            })
            actuator.setFailure(function (data) {
                notyf.error("There was an error creating this item.");
            })
        })();
    </script>
    @endsection
