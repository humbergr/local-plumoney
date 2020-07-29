@extends('emails.emails-admin-layout')

@section('content')

<div class="email-content" style="padding: 2rem 3rem;">
    <h4 class="text-primary text-heading" style="margin: 0 0 1rem 0;color: #303392;font-weight: 400;font-size: 1.5rem;">Payment receipt.</h4>
    <p>A new payment receipt was upload</p>
    <p>The exchange transaction with ID {{$data['transaction_id']}} has been marked as paid.</p>
    <p><strong>Click the button below</strong> for more details about the transaction.</p>

    <a href="{{$data['url']}}" class="btn-primary" style="text-decoration: none;color: white;display: inline-block;padding: 0.75rem 1rem;background-color: #303392;border-radius: 10rem;font-weight: 500;">View Transaction</a>
    <p>Or copy and paste this link on your browser:<br><a href="{{$data['url']}}" style="text-decoration: none;color: #346bff;">{{$data['url']}}</a></p>

</div>

@endsection
