@extends('layouts.app')

@section('title', __('العملات والصرافة'))

@section('content')

@include('cctools::layouts.partials.nav')

<div class="container mt-4">
    <div class="card">
    <div class="card-header" style="padding: 10px 20px;">
        <h3 class="card-title" style="flex-grow: 1; margin: 0; font-weight: bold; font-size: 1.5rem; display: flex;">
            @lang('cctools::lang.welcome_0')
        </h3>
    </div>
        <div class="card-body">
            <p class="card-text">
                @lang( 'cctools::lang.welcome_1')
            </p>
            <p class="card-text">
                @lang( 'cctools::lang.welcome_2')
            </p>
            <p class="card-text">
                @lang( 'cctools::lang.welcome_3')
            </p>

            <h1>@include('cctools::buttons')</h1>
        </div>

    </div>
</div>

@endsection
