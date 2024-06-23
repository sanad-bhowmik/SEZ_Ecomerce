<!doctype html>
<html @if(isRtl()) dir="rtl" class="rtl no-js" @else class="no-js" @endif lang="zxx">

    @php

    $adminColor = Modules\Appearance\Entities\AdminColor::where('is_active',1)->first();

    $popupContent = \Modules\FrontendCMS\Entities\SubscribeContent::find(2);
    $promotionbar = \Modules\FrontendCMS\Entities\SubscribeContent::find(3);

    $langs = app('langs');
    $currencies = app('currencies');

    $locale = app('general_setting')->language_code;
    $ship = app('general_setting')->country_name;
    if(\Session::has('locale')){
        $locale = \Session::get('locale');
    }

    if(auth()->check()){
        $locale = auth()->user()->lang_code;
    }
    $currency_code = getCurrencyCode();

    $carts = collect();
    $compares = 0;
    $wishlists = 0;
    if(auth()->check()){
        $carts = \App\Models\Cart::with('product.product.product','giftCard','product.product_variations.attribute', 'product.product_variations.attribute_value.color')->where('user_id',auth()->user()->id)->where('product_type', 'product')->whereHas('product',function($query){
            return $query->where('status', 1)->whereHas('product', function($q){
                return $q->activeSeller();
            });
        })->orWhere('product_type', 'gift_card')->where('user_id',auth()->user()->id)->whereHas('giftCard', function($query){
            return $query->where('status', 1);
        })->get();
        $compares = count(\App\Models\Compare::with('sellerProductSKU.product')->where('customer_id', auth()->user()->id)->whereHas('sellerProductSKU', function($query){
            return $query->where('status',1)->whereHas('product', function($q){
                return $q->activeSeller();
            });
        })->pluck('id'));
        $wishlists = count(\App\Models\Wishlist::where('user_id', auth()->user()->id)->pluck('id'));
    }else {
        $carts = \App\Models\Cart::with('product.product.product','giftCard','product.product_variations.attribute', 'product.product_variations.attribute_value.color')->where('session_id',session()->getId())->where('product_type', 'product')->whereHas('product',function($query){
            return $query->where('status', 1)->whereHas('product', function($q){
                return $q->activeSeller();
            });
        })->orWhere('product_type', 'gift_card')->where('session_id', session()->getId())->whereHas('giftCard', function($query){
            return $query->where('status', 1);
        })->get();

        if(\Session::has('compare')){
            $dataList = Session::get('compare');
            $collcets =  collect($dataList);

            $collcets =  $collcets->sortByDesc('product_type');
            $products = [];
            foreach($collcets as $collcet){
                $product_com = \Modules\Seller\Entities\SellerProductSKU::with('product')->where('id',$collcet['product_sku_id'])->whereHas('product', function($query){
                    return $query->activeSeller();
                })->pluck('id');
                if($product_com){
                    $products[] = $product_com;
                }
            }
            $compares = count($products);
        }

    }
    $items = 0;
    foreach($carts as $cart){
        $items += $cart->qty;
    }

    $regular_menus = Modules\Menu\Entities\Menu::with('elements.page','elements.childs','elements.childs.page')->where('menu_type', 'normal_menu')->where('menu_position','top_navbar')->whereIn('id',[1,2])->orderBy('id')->where('status', 1)->get();
    $topnavbar_left_menu = null;
    $topnavbar_right_menu = null;
    foreach ($regular_menus as $menu) {
        if($menu->name == 'Top Navbar left menu'){
            $topnavbar_left_menu = $menu;
        }
        elseif ($menu->name == 'Top Navbar right menu') {
            $topnavbar_right_menu = $menu;
        }
    }

    $top_bar = Modules\FrontendCMS\Entities\HomePageSection::where('section_name','top_bar')->first();
@endphp

@include('frontend.amazy.partials._head',['promotionbar' => $promotionbar])

