@extends('layouts.coinbank-layout')

@section('content')

    <section id="page-title" class="pt-5 pb-4">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mx-auto">
                    <div class="text-center text-white">
                        <img src="{{ asset('img/landing/sendPerson-secondary.svg') }}" alt="Transactions" class="img-fluid">
                        @if($purpose === 1)
                            <h1 class="page__title text-primary mb-1">Transacciones de Carga</h1>
                        @else
                            <h1 class="page__title text-primary mb-1">Transacciones de Retiro</h1>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="transactions" class="py-section-3">
        <wallet-transactions-list :transactions_init_value="{{json_encode($transactions)}}"
                                  :purpose="{{$purpose}}">
        </wallet-transactions-list>
    </section>

@endsection
