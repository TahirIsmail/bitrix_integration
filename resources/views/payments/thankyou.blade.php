@extends('layouts.megamenu-master')

@section('header')
@endsection

@section('content')
    <!-- Facebook Pixel Code -->
    <script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window,document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
     fbq('init', '5625251687532850'); 
    fbq('track', 'PageView');
    </script>
    <noscript>
     <img height="1" width="1" 
    src="https://www.facebook.com/tr?id=5625251687532850&ev=PageView
    &noscript=1"/>
    </noscript>
    <!-- End Facebook Pixel Code -->

    <!-- Modules -->
    <div class="wizard_bg mt-0">
        <div class="container">
            <!-- Thanks -->
            <div class="container form_bg" data-aos="zoom-in">
                <div class="text-center thanks_msg">
                    <p class="img">
                        <img src="{{ asset('/assets/img/thank-tick.png')}}" alt="" title="">
                    </p>
                    <h3>Thank You!</h3>
                    <p>Your submission has been received.</p>
                </div>
            </div>
            <!-- Thanks end-->
        </div>
    </div>
    <div class="space_botm"></div>
    <!-- Modules end -->



@endsection

@section('footer')

@endsection
