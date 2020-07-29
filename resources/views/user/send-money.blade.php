@extends('layouts.mvp-layout-internal')

@section('content')

    <main>
        <section class="pb-5">
            <div class="container mt-md-n5">
                <div class="row">
                    @if(Session::has('success'))
                        <div class="col-lg-9 col-xl-10 mx-auto px-0 px-md-3">
                            <div class="alert alert-success" role="alert">
                                {{Session::get('success')}}
                            </div>
                        </div>
                    @endif
                    <div class="col-lg-10 mx-auto">
                        <send-money user="{{Auth::user()->personProfileObject()}}"
                                    trans-tracking="{{uniqid('', false)}}"
                                    qbpay="{{$qbPay}}"
                                    :bonus="{{$bonus}}"></send-money>

                        {{--<div class="d-none d-md-block text-primary small px-3 px-md-0 wow fadeInUp">
                            <h6 class="text-center">Información importante</h6>
                            <p>1 Date available will be displayed on receipt for international transfers over $15.
                                Service
                                and funds may be delayed or unavailable depending on certain factors including the
                                Service
                                selected, the selection of delayed delivery options, special terms applicable to each
                                Service, amount sent, destination country, currency availability, regulatory issues,
                                consumer protection issues, identification requirements, delivery restrictions, agent
                                location hours, and differences in time zones (collectively, “Restrictions”). Additional
                                restrictions may apply; see our terms and conditions for details.</p>
                            <p>1 Date available will be displayed on receipt for international transfers over $15.
                                Service
                                and funds may be delayed or unavailable depending on certain factors including the
                                Service
                                selected, the selection of delayed delivery options, special terms applicable to each
                                Service, amount sent, destination country, currency availability, regulatory issues,
                                consumer protection issues, identification requirements, delivery restrictions, agent
                                location hours, and differences in time zones (collectively, “Restrictions”). Additional
                                restrictions may apply; see our terms and conditions for details.</p>
                        </div>--}}
                    </div>
                </div>
            </div>
        </section>
    </main>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
@endsection
