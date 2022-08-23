@extends('auth.auth-page');
@section('adminlte_css_pre')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@stop

@php($login_url = View::getSection('login_url') ?? config('adminlte.login_url', 'login'))
@php($register_url = View::getSection('register_url') ?? config('adminlte.register_url', 'register'))
@php($password_reset_url = View::getSection('password_reset_url') ?? config('adminlte.password_reset_url', 'password/reset'))

@if (config('adminlte.use_route_url', false))
    @php($login_url = $login_url ? route($login_url) : '')
    @php($register_url = $register_url ? route($register_url) : '')
    @php($password_reset_url = $password_reset_url ? route($password_reset_url) : '')
@else
    @php($login_url = $login_url ? url($login_url) : '')
    @php($register_url = $register_url ? url($register_url) : '')
    @php($password_reset_url = $password_reset_url ? url($password_reset_url) : '')
@endif

@section('auth_body')
    <form action="{{ route('custom/login') }}" method="post">
        @csrf

        <div class="input-group mb-3">
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email') }}" placeholder="{{ __('adminlte::adminlte.email') }}" autofocus>

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>
        </div>

        {{-- Password field --}}
        <div class="input-group mb-3">
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                placeholder="{{ __('adminlte::adminlte.password') }}">

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>


        </div>

        {{-- Login field --}}
        <div class="row">
            <div class="col">
                <button type=submit id=auth
                    class="btn btn-block {{ config('adminlte.classes_auth_btn', 'btn-flat btn-primary') }}">
                    <span class="fas fa-sign-in-alt"></span>
                    {{ __('adminlte::adminlte.sign_in') }}
                </button>
            </div>
        </div>
    </form>
@stop
@push('js')
    <x-notification></x-notification>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            $('button[id="auth"]').on('click', function(e) {
                e.preventDefault()
                let form = $(this).parent().parent().parent()
                $.ajax({
                        url: form.attr('action'),
                        data: form.serialize(),
                        type: 'POST',
                        proccessData: false,
                    }).done(function() {
                        Toast.fire({
                            icon: 'success',
                            title: "{{ __('Please wait') }}"
                        }).then((data) => {
                            window.location.href = "{{ route('home') }}";
                        })

                    })
                    .fail(function(xhr, status, error, responseJSON) {
                        let errors = xhr.responseJSON.errors
                        let message = '';
                        $.each(errors, function(value, index) {
                            message += `${errors[value]} \n`;
                        })
                        Toast.fire({
                            icon: 'error',
                            title: message
                        })
                    })
            })
        })
    </script>
@endPush
