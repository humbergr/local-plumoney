@extends('emails.emails-layout')

@section('content')

  <div class="email-content" style="padding: 2rem 3rem;">
      <h4 class="text-primary text-heading" style="margin: 0 0 1rem 0;color: #303392;font-weight: 400;font-size: 1.5rem;"><strong>@lang('email-confirmation.activate')</strong> @lang('email-confirmation.your_account')</h4>
      <p><strong>@lang('email-confirmation.last_step')</strong>@lang('email-confirmation.click_button')</p>

      <a href="{{$data['url']}}" class="btn-primary" style="text-decoration: none;color: white;display: inline-block;padding: 0.75rem 1rem;background-color: #303392;border-radius: 10rem;font-weight: 500;">@lang('email-confirmation.activate_button')</a>
      <p>@lang('email-confirmation.copy_paste')<br><a href="{{$data['url']}}" style="text-decoration: none;color: #346bff;">{{$data['url']}}</a></p>

  </div>

@endsection
