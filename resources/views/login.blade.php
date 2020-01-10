@extends('layouts.app')
@section('title', 'Login')

@section('content')
    <h1 class="text-center">Login to <b>Pub!</b></h1>
    <div id="users" class="mt-3 d-flex justify-content-center">
        @foreach(\App\User::all() as $user)
            <div class="btn btn-outline-primary UserLoginButton m-2" data-id="{{ $user->id }}" style="width: 150px">
                <div class="user-id">{{ $user->id }}</div>
                <div class="user-name">{{ $user->name }}</div>
                @if ($user->is_admin)
                    <div class="user-type user-type-admin">Administrator</div>
                @else
                    <div class="user-type user-type-customer">Customer</div>
                @endif
            </div>
        @endforeach
    </div>
@endsection

@section('scripts')
    <script>
        Array.from(document.querySelectorAll('.UserLoginButton')).forEach(el => {
            el.addEventListener('click', function() {
                if (this.classList.contains('disabled')) return;

                Array.from(document.querySelectorAll('.UserLoginButton')).forEach(e => e.classList.add('disabled'));
                Array.from(document.querySelectorAll('.UserLoginButton')).forEach(e => e.classList.add('btn-disabled'));

                //document.querySelector('h1').innerHTML = `<i class="fas fa-spinner fa-fw fa-pulse"></i> Authenticating...`;

                fetch(`{{ route('api.doLogin') }}`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        "user_id": el.dataset.id
                    })
                })
                    .then(res => res.json())
                    .then(data => {
                        console.log(data);
                        if (data.reload) return window.location.reload();
                        window.location.href = data.redirect;
                    })
                    .catch(e => {
                        console.error(e);
                        document.querySelector('h1').innerHTML = `<i class="fas fa-times"></i> Error`;
                        notyf.error("An error occured");
                    })
            })
        })
    </script>
@endsection
