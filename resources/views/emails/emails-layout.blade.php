<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!--  <link rel="stylesheet" href="main.css"> -->

    <title>American Kryptos Bank</title>
</head>
<body>

    <div class="email-body" style="display: block;background-color: #E8F3F9;height: 100%;font-size: 13px;padding: 3rem 0.5rem;box-sizing: border-box;font-family: &quot;Open Sans&quot;, sans-serif;color: #545454;">

        <div class="email-container" style="background-color: white;max-width: 520px;min-width: 340px;margin: 0 auto;">
            <div class="email-header clearfix" style="overflow: hidden;display: block;color: white;background-color: #303392;padding: 1rem 3rem;">
                <div class="float-left" style="float: left;">
                    <img src="https://americankryptosbank.com/img/cb-img/coinbank-logo-light.png" alt="American Kryptos Bank Logo" class="header__logo" style="max-height: 40px;">
                </div>
                <div class="float-right" style="float: right;">
                    <div class="social-container" style="font-size: 0.8rem;text-align: right;">
                        <span style="display: inline-block;margin-bottom: 3px;">@lang('emails-layout.contact_us')</span>
                        <div class="social-icons">
                            <a href="https://www.facebook.com/americankryptosbank/" style="text-decoration: none;color: #346bff;"><img src="{{asset('img/emails/fb.png')}}" alt="Facebook" title="Facebook"></a>
                            <a href="https://twitter.com/akryptosbankven" style="text-decoration: none;color: #346bff;"><img src="{{asset('img/emails/tw.png')}}" alt="Twitter" title="Twitter"></a>
                            <a href="https://www.instagram.com/akryptosbankven/" style="text-decoration: none;color: #346bff;"><img src="{{asset('img/emails/ig.png')}}" alt="Instagram" title="Instagram"></a>
                            <a href="https://www.linkedin.com/company/coin-bank-usa/" style="text-decoration: none;color: #346bff;"><img src="{{asset('img/emails/lk.png')}}" alt="LinkedIn" title="LinkedIn"></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="email-subheader" style="padding: 0.5rem 3rem;background-color: #f1f1f1;color: #545454;">@lang('emails-layout.hello'), <strong>{{$data['name']}}</strong></div>

              @yield('content')

            <div class="email-footer" style="text-align: center;font-size: 0.7rem;color: #afafaf;padding: 0.5rem 3rem;">
                <div>American Kryptos Bank</div>
                <div>+1 (786) 245-8123</div>
                <div>3517 NW 115TH AVE</div>
                <div>DORAL FL 33178</div>
                <div><a href="mailto:csupport@americankryptosbank.com" style="text-decoration: none;color: #7c7c7c;">csupport@americankryptosbank.com</a></div>
            </div>
        </div>

    </div>

</body>
</html>
