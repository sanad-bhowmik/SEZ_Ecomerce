<div class="col-lg-3  col-md-6">
    <div class="footer_widget">
        <div class="footer_title">
            <h3>Subscribe Newsletter</h3>
        </div>
        <div class="subcribe-form mb_20 theme_mailChimp2" id="mc_embed_signup">
            <form id="subscriptionForm" method="" class="subscription relative">
                <input name="email" id="subscription_email_id" class="form-control"
                    placeholder="{{ __('defaultTheme.enter_email_address') }}" type="email">
                <div class="message_div d-none">

                </div>
                <button class="" id="subscribeBtn">{{ __('defaultTheme.subscribe') }}</button>
                <div class="info"></div>
            </form>
        </div>
        <div class="social__Links">
            @if(app('general_setting')->twitter)
            <a href="{{ app('general_setting')->twitter }}">
                <i class="fab fa-twitter"></i>
            </a>
            @endif
            @if(app('general_setting')->linkedin)
            <a href="{{ app('general_setting')->linkedin }}">
                <i class="fab fa-linkedin-in"></i>
            </a>
            @endif
            @if(app('general_setting')->instagram)
            <a href="{{ app('general_setting')->instagram }}">
                <i class="fab fa-instagram"></i>
            </a>
            @endif
            @if(app('general_setting')->facebook)
            <a href="{{ app('general_setting')->facebook }}">
                <i class="fab fa-facebook"></i>
            </a>
            @endif
        </div>
    </div>
</div>
