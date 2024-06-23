<div class="header_topbar_area {{$top_bar->status == 0 ? 'd-none':''}}" id="top_bar">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="header__wrapper">
                    <!-- header__left__start  -->
                    <div class="header__left d-flex align-items-center">
                        @if($topnavbar_left_menu)
                            @foreach($topnavbar_left_menu->elements->where('has_parent',null) as $element)
                                @if($element->type == 'link' && strtolower($element->title) == 'playstore' || $element->type == 'link' && strtolower($element->title) == 'play store')
                                    <a href="{{ $element->link }}" class="single_top_lists d-flex align-items-center">
                                        <img src="{{showImage('frontend/amazy/img/amaz_icon/playstore.svg')}}" alt="">
                                        <span>{{textLimit($element->title,25)}}</span>
                                    </a>
                                @elseif($element->type == 'link' && strtolower($element->title) == 'appstore' || $element->type == 'link' && strtolower($element->title) == 'app store')
                                    <a href="{{ $element->link }}" class="single_top_lists d-flex align-items-center d-none d-md-inline-flex">
                                        <img src="{{showImage('frontend/amazy/img/amaz_icon/apple.svg')}}" alt="">
                                        <span>{{textLimit($element->title,25)}}</span>
                                    </a>

                                @elseif($element->type == 'page' && $element->page->slug == 'merchant' && isModuleActive('MultiVendor'))
                                    @if (app('business_settings')->where('category_type', 'vendor_configuration')->where('type',
                                    'Multi-Vendor System Activate')->first()->status)
                                        @if(auth()->check() && auth()->user()->role->type == 'customer')
                                            <a href="{{ route('frontend.merchant-register-step-first') }}" class="single_top_lists d-flex align-items-center d-none d-md-inline-flex">
                                                <span>{{__('defaultTheme.become a merchant')}}</span>
                                            </a>
                                        @elseif(!auth()->check())
                                            <a href="{{ route('frontend.merchant-register-step-first') }}" class="single_top_lists d-flex align-items-center d-none d-md-inline-flex">
                                                <span>{{__('defaultTheme.become a merchant')}}</span>
                                            </a>
                                        @else
                                            @continue
                                        @endif
                                    @else
                                        @continue
                                    @endif
                                @elseif($element->type == 'page' && $element->page->slug == 'track-order')
                                    <a href="{{ route('frontend.order.track') }}" class="single_top_lists d-flex align-items-center d-none d-md-inline-flex">
                                        <img src="{{showImage('frontend/amazy/img/svg/Track.svg')}}" alt="">
                                        <span>{{__('defaultTheme.track_your_order') }}</span>
                                    </a>
                                @elseif($element->type == 'page' && $element->page->slug == 'contact-us')
                                    <a href="{{ url('/contact-us') }}" class="single_top_lists d-flex align-items-center d-none d-md-inline-flex">
                                        <span>{{ __('defaultTheme.support')}}</span>
                                    </a>
                                @elseif($element->type == 'page' && $element->page->slug == 'compare')
                                    <a href="{{ url('/compare') }}" class="single_top_lists d-flex align-items-center d-none d-md-inline-flex">
                                        <img src="{{showImage('frontend/amazy/img/amaz_icon/compare.svg')}}" alt="">
                                        <span>{{ __('defaultTheme.compare') }}</span>
                                    </a>
                                @elseif($element->type == 'page' && $element->page->slug == 'my-wishlist')
                                    @if(auth()->check())
                                        @if(isModuleActive('MultiVendor') && auth()->user()->role->type != 'superadmin' || isModuleActive('MultiVendor') && auth()->user()->role->type != 'admin' || isModuleActive('MultiVendor') && auth()->user()->role->type != 'staff' || auth()->user()->role->type == 'customer')
                                            <a href="{{ route('frontend.my-wishlist') }}" class="single_top_lists d-flex align-items-center d-none d-md-inline-flex">
                                                <img src="{{showImage('frontend/amazy/img/amaz_icon/wishlist.svg')}}" alt="">
                                                <span>{{ __('defaultTheme.wishlist')}} (<span class="wishlist_count">{{$wishlists}}</span>)</span>
                                            </a>
                                        @else
                                            @continue
                                        @endif
                                    @else
                                        <a href="{{ route('frontend.my-wishlist') }}" class="single_top_lists d-flex align-items-center d-none d-md-inline-flex">
                                            <img src="{{showImage('frontend/amazy/img/amaz_icon/wishlist.svg')}}" alt="">
                                            <span>{{ __('defaultTheme.wishlist')}} (<span class="wishlist_count">{{$wishlists}}</span>)</span>
                                        </a>
                                    @endif
                                @elseif($element->type == 'page' && $element->page->slug == 'cart')
                                    <a href="{{ url('/cart') }}" class="single_top_lists d-flex align-items-center d-none d-md-inline-flex">
                                        <img src="{{showImage('frontend/amazy/img/amaz_icon/cart.svg')}}" alt="">
                                        <span>{{ __('common.cart') }} (<span class="cart_count_bottom">{{$items}}</span>)</span>
                                    </a>
                                @elseif($element->type == 'link')
                                    <a href="{{ $element->link }}" class="single_top_lists d-flex align-items-center d-none d-md-inline-flex">
                                        <span>{{ $element->title }}</span>
                                    </a>
                                @elseif($element->type == 'page' && $element->page->status == 1)
                                    @if(!isModuleActive('Lead') && $element->page->module == 'Lead')
                                        @continue
                                    @endif
                                    @if(!isModuleActive('MultiVendor') && $element->page->slug == 'merchant' || !isModuleActive('MultiVendor') && $element->page->module == 'MultiVendor')
                                        @continue
                                    @endif
                                    <a href="{{ url(@$element->page->slug) }}" class="single_top_lists d-flex align-items-center d-none d-md-inline-flex">
                                        <span>{{ ucfirst(textLimit($element->title, 25)) }}</span>
                                    </a>
                                @elseif($element->type == 'category')
                                    <a href="{{route('frontend.category-product',['slug' => $element->category->slug, 'item' =>'category'])}}" class="single_top_lists d-flex align-items-center d-none d-md-inline-flex">
                                        <span>{{ textLimit($element->title,25) }}</span>
                                    </a>
                                @elseif($element->type == 'brand')
                                    <a href="{{route('frontend.category-product',['slug' => $element->brand->slug, 'item' =>'brand'])}}" class="single_top_lists d-flex align-items-center d-none d-md-inline-flex">
                                        <span>{{ $element->title }}</span>
                                    </a>
                                @elseif($element->type == 'tag')
                                    <a href="{{route('frontend.category-product',['slug' => $element->tag->name, 'item' =>'tag'])}}" class="single_top_lists d-flex align-items-center d-none d-md-inline-flex">
                                        <span>{{ $element->title }}</span>
                                    </a>
                                @elseif($element->type == 'product' && @$element->product)
                                    <a href="{{singleProductURL(@$element->product->seller->slug, @$element->product->slug)}}" class="single_top_lists d-flex align-items-center d-none d-md-inline-flex">
                                        <span>{{ $element->title }}</span>
                                    </a>
                                @elseif($element->type == 'function' & $element->element_id == 1)
                                    <div class="single_top_lists position-relative  d-flex align-items-center shoping_language d-none d-md-inline-flex">
                                        <div class="">
                                            <div class="language_toggle_btn gj-cursor-pointer d-flex align-items-center gap_10 ">
                                                <span>{{strtoupper($locale)}}</span>
                                                <span class="vertical_line style2 d-none d-md-block"></span>
                                                <span>{{strtoupper($currency_code)}}</span>
                                                <i class="ti-angle-down"></i>
                                            </div>
                                            
                                                
                                            <div class="language_toggle_box position-absolute top-100 end-0 bg-white">
                                                <form action="{{route('frontend.locale')}}" method="POST">
                                                    @csrf
                                                    <div class="lag_select">
                                                        <span class="font_12 f_w_500 text-uppercase mb_10 d-block">{{ __('defaultTheme.language') }}</span>
                                                        <select class="amaz_select6 wide mb_20" name="lang">
                                                            @foreach($langs as $key => $lang)
                                                            <option {{ $locale==$lang->code?'selected':'' }} value="{{$lang->code}}">
                                                                {{$lang->native}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="lag_select">
                                                        <span class="font_12 f_w_500 text-uppercase mb_10 d-block">{{ __('defaultTheme.currency') }}</span>
                                                        <select class="amaz_select6 wide" name="currency">
                                                            @foreach($currencies as $key => $item)
                                                            <option {{$currency_code==$item->code?'selected':'' }}
                                                                value="{{$item->id}}">
                                                                ({{$item->symbol}}) {{$item->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <button type="submit" class="amaz_primary_btn style3 save_btn">{{ __('defaultTheme.save_change') }}</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if(!$loop->last)
                                    <span class="vertical_line style2 d-none d-lg-inline-flex"></span>
                                @endif
                            @endforeach
                            
                        @endif
                    </div>
                    <!-- header__left__end  -->
                    <!-- header__right_start  -->
                    <div class="header_top_area_right border-top-0 border-bottom-0">
                        @if(isset($topnavbar_right_menu))
                            @foreach($topnavbar_right_menu->elements->where('has_parent',null) as $element)
                                @if($element->type == 'page' && $element->page->slug == 'track-order')
                                    <a href="{{ route('frontend.order.track') }}" class="single_top_lists d-flex align-items-center d-none d-md-inline-flex">
                                        <img src="{{showImage('frontend/amazy/img/svg/Track.svg')}}" alt="">
                                        <span>{{__('defaultTheme.track_your_order') }}</span>
                                    </a>
                                @elseif($element->type == 'link' && strtolower($element->title) == 'playstore' || $element->type == 'link' && strtolower($element->title) == 'play store')
                                    <a href="{{ $element->link }}" class="single_top_lists d-flex align-items-center">
                                        <img src="{{showImage('frontend/amazy/img/amaz_icon/playstore.svg')}}" alt="">
                                        <span>{{textLimit($element->title,25)}}</span>
                                    </a>
                                @elseif($element->type == 'link' && strtolower($element->title) == 'appstore' || $element->type == 'link' && strtolower($element->title) == 'app store')
                                    <a href="{{ $element->link }}" class="single_top_lists d-flex align-items-center d-none d-md-inline-flex">
                                        <img src="{{showImage('frontend/amazy/img/amaz_icon/apple.svg')}}" alt="">
                                        <span>{{textLimit($element->title,25)}}</span>
                                    </a>
                                @elseif($element->type == 'page' && $element->page->slug == 'compare')
                                    <a href="{{ url('/compare') }}" class="single_top_lists d-flex align-items-center d-none d-md-inline-flex">
                                        <img src="{{showImage('frontend/amazy/img/amaz_icon/compare.svg')}}" alt="">
                                        <span>{{ __('defaultTheme.compare') }}</span>
                                    </a>
                                @elseif($element->type == 'page' && $element->page->slug == 'my-wishlist')
                                    @if(auth()->check())
                                        @if(isModuleActive('MultiVendor') && auth()->user()->role->type != 'superadmin' || isModuleActive('MultiVendor') && auth()->user()->role->type != 'admin' || isModuleActive('MultiVendor') && auth()->user()->role->type != 'staff' || auth()->user()->role->type == 'customer')
                                            <a href="{{ route('frontend.my-wishlist') }}" class="single_top_lists d-flex align-items-center d-none d-md-inline-flex">
                                                <img src="{{showImage('frontend/amazy/img/amaz_icon/wishlist.svg')}}" alt="">
                                                <span>{{ __('defaultTheme.wishlist')}} (<span class="wishlist_count">{{$wishlists}}</span>)</span>
                                            </a>
                                        @else
                                            @continue
                                        @endif
                                    @else
                                        <a href="{{ route('frontend.my-wishlist') }}" class="single_top_lists d-flex align-items-center d-none d-md-inline-flex">
                                            <img src="{{showImage('frontend/amazy/img/amaz_icon/wishlist.svg')}}" alt="">
                                            <span>{{ __('defaultTheme.wishlist')}} (<span class="wishlist_count">{{$wishlists}}</span>)</span>
                                        </a>
                                    @endif
                                @elseif($element->type == 'page' && $element->page->slug == 'cart')
                                    <a href="{{ url('/cart') }}" class="single_top_lists d-flex align-items-center d-none d-md-inline-flex">
                                        <img src="{{showImage('frontend/amazy/img/amaz_icon/cart.svg')}}" alt="">
                                        <span>{{ __('common.cart') }} (<span class="cart_count_bottom">{{$items}}</span>)</span>
                                    </a>
                                @elseif($element->type == 'link')
                                    <a href="{{ $element->link }}" class="single_top_lists d-flex align-items-center d-none d-md-inline-flex">
                                        <span>{{textLimit($element->title,25)}}</span>
                                    </a>
                                @elseif($element->type == 'page' && $element->page->status == 1)
                                    @if(!isModuleActive('Lead') && $element->page->module == 'Lead')
                                        @continue
                                    @endif
                                    @if(!isModuleActive('MultiVendor') && $element->page->slug == 'merchant' || !isModuleActive('MultiVendor') && $element->page->module == 'MultiVendor')
                                        @continue
                                    @endif
                                    <a href="{{ url(@$element->page->slug) }}" class="single_top_lists d-flex align-items-center d-none d-md-inline-flex">
                                        <span>{{ ucfirst(textLimit($element->title, 25)) }}</span>
                                    </a>

                                @elseif($element->type == 'category')
                                    <a href="{{route('frontend.category-product',['slug' => $element->category->slug, 'item' =>'category'])}}" class="single_top_lists d-flex align-items-center d-none d-md-inline-flex">
                                        <span>{{textLimit($element->title,25)}}</span>
                                    </a>
                                @elseif($element->type == 'brand')
                                    <a href="{{route('frontend.category-product',['slug' => $element->brand->slug, 'item' =>'brand'])}}" class="single_top_lists d-flex align-items-center d-none d-md-inline-flex">
                                        <span>{{textLimit($element->title,25)}}</span>
                                    </a>
                                @elseif($element->type == 'tag')
                                    <a href="{{route('frontend.category-product',['slug' => $element->tag->name, 'item' =>'tag'])}}" class="single_top_lists d-flex align-items-center d-none d-md-inline-flex">
                                        <span>{{textLimit($element->title,25)}}</span>
                                    </a>
                                @elseif($element->type == 'product' && @$element->product)
                                    <a href="{{singleProductURL(@$element->product->seller->slug, @$element->product->slug)}}" class="single_top_lists d-flex align-items-center d-none d-md-inline-flex">
                                        <span>{{textLimit($element->title,25)}}</span>
                                    </a>
                                @elseif($element->type == 'function' & $element->element_id == 1)
                                    <div class="single_top_lists position-relative  d-flex align-items-center shoping_language d-none d-md-inline-flex">
                                        <div class="">
                                            <div class="language_toggle_btn gj-cursor-pointer d-flex align-items-center gap_10 ">
                                                <span>{{strtoupper($locale)}}</span>
                                                <span class="vertical_line style2 d-none d-md-block"></span>
                                                <span>{{strtoupper($currency_code)}}</span>
                                                <i class="ti-angle-down"></i>
                                            </div>
                                            <div class="language_toggle_box position-absolute top-100 end-0 bg-white">
                                                <form action="{{route('frontend.locale')}}" method="POST">
                                                    @csrf
                                                    <div class="lag_select">
                                                        <span class="font_12 f_w_500 text-uppercase mb_10 d-block">{{ __('defaultTheme.language') }}</span>
                                                        <select class="amaz_select6 wide mb_20" name="lang">
                                                            @foreach($langs as $key => $lang)
                                                            <option {{ $locale==$lang->code?'selected':'' }} value="{{$lang->code}}">
                                                                {{$lang->native}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="lag_select">
                                                        <span class="font_12 f_w_500 text-uppercase mb_10 d-block">{{ __('defaultTheme.currency') }}</span>
                                                        <select class="amaz_select6 wide" name="currency">
                                                            @foreach($currencies as $key => $item)
                                                            <option {{$currency_code==$item->code?'selected':'' }}
                                                                value="{{$item->id}}">
                                                                ({{$item->symbol}}) {{$item->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <button type="submit" class="amaz_primary_btn style3 save_btn">{{ __('defaultTheme.save_change') }}</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if(!$loop->last)
                                    <span class="vertical_line style2 d-none d-md-block"></span>
                                @endif
                            @endforeach
                        @endif
                        
                    </div>
                    <!-- header__right_end  -->
                </div>
            </div>
        </div>
    </div>
</div>