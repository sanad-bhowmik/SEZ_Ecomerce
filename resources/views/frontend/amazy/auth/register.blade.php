@extends('frontend.amazy.auth.layouts.app')

@section('content')
<div class="amazy_login_area">
    <div class="amazy_login_area_left d-flex align-items-center justify-content-center">
        <div class="amazy_login_form">
            <a href="{{url('/')}}" class="logo mb_50 d-block">
                <img src="{{showImage(app('general_setting')->logo)}}" alt="">
            </a>
            <h3 class="m-0">{{__('auth.Sign Up')}}</h3>
            <p class="support_text">{{__('auth.See your growth and get consulting support!')}}</p>
            
            @if (app('general_setting')->google_status)
            <a href="{{url('/login/google')}}" class="google_logIn d-flex align-items-center justify-content-center">
                <img src="{{url('/')}}/public/frontend/amazy/img/svg/google_icon.svg" alt="">
                <h5 class="m-0 font_16 f_w_500">{{__('auth.Sign up with Google')}}</h5>
            </a>
            @endif
            @if (app('general_setting')->facebook_status)
            <a href="{{url('/login/facebook')}}" class="google_logIn d-flex align-items-center justify-content-center">
                <img src="{{url('/')}}/public/frontend/amazy/img/svg/facebook_icon.svg" alt="">
                <h5 class="m-0 font_16 f_w_500">{{__('auth.Sign up with Facebook')}}</h5>
            </a>
            @endif
            @if (app('general_setting')->twitter_status)
            <a href="{{url('/login/twitter')}}" class="google_logIn d-flex align-items-center justify-content-center">
                <img src="{{url('/')}}/public/frontend/amazy/img/svg/twitter_icon.svg" alt="">
                <h5 class="m-0 font_16 f_w_500">{{__('auth.Sign up with Twitter')}}</h5>
            </a>
            @endif
            @if (app('general_setting')->linkedin_status)
            <a href="{{url('/login/linkedin')}}" class="google_logIn d-flex align-items-center justify-content-center">
                <img src="{{url('/')}}/public/frontend/amazy/img/svg/linkedin_icon.svg" alt="">
                <h5 class="m-0 font_16 f_w_500">{{__('auth.Sign up with LinkedIn')}}</h5>
            </a>
            @endif

            <div class="form_sep2 d-flex align-items-center">
                <span class="sep_line flex-fill"></span>
                <span class="form_sep_text font_14 f_w_500 ">{{__('auth.or Sign up with Email or Phone')}}</span>
                <span class="sep_line flex-fill"></span>
            </div>
            <form action="{{ route('register') }}" method="POST" name="register" id="login_form" enctype="multipart/form-data">
                @csrf
    
                @if(!empty($row) && !empty($form_data))

                @else
                <div class="row">
                    <div class="col-12 mb_20">
                        <label class="primary_label2">{{__('common.first_name')}} <span>*</span> </label>
                        <input name="first_name" id="first_name" value="{{ old('first_name') }}" placeholder="{{ __('common.first_name') }}" onfocus="this.placeholder = ''" onblur="this.placeholder = '{{ __('common.first_name') }}'" class="primary_input3 radius_5px" type="text">
                        <span class="text-danger" >{{ $errors->first('first_name') }}</span>
                    </div>
                    <div class="col-12 mb_20">
                        <label class="primary_label2">{{__('common.last_name')}}</label>
                        <input name="last_name" id="last_name" value="{{ old('last_name') }}" placeholder="{{ __('common.last_name') }}" onfocus="this.placeholder = ''" onblur="this.placeholder = '{{ __('common.last_name') }}'" class="primary_input3 radius_5px" type="text">
                        <span class="text-danger" >{{ $errors->first('last_name') }}</span>
                    </div>
                    @if(isModuleActive('Otp') && otp_configuration('otp_activation_for_customer') || app('business_settings')->where('type', 'email_verification')->first()->status == 0)
                    <div class="col-12 mb_20">
                        <label class="primary_label2">{{__('common.email_or_phone')}} <span>*</span></label>
                        <input name="email" id="email" value="{{ old('email') }}" placeholder="{{ __('common.email_or_phone') }}" onfocus="this.placeholder = ''" onblur="this.placeholder = '{{ __('common.email_or_phone') }}'" class="primary_input3 radius_5px" type="text">
                        <span class="text-danger" >{{ $errors->first('email') }}</span>
                    </div>
                    @else
                    <div class="col-12 mb_20">
                        <label class="primary_label2">{{__('common.email')}} <span>*</span></label>
                        <input name="email" id="email" value="{{ old('email') }}" placeholder="{{ __('common.email') }}" onfocus="this.placeholder = ''" onblur="this.placeholder = '{{ __('common.email') }}'" class="primary_input3 radius_5px" type="text">
                        <span class="text-danger" >{{ $errors->first('email') }}</span>
                    </div>
                    @endif
                    <div class="col-12 mb_20">
                        <label for="referral_code" class="primary_label2">{{__('common.referral_code_(optional)')}}</label>
                        <input name="referral_code" id="referral_code" value="{{ old('referral_code') }}" placeholder="{{ __('common.referral_code') }}" onfocus="this.placeholder = ''" onblur="this.placeholder = '{{ __('common.referral_code') }}'" class="primary_input3 radius_5px" type="text">
                        <span class="text-danger" >{{ $errors->first('referral_code') }}</span>
                    </div>
                    <div class="col-12 mb_20">
                        <label class="primary_label2">{{ __('common.password') }} <span>*</span></label>
                        <input name="password" id="password" placeholder="{{__('amazy.Min. 8 Character')}}" onfocus="this.placeholder = ''" onblur="this.placeholder = '{{__('amazy.Min. 8 Character')}}'" class="primary_input3 radius_5px" type="password">
                        <span class="text-danger" >{{ $errors->first('password') }}</span>
                    </div>
                    <div class="col-12 mb_20">
                        <label class="primary_label2" for="password-confirm">{{ __('common.confirm_password') }} <span>*</span></label>
                        <input name="password_confirmation" id="password-confirm" placeholder="{{__('amazy.Min. 8 Character')}}" onfocus="this.placeholder = ''" onblur="this.placeholder = '{{__('amazy.Min. 8 Character')}}'" class="primary_input3 radius_5px" type="password">
                    </div>
                    @if(env('NOCAPTCHA_FOR_REG') == "true")
                    <div class="col-12 mb_20">
                        @if(env('NOCAPTCHA_INVISIBLE') != "true")
                        <div class="g-recaptcha" data-callback="callback" data-sitekey="{{env('NOCAPTCHA_SITEKEY')}}"></div>
                        @endif
                        <span class="text-danger" >{{ $errors->first('g-recaptcha-response') }}</span>
                    </div>
                    @endif
                    <div class="col-12">
                        @if(env('NOCAPTCHA_INVISIBLE') == "true")
                        <button type="button" class="g-recaptcha amaz_primary_btn style2 radius_5px  w-100 text-uppercase  text-center mb_25" data-sitekey="{{env('NOCAPTCHA_SITEKEY')}}" data-size="invisible" data-callback="onSubmit">{{__('auth.Sign Up')}}</button>
                        @else
                        <button class="amaz_primary_btn style2 radius_5px  w-100 text-uppercase  text-center mb_25" id="sign_in_btn">{{__('auth.Sign Up')}}</button>
                        @endif
                    </div>
                    <div class="col-12">
                        <p class="sign_up_text">{{__('auth.Already have an Account?')}}  <a href="{{url('/login')}}">{{__('auth.Sign In')}}</a></p>
                    </div>
                </div>
                @endif
            </form>
        </div>
    </div>
    <div class="amazy_login_area_right d-flex align-items-center justify-content-center">
        <div class="amazy_login_area_right_inner d-flex align-items-center justify-content-center flex-column">
            <div class="thumb">
                <img class="img-fluid" src="{{ showImage($loginPageInfo->cover_img) }}" alt="">
            </div>
            <div class="login_text d-flex align-items-center justify-content-center flex-column text-center">
                <h4>{{ isset($loginPageInfo->title)? $loginPageInfo->title:'' }}</h4>
                <p class="m-0">{{ isset($loginPageInfo->sub_title)? $loginPageInfo->sub_title:'' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
    function onSubmit(token) {
        document.getElementById("login_form").submit();
    }
</script>
<script>
    (function($){
        "use strict";
        $(document).ready(function(){

            $(document).on('submit', '.register_form', function(event){

                if($("#policyCheck").prop('checked')!=true){
                    event.preventDefault();
                    toastr.error("{{__('common.please_agree_with_our_policy_privacy')}}","{{__('common.error')}}");
                    return false;
                }

            });

        });
    })(jQuery);
</script>
@endpush