<body>
    <!-- preloader  -->
    <!-- <div class="preloader" >
        <h3 data-text="Amazy..">Amazy..</h3>
    </div> -->
    <div class="preloader_setup" id="pre-loader">
        @include('backEnd.partials.preloader')
    </div>
    <!-- preloader:end  -->
    <!-- promotion_bar_wrapper::start  -->
    <!-- position-fixed>> add this class to use this  -->
    @php
        $promotionshow = true;
        if(Session::get('close_promotion')){
            $promotionshow = false;
        }
    @endphp
    @if($promotionshow && @$promotionbar->status)
    <div class="promotion_bar position-relative top-0 start-0 w-100 d-none d-lg-block">
        <a href="{{@$promotionbar->description}}" target="_blank" class="promotion_bar_wrapper d-flex align-items-center position-relative">
            <span class="close_promobar gj-cursor-pointer d-inline-flex align-items-center justify-content-center" id="promotion_close">
                <i class="ti-close"></i>
            </span>
        </a>
    </div>
    @endif
    <!-- promotion_bar_wrapper::end  -->

    <!-- HEADER::START -->
    <input type="hidden" id="url" value="{{url('/')}}">
    @php
        $base_url = url('/');
        $current_url = url()->current();
        $just_path = trim(str_replace($base_url,'',$current_url));
        $flash_deal = \Modules\Marketing\Entities\FlashDeal::where('status', 1)->first();
        $new_user_zone = \Modules\Marketing\Entities\NewUserZone::where('status', 1)->first();
    @endphp
    <input type="hidden" id="just_url" value="{{$just_path}}">

    <!-- HEADER::START -->
    <header class="amazcartui_header">
        <div id="sticky-header" class="header_area">
            @include('frontend.amazy.partials._submenu',[$compares])
            @include('frontend.amazy.partials._mainmenu')
            <!-- main_header_area  -->
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="mobile_menu d-block d-lg-none"></div>
                    </div>
                </div>
            </div>
            <div class="menu_search_popup">
                <form class="menu_search_popup_field" method="GET" id="search_form2">
                    <input type="text" class="category_box_input2" placeholder="{{ __('defaultTheme.search_your_item') }}" id="inlineFormInputGroup">
                    <button type="submit" id="search_button">
                        <i class="ti-search"></i>
                    </button>
                </form>
                <span class="search_close home6_search_hide">
                    <i class="fas fa-times"></i>
                </span>
                <div class="live-search">
                    <ul class="p-0" id="search_items2">
                        <li class="search_item" id="search_empty_list2">
                            
                        </li>
                        <li class="search_item" id="search_history2">
                            
                        </li>
                        <li class="search_item" id="tag_search2">
                            
                        </li>
                        <li class="search_item" id="category_search2">
                            
                        </li>
                        <li class="search_item" id="product_search2">
                            
                        </li>
                        <li class="search_item" id="seller_search2">
                            
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        @if(request()->is('gift-cards/*') || request()->is('product/*'))
            <div class="product_details_buttons d-md-none" id="cart_footer_mobile">
                
                @if(request()->is('product/*'))
                    @if(isModuleActive('MultiVendor'))
                        <a href="
                            @if ($product->seller->slug)
                                {{route('frontend.seller',$product->seller->slug)}}
                            @else
                                {{route('frontend.seller',base64_encode($product->seller->id))}}
                            @endif
                        " class="d-flex flex-column justify-content-center product_details_icon">
                            <i class="ti-save"></i>
                            <span>{{__('common.store')}}</span>
                        </a>
                    @else
                    <a href="{{url('/')}}" class="d-flex flex-column justify-content-center product_details_icon">
                        <i class="ti-home"></i>
                        <span>{{__('common.home')}}</span>
                    </a>
                    @endif
                    @if (@$product->stock_manage == 1 && @$product->skus->first()->product_stock >= @$product->product->minimum_order_qty || @$product->stock_manage == 0)
                        
                        <button type="button" class="product_details_button style1 buy_now_btn" data-id="{{$product->id}}" data-type="product">
                            <span>{{__('common.buy_now')}}</span>
                        </button>
                        
                        <button class="product_details_button add_to_cart_btn" type="button">{{__('common.add_to_cart')}}</button>
                    @else
                        <button type="button" class="product_details_button style1" disabled>
                            <span>{{__('defaultTheme.out_of_stock')}}</span>
                        </button>
                        <button type="button" class="product_details_button" disabled>{{__('defaultTheme.out_of_stock')}}</button>
                    @endif
                @else
                    
                    <button type="button" class="product_details_button style1 buy_now_btn" data-gift-card-id="{{ $card->id }}" data-seller="1" data-base-price="{{$base_price}}" data-shipping-method="1" data-type="gift_card">
                        <span>{{__('common.buy_now')}}</span>
                    </button>
                    
                    <button class="product_details_button add_gift_card_to_cart" type="button" data-gift-card-id="{{ $card->id }}" data-seller="1" data-base-price="{{$base_price}}" data-shipping-method="1" data-show="{{json_encode($showData)}}">{{__('common.add_to_cart')}}</button>
                @endif
            </div>

        @else
            <ul class="short_curt_icons">
                <li>
                    <a href="{{url('/')}}">
                        <div class="cart_singleIcon">
                            <i class="ti-home"></i>
                        </div>
                        <span>{{__('common.home')}}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/category') }}">
                        <div class="cart_singleIcon">
                            <i class="ti-align-justify"></i>
                        </div>
                        <span>{{__('common.category')}}</span>
                    </a>
                </li>
                <li>
                    <a class="position-relative" href="{{url('/cart')}}">
                        <div class="cart_singleIcon cart_singleIcon_cart d-flex align-items-center justify-content-center">
                            <i class="ti-shopping-cart"></i>
                        </div>
                        <span>{{__('common.cart')}} (<span class="cart_count_bottom">{{$items}}</span>)</span>
                    </a>
                </li>
                <li>
                    @if (isset($flash_deal))
                        <a class="position-relative" href="{{ route('frontend.flash-deal', $flash_deal->slug) }}">
                            <div class="cart_singleIcon">
                                <img class="mb_5" src="{{showImage('frontend/amazy/img/amaz_icon/deals_white.svg')}}" alt="">
                            </div>
                            <span>{{__('amazy.Daily Deals')}}</span>
                        </a>
                    @else
                        <a class="position-relative" href="{{url('/profile/notifications')}}">
                            <div class="cart_singleIcon">
                                <i class="ti-bell"></i>
                            </div>
                            <span>{{__('Notification')}}</span>
                        </a>
                    @endif
                </li>
                @guest
                    <li>
                        <a href="{{ url('/login') }}">
                            <div class="cart_singleIcon">
                                <i class="ti-user"></i>
                            </div>
                            <span>{{ __('defaultTheme.login') }}</span>
                        </a>
                    </li>
                @else
                    <li>
                        <a href="{{ route('frontend.dashboard') }}">
                            <div class="cart_singleIcon">
                                <i class="ti-user"></i>
                            </div>
                            <span>{{__('common.account')}}</span>
                        </a>
                    </li>
                @endguest
            </ul>
        @endif
            
        <link rel="stylesheet" href="{{asset("public/css/animate.min.css")}}">
        <link rel="stylesheet" href="{{asset("public/css/bootstrap.min.css")}}">
        <link rel="stylesheet" href="{{asset("public/css/style.css")}}">
        <link rel="stylesheet" href="{{asset("public/css/responsive.css")}}">

    </header>

    
    <!--/ HEADER::END -->
    <section style="background-color:#fff" class="offer-area pd-top-100 pd-bottom-90">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 align-self-center d-contents">
                    <div class="single-offer-wrap" style="height: 421px;background-color: var(--main-color);" >
                        <img class="bg-img" src="{{asset("public/images/SansTitre-1.png")}}" alt="img">
                        <div class="wrap-details">
                            <h2>SHOPPING </h2>
                            <h5>SHOPPING </h5>
                            
                            <a class="btn btn-white" style=" color: #010232;" href="{{route('frontend.welcome')}}">VOIR TOUT</a>
                        </div>

                    </div>
                </div>
                <div class="col-md-6 align-self-center">
                    <div class="single-offer-wrap" style="background-color: var(--heading-color);">
                        <img class="bg-img" src="{{asset("public/images/food1.png")}}" alt="img">
                        <div class="wrap-details">
                            <h5>FAST FOOD ET LIVRAISON RAPIDE</h5>
                            <a class="btn btn-white" style=" color: #010232;" href="onelink.to/k4np8j">Commandez</a>
                        </div>
                    </div>
                    <div class="single-offer-wrap" style="background-color: #FFEECC">
                        
                        <div class="bg-img"><img src="{{asset("public/images/service.png")}}" alt="img"></div>
                        <div class="overlay-gradient"></div>
                        <div class="wrap-details">
                            <h3 class="text-heading">SERVICE<span> </span></h3>
                            <a onclick="commingSoon()" class="btn btn-white" style=" color: #010232;" href="#">Commandez</a>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </section>



    <!-- FOOTER::END  -->
	@include('frontend.amazy.auth.partials._login_modal')
	<div id="cart_data_show_div">
		@include('frontend.amazy.partials._cart_details_submenu')
	</div>
	<div id="cart_success_modal_div">
		@include('frontend.amazy.partials._cart_success_modal')
	</div>
	<input type="hidden" id="login_check" value="@if(auth()->check()) 1 @else 0 @endif">
	<div class="add-product-to-cart-using-modal">
		
	</div>
	
	@include('frontend.amazy.partials._modals')
	
	<!-- UP_ICON  -->
	<div id="back-top" style="display: none;">
		<a title="Go to Top" href="#">
			<i class="ti-angle-up"></i>
		</a>
	</div>
	<!--/ UP_ICON -->
	<!-- facebook chat start -->
	@php
		$messanger_data = \Modules\GeneralSetting\Entities\FacebookMessage::first();
	@endphp
	@if($messanger_data->status == 1)
		@php echo $messanger_data->code; @endphp
	@endif
	<!-- facebook chat end -->
	
	@include('frontend.amazy.partials._script')
	@stack('scripts')
	@stack('wallet_scripts')
	
	</body>

    <script>

        function commingSoon() {
          alert("Comming Soon");
        }
        
        
        
        
     </script>
	
</html>




