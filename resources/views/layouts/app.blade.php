<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>
        @if(View::hasSection('title'))
            @yield('title') - Pub!
        @else
            Pub!
        @endif
    </title>
    <link rel="icon" href="/assets/icon.png">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="/assets/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/app.css">
    <link rel="stylesheet" href="/assets/notyf.min.css">
</head>
<body>
<nav class="navbar navbar-expand-md fixed-top navbar-dark bg-primary">
    <div class="navbar-collapse collapse w-100 order-1 order-md-0 dual-collapse2">

        <ul class="navbar-nav mr-auto">
                @if(Auth::check())
                <li class="nav-item">
                    <div class="navbar-text"><i class="fas fa-user fa-fw"></i> {{ Auth::user()->name  }}</div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}">Logout</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('order.list') }}">Your Orders</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.item.list') }}">Administration</a>
                </li>
                @else
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">Login</a>
                </li>
                @endif
        </ul>
    </div>
    <div class="mx-auto order-0">
        <a class="navbar-dark mx-auto text-white h4" href="/">Pub!</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".dual-collapse2">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
    <div class="navbar-collapse collapse w-100 order-3 dual-collapse2">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('basket') }}">Basket <span class="badge badge-pill badge-light" id="basket-count">{{  session()->get('cart')->count() }}</span></a>
            </li>
        </ul>
    </div>
</nav>


<div id="content">
    <div class="container">
        @yield('content')
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/07756738a6.js" crossorigin="anonymous"></script>
<script src="/assets/notyf.min.js"></script>
<script>
    const notyf = new Notyf({duration:3500});
</script>


@if (Session::has('success-message'))
    <script>
        notyf.success(`{{ Session::get('success-message') }}`)
    </script>
@endif
@if (Session::has('error-message'))
    <script>
        notyf.success(`{{ Session::get('error-message') }}`)
    </script>
@endif

<script>

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
                    el.innerHTML = `<i class="fas fa-fw fa-check"></i>`;
                    window.location.reload()
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
<script>
    // making a single handler
    class Actuator {
        constructor(selector, route, method) {
            this.elements = Array.from(document.querySelectorAll(selector));
            this.route = route;
            this.method = method;
            this.data = {};

            this.setListeners();
        }

        setListeners() {
            let instance = this;
            this.elements.forEach(el => {
                el.addEventListener('click', function() {
                    if (el.classList.contains('disabled')) return;
                    if (instance.passesMiddleware(this)) {
                        el.classList.add('disabled');
                        instance.sendRequest();
                    }
                });
            })
        }

        passesMiddleware(element) {
            if (!this.middlewareFn) return true;
            return this.middlewareFn(element);
        }

        setMiddleware(fn) {
            this.middlewareFn = fn;
        }

        put(key, val) {
            this.data[key] = val;
        }

        sendRequest() {
            fetch(this.route, {
                method: this.method,
                headers: {
                    "Content-Type": "application/json",
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(this.data)
            })
                .then(res => res.json())
                .then(data => this.successFn(data))
                .catch(e => this.failureFn(e))
        }
        setSuccess(fn) {
            this.successFn = fn;
        }
        setFailure(fn) {
            this.failureFn = fn;
        }
    }
</script>
@yield('scripts')
</body>
</html>
