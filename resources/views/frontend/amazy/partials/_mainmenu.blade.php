<div class="header_top_area">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="header__wrapper">
                    <!-- header__left__start  -->
                    <div class="header__left d-flex align-items-center">
                        <div class="logo_img">
                            <a href="{{ url('/') }}">
                                <img src="{{ showImage(app('general_setting')->logo) }}" alt="">
                            </a>
                        </div>
                    </div>
                    <!-- header__left__end  -->
                    <div class="header_middle d-flex">
                        <form method="GET" id="search_form">
                            <div class="input-group header_search_field ">
                                <div class="input-group-prepend">
                                    <button class="btn input-group-append" id="search_button"> <i class="ti-search"></i> </button>
                                </div>
                                <input type="text" class="form-control category_box_input" id="inlineFormInputGroup" placeholder="{{ __('defaultTheme.search_your_item') }}">
                                
                                <div class="live-search">
                                    <ul class="p-0" id="search_items">
                                        <li class="search_item" id="search_empty_list">
                                            
                                        </li>
                                        <li class="search_item" id="search_history">
                                            
                                        </li>
                                        <li class="search_item" id="tag_search">
                                            
                                        </li>
                                        <li class="search_item" id="category_search">
                                            
                                        </li>
                                        <li class="search_item" id="product_search">
                                            
                                        </li>
                                        <li class="search_item" id="seller_search">
                                            
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </form>

                    </div>
                    <!-- header__right_start  -->
                    <div class="header_top_area_right">
                        <div class="wish_cart">
                            <div class="single_wishcart_lists" >
                                <div class="icon d-inline-block lh-1">
                                    <img src="{{url('/')}}/public/frontend/amazy/img/amaz_icon/user.svg" alt="">
                                </div>
                                @guest
                                    <span class="d-inline-block lh-1 ">
                                        <a href="{{url('/login')}}">{{ __('defaultTheme.login') }}</a>
                                        <a href="{{url('/register')}}">/ {{ __('defaultTheme.register') }}</a>
                                    </span>
                                @else
                                    <span class="d-inline-block lh-1 ">
                                        @if (auth()->check() && auth()->user()->role->type == "superadmin" || auth()->check() && auth()->user()->role->type == "admin" || auth()->check() && auth()->user()->role->type == "staff")
                                            <a href="{{ route('admin.dashboard') }}">{{ __('common.dashboard') }}</a>
                                        @elseif (auth()->check() && auth()->user()->role->type == "seller" && isModuleActive('MultiVendor'))
                                            <a href="{{ route('seller.dashboard') }}">{{ __('common.dashboard') }}</a>
                                        @elseif (auth()->check() && auth()->user()->role->type == "affiliate")
                                            <a href="{{ route('affiliate.my_affiliate.index') }}">{{ __('common.dashboard') }}</a>
                                        @else
                                            <a href="{{ route('frontend.dashboard') }}">{{ __('common.dashboard') }}</a>
                                        @endif

                                        <a href="{{ route('logout') }}" class="log_out">/ {{ __('defaultTheme.log_out') }}</a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </span>
                                @endguest
                            </div>
                        </div>
                        <div class="wish_cart_mobile">
                            <div class="home6_search_toggle ">
                                <i class="ti-search"></i>
                            </div>
                        </div>
                    </div>
                    <!-- header__right_end  -->
                </div>
            </div>
        </div>
    </div>
</div>