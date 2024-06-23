@extends('frontend.amazy.layouts.app')
@section('content')
    <!-- home_banner::start  -->
    @php
        $headers = \Modules\Appearance\Entities\Header::all();
    @endphp
    <x-slider-component :headers="$headers" />
    <!-- home_banner::end  -->
    @php
        $best_deal = $widgets->where('section_name', 'best_deals')->first();
    @endphp
    <div id="best_deals" class="amaz_section section_spacing {{ $best_deal->status == 0 ? 'd-none' : '' }}">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section__title d-flex align-items-center gap-3 mb_30 flex-wrap">
                        <h3 id="best_deals_title" class="m-0 flex-fill">{{ $best_deal->title }}</h3>
                        <a href="{{ route('frontend.category-product', ['slug' => $best_deal->section_name, 'item' => 'product']) }}"
                            class="title_link d-flex align-items-center lh-1">
                            <span class="title_text">{{ __('common.view_all') }}</span>
                            <span class="title_icon">
                                <i class="fas fa-chevron-right"></i>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="row  ">
                <div class="col-12">
                    <div class="trending_product_active owl-carousel">
                        @foreach ($best_deal->getProductByQuery() as $key => $product)
                            <div class="product_widget5 mb_30">
                                @php
                                    if (@$product->thum_img != null) {
                                        $thumbnail = showImage(@$product->thum_img);
                                    } else {
                                        $thumbnail = showImage(@$product->product->thumbnail_image_source);
                                    }
                                    
                                    $price_qty = getProductDiscountedPrice(@$product);
                                    $showData = [
                                        'name' => @$product->product->product_name,
                                        'url' => singleProductURL(@$product->product->seller->slug, @$product->product->slug),
                                        'price' => $price_qty,
                                        'thumbnail' => $thumbnail,
                                    ];
                                @endphp
                                <div class="product_thumb_upper">
                                    <a href="{{ singleProductURL($product->seller->slug, $product->slug) }}" class="thumb">
                                        <img data-src="{{ $thumbnail }}" src="{{ showImage(themeDefaultImg()) }}"
                                            class="lazyload" alt="">
                                    </a>
                                    <div class="product_action">
                                        <a href="" class="addToCompareFromThumnail"
                                            data-producttype="{{ @$product->product->product_type }}"
                                            data-seller={{ $product->user_id }}
                                            data-product-sku={{ @$product->skus->first()->id }}
                                            data-product-id={{ $product->id }}>
                                            <i class="ti-control-shuffle"></i>
                                        </a>
                                        <a href=""
                                            class="add_to_wishlist {{ $product->is_wishlist() == 1 ? 'is_wishlist' : '' }}"
                                            id="wishlistbtn_{{ $product->id }}" data-product_id="{{ $product->id }}"
                                            data-seller_id="{{ $product->user_id }}">
                                            <i class="ti-heart"></i>
                                        </a>
                                        <a href="" class="quickView" data-product_id="{{ $product->id }}"
                                            data-type="product">
                                            <i class="ti-eye"></i>
                                        </a>
                                    </div>
                                    @if ($product->hasDeal)
                                        @if ($product->hasDeal->discount > 0)
                                            <span class="badge_1">
                                                @if ($product->hasDeal->discount_type == 0)
                                                    {{ $product->hasDeal->discount }} % off
                                                @else
                                                    {{ single_price($product->hasDeal->discount) }} off
                                                @endif
                                            </span>
                                        @endif
                                    @else
                                        @if ($product->hasDiscount == 'yes')
                                            @if ($product->discount > 0)
                                                <span class="badge_1">
                                                    @if ($product->discount_type == 0)
                                                        {{ $product->discount }} % off
                                                    @else
                                                        {{ single_price($product->discount) }} off
                                                    @endif
                                                </span>
                                            @endif
                                        @endif
                                    @endif
                                </div>
                                <div class="product__meta text-center">
                                    <span
                                        class="product_banding ">{{ $product->brand->name ?? __('amazy.no_brand') }}</span>
                                    <a href="{{ singleProductURL($product->seller->slug, $product->slug) }}">
                                        <h4>
                                            @if ($product->product_name != null)
                                                {{ textLimit(@$product->product_name, 44) }}
                                            @else
                                                {{ textLimit(@$product->product->product_name, 44) }} @endif
                                        </h4>
                                    </a>
                                    <div class="stars justify-content-center">
                                        @php
                                            $reviews = @$product->reviews->where('status', 1)->pluck('rating');
                                            if (count($reviews) > 0) {
                                                $value = 0;
                                                $rating = 0;
                                                foreach ($reviews as $review) {
                                                    $value += $review;
                                                }
                                                $rating = $value / count($reviews);
                                                $total_review = count($reviews);
                                            } else {
                                                $rating = 0;
                                                $total_review = 0;
                                            }
                                        @endphp
                                        <x-rating :rating="$rating" />
                                    </div>
                                    <div class="product_prise">
                                        <p>
                                            <span>
                                                @if (getProductwitoutDiscountPrice(@$product) != single_price(0))
                                                    {{ getProductwitoutDiscountPrice(@$product) }}
                                                @endif
                                            </span>
                                            {{ getProductDiscountedPrice($product) }}
                                        </p>
                                        <a class="add_cart add_to_cart addToCartFromThumnail"
                                            data-producttype="{{ @$product->product->product_type }}"
                                            data-seller={{ $product->user_id }}
                                            data-product-sku={{ @$product->skus->first()->id }}
                                            @if (@$product->hasDeal) data-base-price={{ selling_price(@$product->skus->first()->selling_price, @$product->hasDeal->discount_type, @$product->hasDeal->discount) }} 
                                        @else
                                        @if (@$product->hasDiscount == 'yes')
                                            data-base-price={{ selling_price(@$product->skus->first()->selling_price, @$product->discount_type, @$product->discount) }} 
                                        @else
                                            data-base-price={{ @$product->skus->first()->selling_price }} @endif
                                            @endif
                                            data-shipping-method=0
                                            data-product-id={{ $product->id }}
                                            data-stock_manage="{{ $product->stock_manage }}"
                                            data-stock="{{ @$product->skus->first()->product_stock }}"
                                            data-min_qty="{{ $product->product->minimum_order_qty }}"
                                            data-prod_info= "{{ json_encode($showData) }}"
                                            >Add To Cart</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- amaz_section::start  -->
    @php
        $feature_categories = $widgets->where('section_name', 'feature_categories')->first();
    @endphp
    <div id="feature_categories" class="amaz_section {{ $feature_categories->status == 0 ? 'd-none' : '' }}">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section__title d-flex align-items-center gap-3 mb_30 flex-wrap ">
                        <h3 id="feature_categories_title" class="m-0 flex-fill">{{ $feature_categories->title }}</h3>
                        <a href="{{ url('/category') }}" class="title_link d-flex align-items-center lh-1">
                            <span class="title_text">{{ __('common.view_all') }}</span>
                            <span class="title_icon">
                                <i class="fas fa-chevron-right"></i>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach ($feature_categories->getCategoryByQuery() as $key => $category)
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="amaz_home_cartBox amaz_cat_bg1 d-flex justify-content-between mb_30">
                            <div class="img_box">
                                <img class="lazyload" src="{{ showImage(themeDefaultImg()) }}"
                                    data-src="{{ showImage(@$category->categoryImage->image ? @$category->categoryImage->image : 'frontend/default/img/default_category.png') }}"
                                    alt="">
                            </div>
                            <div class="amazcat_text_box">
                                <h4>
                                    <a>{{ textLimit($category->name, 25) }}</a>
                                </h4>
                                <p class="lh-1">{{ $category->sellerProducts->count() }} Products</p>
                                <a class="shop_now_text"
                                    href="{{ route('frontend.category-product', ['slug' => $category->slug, 'item' => 'category']) }}">Shop
                                    now »</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- amaz_section::end  -->
    <!-- new  -->
    <!-- new  -->
    <!-- amaz_section::start  -->

    <div class="amaz_section section_spacing2">

        <div class="container">
            <!-- start cats -->
            <div class="row">
                <div class="col-12">
                    <div class="section__title d-flex align-items-center gap-3 mb_30 flex-wrap">
                        <h3 id="best_deals_title" class="m-0 flex-fill">Électroménager, TV & Audio</h3>
                        <a href="#" class="title_link d-flex align-items-center lh-1">
                            <span class="title_text"></span>
                            <span class="title_icon">
                                {{-- <i class="fas fa-chevron-right"></i> --}}
                            </span>
                        </a>
                    </div>
                </div>
            </div>


            <div class="row">
                @php
                    $sellers = DB::table('seller_products')
                        ->join('category_product', 'seller_products.product_id', 'category_product.product_id')
                        ->join('users', 'users.id', 'seller_products.user_id')
                        ->leftJoin('seller_accounts', 'seller_accounts.user_id', 'users.id')
                        ->whereRaw('category_product.category_id in (25,26,2,6,29) ')
                        ->selectRaw('distinct users.slug,users.photo,seller_accounts.*')
                        ->get();
                    //->toSql();
                    //
                    
                    //dd($sellers);
                @endphp
                @forelse($sellers as $seller)
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="amaz_home_cartBox amaz_cat_bg1 d-flex justify-content-between mb_30">
                            <div class="img_box">
                                <img class="lazyload" src="{{ showImage(themeDefaultImg()) }}"
                                    data-src="{{ showImage(@$seller->photo ? @$seller->photo : 'frontend/default/img/default_category.png') }}"
                                    alt="">
                            </div>
                            <div class="amazcat_text_box">
                                <h4>
                                    <a
                                        href="{{ route('frontend.seller', $seller->slug) }}">{{ $seller->seller_shop_display_name ? $seller->seller_shop_display_name : $seller->slug }}</a>
                                </h4>

                                <a class="shop_now_text" href="{{ route('frontend.seller', $seller->slug) }}">Shop now »</a>
                            </div>
                        </div>
                    </div>





                @empty
                    <p> No Shops found </p>
                @endforelse
            </div>
            <!-- end cats -->
        </div>
    </div>

    <!-- start cats -->
    <div class="amaz_section section_spacing2">

        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section__title d-flex align-items-center gap-3 mb_30 flex-wrap">
                        <h3 id="best_deals_title" class="m-0 flex-fill">MODE:</h3>
                        <a href="#" class="title_link d-flex align-items-center lh-1">
                            <span class="title_text"></span>
                            <span class="title_icon">
                                {{-- <i class="fas fa-chevron-right"></i> --}}
                            </span>
                        </a>
                    </div>
                </div>
            </div>


            <div class="row">
                @php
                    $sellers = DB::table('seller_products')
                        ->join('category_product', 'seller_products.product_id', 'category_product.product_id')
                        ->join('users', 'users.id', 'seller_products.user_id')
                        ->leftJoin('seller_accounts', 'seller_accounts.user_id', 'users.id')
                        ->whereRaw('category_product.category_id in (30,31,32,33,34,35) ')
                        ->selectRaw('distinct users.slug,users.photo,seller_accounts.*')
                        ->get();
                    //->toSql();
                    //
                    
                    //dd($sellers);
                @endphp
                @forelse($sellers as $seller)
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="amaz_home_cartBox amaz_cat_bg1 d-flex justify-content-between mb_30">
                            <div class="img_box">
                                <img class="lazyload" src="{{ showImage(themeDefaultImg()) }}"
                                    data-src="{{ showImage(@$seller->photo ? @$seller->photo : 'frontend/default/img/default_category.png') }}"
                                    alt="">
                            </div>
                            <div class="amazcat_text_box">
                                <h4>
                                    <a
                                        href="{{ route('frontend.seller', $seller->slug) }}">{{ $seller->seller_shop_display_name ? $seller->seller_shop_display_name : $seller->slug }}</a>
                                </h4>

                                <a class="shop_now_text" href="{{ route('frontend.seller', $seller->slug) }}">Shop now
                                    »</a>
                            </div>
                        </div>
                    </div>

                @empty
                    <p> No Shops found </p>
                @endforelse
            </div>

        </div>
    </div>
    <!-- end cats -->

    <!-- start cats -->
    <div class="amaz_section section_spacing2">

        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section__title d-flex align-items-center gap-3 mb_30 flex-wrap">
                        <h3 id="best_deals_title" class="m-0 flex-fill">Santé & Beauté:</h3>
                        <a href="#" class="title_link d-flex align-items-center lh-1">
                            <span class="title_text"></span>
                            <span class="title_icon">
                                {{-- <i class="fas fa-chevron-right"></i> --}}
                            </span>
                        </a>
                    </div>
                </div>
            </div>


            <div class="row">
                @php
                    $sellers = DB::table('seller_products')
                        ->join('category_product', 'seller_products.product_id', 'category_product.product_id')
                        ->join('users', 'users.id', 'seller_products.user_id')
                        ->leftJoin('seller_accounts', 'seller_accounts.user_id', 'users.id')
                        ->whereRaw('category_product.category_id in (17,36,37) ')
                        ->selectRaw('distinct users.slug,users.photo,seller_accounts.*')
                        ->get();
                    //->toSql();
                    //
                    
                    //dd($sellers);
                @endphp
                @forelse($sellers as $seller)
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="amaz_home_cartBox amaz_cat_bg1 d-flex justify-content-between mb_30">
                            <div class="img_box">
                                <img class="lazyload" src="{{ showImage(themeDefaultImg()) }}"
                                    data-src="{{ showImage(@$seller->photo ? @$seller->photo : 'frontend/default/img/default_category.png') }}"
                                    alt="">
                            </div>
                            <div class="amazcat_text_box">
                                <h4>
                                    <a
                                        href="{{ route('frontend.seller', $seller->slug) }}">{{ $seller->seller_shop_display_name ? $seller->seller_shop_display_name : $seller->slug }}</a>
                                </h4>

                                <a class="shop_now_text" href="{{ route('frontend.seller', $seller->slug) }}">Shop now
                                    »</a>
                            </div>
                        </div>
                    </div>

                @empty
                    <p> No Shops found </p>
                @endforelse
            </div>
        </div>
    </div>
    <!-- end cats -->
    <!-- start cats -->
    <div class="amaz_section section_spacing2">

        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section__title d-flex align-items-center gap-3 mb_30 flex-wrap">
                        <h3 id="best_deals_title" class="m-0 flex-fill">Téléphonie & Accessoires:</h3>
                        <a href="#" class="title_link d-flex align-items-center lh-1">
                            <span class="title_text"></span>
                            <span class="title_icon">
                                {{-- <i class="fas fa-chevron-right"></i> --}}
                            </span>
                        </a>
                    </div>
                </div>
            </div>


            <div class="row">
                @php
                    $sellers = DB::table('seller_products')
                        ->join('category_product', 'seller_products.product_id', 'category_product.product_id')
                        ->join('users', 'users.id', 'seller_products.user_id')
                        ->leftJoin('seller_accounts', 'seller_accounts.user_id', 'users.id')
                        ->whereRaw('category_product.category_id in (18,38,39) ')
                        ->selectRaw('distinct users.slug,users.photo,seller_accounts.*')
                        ->get();
                    //->toSql();
                    //
                    
                    //dd($sellers);
                @endphp
                @forelse($sellers as $seller)
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="amaz_home_cartBox amaz_cat_bg1 d-flex justify-content-between mb_30">
                            <div class="img_box">
                                <img class="lazyload" src="{{ showImage(themeDefaultImg()) }}"
                                    data-src="{{ showImage(@$seller->photo ? @$seller->photo : 'frontend/default/img/default_category.png') }}"
                                    alt="">
                            </div>
                            <div class="amazcat_text_box">
                                <h4>
                                    <a
                                        href="{{ route('frontend.seller', $seller->slug) }}">{{ $seller->seller_shop_display_name ? $seller->seller_shop_display_name : $seller->slug }}</a>
                                </h4>

                                <a class="shop_now_text" href="{{ route('frontend.seller', $seller->slug) }}">Shop now
                                    »</a>
                            </div>
                        </div>
                    </div>

                @empty
                    <p> No Shops found </p>
                @endforelse
            </div>
        </div>
    </div>
    <!-- end cats -->

    <!-- start cats -->
    <div class="amaz_section section_spacing2">

        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section__title d-flex align-items-center gap-3 mb_30 flex-wrap">
                        <h3 id="best_deals_title" class="m-0 flex-fill">Article de Sport:</h3>
                        <a href="#" class="title_link d-flex align-items-center lh-1">
                            <span class="title_text"></span>
                            <span class="title_icon">
                                {{-- <i class="fas fa-chevron-right"></i> --}}
                            </span>
                        </a>
                    </div>
                </div>
            </div>


            <div class="row">
                @php
                    $sellers = DB::table('seller_products')
                        ->join('category_product', 'seller_products.product_id', 'category_product.product_id')
                        ->join('users', 'users.id', 'seller_products.user_id')
                        ->leftJoin('seller_accounts', 'seller_accounts.user_id', 'users.id')
                        ->whereRaw('category_product.category_id in (40,41) ')
                        ->selectRaw('distinct users.slug,users.photo,seller_accounts.*')
                        ->get();
                    //->toSql();
                    //
                    
                    //dd($sellers);
                @endphp
                @forelse($sellers as $seller)
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="amaz_home_cartBox amaz_cat_bg1 d-flex justify-content-between mb_30">
                            <div class="img_box">
                                <img class="lazyload" src="{{ showImage(themeDefaultImg()) }}"
                                    data-src="{{ showImage(@$seller->photo ? @$seller->photo : 'frontend/default/img/default_category.png') }}"
                                    alt="">
                            </div>
                            <div class="amazcat_text_box">
                                <h4>
                                    <a
                                        href="{{ route('frontend.seller', $seller->slug) }}">{{ $seller->seller_shop_display_name ? $seller->seller_shop_display_name : $seller->slug }}</a>
                                </h4>

                                <a class="shop_now_text" href="{{ route('frontend.seller', $seller->slug) }}">Shop now
                                    »</a>
                            </div>
                        </div>
                    </div>

                @empty
                    <p> No Shops found </p>
                @endforelse
            </div>

        </div>
    </div>
    <!-- end cats -->
    <!-- start cats -->
    <div class="amaz_section section_spacing2">

        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section__title d-flex align-items-center gap-3 mb_30 flex-wrap">
                        <h3 id="best_deals_title" class="m-0 flex-fill">Informatique:</h3>
                        <a href="#" class="title_link d-flex align-items-center lh-1">
                            <span class="title_text"></span>
                            <span class="title_icon">
                                {{-- <i class="fas fa-chevron-right"></i> --}}
                            </span>
                        </a>
                    </div>
                </div>
            </div>


            <div class="row">
                @php
                    $sellers = DB::table('seller_products')
                        ->join('category_product', 'seller_products.product_id', 'category_product.product_id')
                        ->join('users', 'users.id', 'seller_products.user_id')
                        ->leftJoin('seller_accounts', 'seller_accounts.user_id', 'users.id')
                        ->whereRaw('category_product.category_id in (42,43,21,22) ')
                        ->selectRaw('distinct users.slug,users.photo,seller_accounts.*')
                        ->get();
                    //->toSql();
                    //
                    
                    //dd($sellers);
                @endphp
                @forelse($sellers as $seller)
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="amaz_home_cartBox amaz_cat_bg1 d-flex justify-content-between mb_30">
                            <div class="img_box">
                                <img class="lazyload" src="{{ showImage(themeDefaultImg()) }}"
                                    data-src="{{ showImage(@$seller->photo ? @$seller->photo : 'frontend/default/img/default_category.png') }}"
                                    alt="">
                            </div>
                            <div class="amazcat_text_box">
                                <h4>
                                    <a
                                        href="{{ route('frontend.seller', $seller->slug) }}">{{ $seller->seller_shop_display_name ? $seller->seller_shop_display_name : $seller->slug }}</a>
                                </h4>

                                <a class="shop_now_text" href="{{ route('frontend.seller', $seller->slug) }}">Shop now
                                    »</a>
                            </div>
                        </div>
                    </div>

                @empty
                    <p> No Shops found </p>
                @endforelse
            </div>

        </div>
    </div>
    <!-- end cats -->

    <!-- start cats -->

    <div class="amaz_section section_spacing2">

        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section__title d-flex align-items-center gap-3 mb_30 flex-wrap">
                        <h3 id="best_deals_title" class="m-0 flex-fill">Maison & Bereau:</h3>
                        <a href="#" class="title_link d-flex align-items-center lh-1">
                            <span class="title_text"></span>
                            <span class="title_icon">
                                {{-- <i class="fas fa-chevron-right"></i> --}}
                            </span>
                        </a>
                    </div>
                </div>
            </div>


            <div class="row">
                @php
                    $sellers = DB::table('seller_products')
                        ->join('category_product', 'seller_products.product_id', 'category_product.product_id')
                        ->join('users', 'users.id', 'seller_products.user_id')
                        ->leftJoin('seller_accounts', 'seller_accounts.user_id', 'users.id')
                        ->whereRaw('category_product.category_id in (44,45,46,47) ')
                        ->selectRaw('distinct users.slug,users.photo,seller_accounts.*')
                        ->get();
                    //->toSql();
                    //
                    
                    //dd($sellers);
                @endphp
                @forelse($sellers as $seller)
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="amaz_home_cartBox amaz_cat_bg1 d-flex justify-content-between mb_30">
                            <div class="img_box">
                                <img class="lazyload" src="{{ showImage(themeDefaultImg()) }}"
                                    data-src="{{ showImage(@$seller->photo ? @$seller->photo : 'frontend/default/img/default_category.png') }}"
                                    alt="">
                            </div>
                            <div class="amazcat_text_box">
                                <h4>
                                    <a
                                        href="{{ route('frontend.seller', $seller->slug) }}">{{ $seller->seller_shop_display_name ? $seller->seller_shop_display_name : $seller->slug }}</a>
                                </h4>

                                <a class="shop_now_text" href="{{ route('frontend.seller', $seller->slug) }}">Shop now
                                    »</a>
                            </div>
                        </div>
                    </div>

                @empty
                    <p> No Shops found </p>
                @endforelse
            </div>
        </div>
    </div>
    <!-- end cats -->





    @php
        $filter_category = $widgets->where('section_name', 'filter_category')->first();
        $category = @$filter_category->customSection->category;
    @endphp

    <div id="filter_category" class="amaz_section section_spacing2">
        <div class="container ">
            @if ($category)
                <div class="row no-gutters">
                    <div class="col-xl-5 p-0 col-lg-12">
                        <div class="House_Appliances_widget">
                            <div class="House_Appliances_widget_left d-flex flex-column flex-fill">
                                <h4 id="filter_category_title">{{ $filter_category->title }}</h4>
                                <ul class="nav nav-tabs flex-fill flex-column border-0" id="myTab10" role="tablist">
                                    @foreach (@$category->subCategories as $key => $subcat)
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link {{ $key == 0 ? 'active' : '' }}"
                                                id="tab_link_{{ $subcat->id }}" data-bs-toggle="tab"
                                                data-bs-target="#tab_pane_subcat_{{ $subcat->id }}" type="button"
                                                role="tab" aria-controls="Dining"
                                                aria-selected="true">{{ $subcat->name }}</button>
                                        </li>
                                    @endforeach
                                </ul>
                                <a href="{{ route('frontend.category-product', ['slug' => $category->slug, 'item' => 'category']) }}"
                                    class="title_link d-flex align-items-center lh-1">
                                    <span class="title_text">More Deals</span>
                                    <span class="title_icon">
                                        <i class="fas fa-chevron-right"></i>
                                    </span>
                                </a>
                            </div>
                            <a href="{{ route('frontend.category-product', ['slug' => $category->slug, 'item' => 'category']) }}"
                                class="House_Appliances_widget_right overflow-hidden p-0 {{ $filter_category->customSection->field_2 ? '' : 'd-none' }}">
                                <img class="h-100 lazyload"
                                    data-src="{{ showImage($filter_category->customSection->field_2) }}"
                                    src="{{ showImage(themeDefaultImg()) }}" alt="">
                            </a>
                        </div>
                    </div>
                    <div class="col-xl-7 p-0 col-lg-12">
                        <div class="tab-content" id="myTabContent10">
                            @foreach ($category->subCategories as $key => $subcat)
                                <div class="tab-pane fade {{ $key == 0 ? 'show active' : '' }}"
                                    id="tab_pane_subcat_{{ $subcat->id }}" role="tabpanel"
                                    aria-labelledby="Dining-tab">
                                    <!-- content  -->
                                    <div class="House_Appliances_product">
                                        @foreach ($subcat->sellerProductTake() as $product)
                                            <div class="product_widget5 style4 ">
                                                <div class="product_thumb_upper">
                                                    @php
                                                        if (@$product->thum_img != null) {
                                                            $thumbnail = showImage(@$product->thum_img);
                                                        } else {
                                                            $thumbnail = showImage(@$product->product->thumbnail_image_source);
                                                        }
                                                        
                                                        $price_qty = getProductDiscountedPrice(@$product);
                                                        $showData = [
                                                            'name' => @$product->product->product_name,
                                                            'url' => singleProductURL(@$product->product->seller->slug, @$product->product->slug),
                                                            'price' => $price_qty,
                                                            'thumbnail' => $thumbnail,
                                                        ];
                                                    @endphp
                                                    <a href="{{ singleProductURL($product->seller->slug, $product->slug) }}"
                                                        class="thumb">
                                                        <img data-src="{{ $thumbnail }}"
                                                            src="{{ showImage(themeDefaultImg()) }}" class="lazyload"
                                                            alt="{{ @$product->product_name }}">
                                                    </a>
                                                    <div class="product_action">
                                                        <a href="" class="addToCompareFromThumnail"
                                                            data-producttype="{{ @$product->product->product_type }}"
                                                            data-seller={{ $product->user_id }}
                                                            data-product-sku={{ @$product->skus->first()->id }}
                                                            data-product-id={{ $product->id }}>
                                                            <i class="ti-control-shuffle"></i>
                                                        </a>
                                                        <a href=""
                                                            class="add_to_wishlist {{ $product->is_wishlist() == 1 ? 'is_wishlist' : '' }}"
                                                            id="wishlistbtn_{{ $product->id }}"
                                                            data-product_id="{{ $product->id }}"
                                                            data-seller_id="{{ $product->user_id }}">
                                                            <i class="ti-heart"></i>
                                                        </a>
                                                        <a class="quickView" data-product_id="{{ $product->id }}"
                                                            data-type="product">
                                                            <i class="ti-eye"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="product__meta text-center">
                                                    <span
                                                        class="product_banding ">{{ $product->brand->name ?? __('amazy.no_brand') }}</span>
                                                    <a
                                                        href="{{ singleProductURL($product->seller->slug, $product->slug) }}">
                                                        <h4>
                                                            @if ($product->product_name)
                                                                {{ textLimit(@$product->product_name, 56) }}
                                                            @else
                                                                {{ textLimit(@$product->product->product_name, 56) }}
                                                            @endif
                                                        </h4>
                                                    </a>
                                                    <div class="stars justify-content-center">
                                                        @php
                                                            $reviews = $product->reviews->where('status', 1)->pluck('rating');
                                                            if (count($reviews) > 0) {
                                                                $value = 0;
                                                                $rating = 0;
                                                                foreach ($reviews as $review) {
                                                                    $value += $review;
                                                                }
                                                                $rating = $value / count($reviews);
                                                                $total_review = count($reviews);
                                                            } else {
                                                                $rating = 0;
                                                                $total_review = 0;
                                                            }
                                                        @endphp
                                                        <x-rating :rating="$rating" />
                                                    </div>
                                                    <div class="product_prise">
                                                        <p>
                                                            @if ($product->hasDeal)
                                                                {{ single_price(selling_price($product->skus->first()->selling_price, $product->hasDeal->discount_type, $product->hasDeal->discount)) }}
                                                            @else
                                                                @if ($product->hasDiscount == 'yes')
                                                                    {{ single_price(selling_price(@$product->skus->first()->selling_price, @$product->discount_type, @$product->discount)) }}
                                                                @else
                                                                    {{ single_price(@$product->skus->first()->selling_price) }}
                                                                @endif
                                                            @endif
                                                        </p>
                                                        <a class="add_cart add_to_cart addToCartFromThumnail"
                                                            data-producttype="{{ @$product->product->product_type }}"
                                                            data-seller={{ $product->user_id }}
                                                            data-product-sku={{ @$product->skus->first()->id }}
                                                            @if (@$product->hasDeal) data-base-price={{ selling_price(@$product->skus->first()->selling_price, @$product->hasDeal->discount_type, @$product->hasDeal->discount) }}
                                                        @else
                                                            @if (@$product->hasDiscount == 'yes')
                                                                data-base-price={{ selling_price(@$product->skus->first()->selling_price, @$product->discount_type, @$product->discount) }}
                                                            @else
                                                                data-base-price={{ @$product->skus->first()->selling_price }} @endif
                                                            @endif
                                                            data-shipping-method=0
                                                            data-product-id={{ $product->id }}
                                                            data-stock_manage="{{ $product->stock_manage }}"
                                                            data-stock="{{ @$product->skus->first()->product_stock }}"
                                                            data-min_qty="{{ $product->product->minimum_order_qty }}"
                                                            data-prod_info="{{ json_encode($showData) }}"
                                                            >Add To Cart</a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <!-- content  -->
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- amaz_section::end  -->

    <!-- cta::start  -->
    <div class="amaz_section">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <x-random-ads-component />
                </div>
            </div>
        </div>
    </div>
    <!-- cta::end  -->

    @php
        $top_rating = $widgets->where('section_name', 'top_rating')->first();
        $peoples_choice = $widgets->where('section_name', 'people_choices')->first();
        $top_picks = $widgets->where('section_name', 'top_picks')->first();
    @endphp
    <div class="amaz_section section_spacing3">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <ul class="nav amzcart_tabs d-flex align-items-center justify-content-center flex-wrap "
                        id="myTab" role="tablist">
                        <li class="nav-item " role="presentation" id="top_rating"
                            class="{{ $top_rating->status == 0 ? 'd-none' : '' }}">
                            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home"
                                type="button" role="tab" aria-controls="home" aria-selected="true"><span
                                    id="top_rating_title">{{ $top_rating->title }}</span></button>
                        </li>
                        <li class="nav-item" role="presentation" id="people_choices"
                            class="{{ $peoples_choice->status == 0 ? 'd-none' : '' }}">
                            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile"
                                type="button" role="tab" aria-controls="profile" aria-selected="false"><span
                                    id="people_choice_title">{{ $peoples_choice->title }}</span></button>
                        </li>
                        <li class="nav-item" role="presentation" id="top_picks"
                            class="{{ $top_picks->status == 0 ? 'd-none' : '' }}">
                            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact"
                                type="button" role="tab" aria-controls="contact" aria-selected="false"><span
                                    id="top_picks_title">{{ $top_picks->title }}</span></button>
                        </li>
                    </ul>

                </div>
                <div class="col-xl-12">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel"
                            aria-labelledby="home-tab">
                            <!-- conttent  -->
                            <div class="amaz_fieature_active fieature_crousel_area owl-carousel">
                                @foreach ($top_rating->getHomePageProductByQuery() as $key => $product)
                                    <div class="product_widget5 style2 mb_30">
                                        <div class="product_thumb_upper">
                                            @php
                                                if (@$product->thum_img != null) {
                                                    $thumbnail = showImage(@$product->thum_img);
                                                } else {
                                                    $thumbnail = showImage(@$product->product->thumbnail_image_source);
                                                }
                                                
                                                $price_qty = getProductDiscountedPrice(@$product);
                                                $showData = [
                                                    'name' => @$product->product_name,
                                                    'url' => singleProductURL(@$product->seller->slug, @$product->slug),
                                                    'price' => $price_qty,
                                                    'thumbnail' => $thumbnail,
                                                ];
                                            @endphp
                                            <a href="{{ singleProductURL($product->seller->slug, $product->slug) }}"
                                                class="thumb">
                                                <img data-src="{{ $thumbnail }}"
                                                    src="{{ showImage(themeDefaultImg()) }}" class="lazyload"
                                                    alt="{{ @$product->product_name }}">
                                            </a>
                                            <div class="product_action">
                                                <a href="" class="addToCompareFromThumnail"
                                                    data-producttype="{{ @$product->product->product_type }}"
                                                    data-seller={{ $product->user_id }}
                                                    data-product-sku={{ @$product->skus->first()->id }}
                                                    data-product-id={{ $product->id }}>
                                                    <i class="ti-control-shuffle"></i>
                                                </a>
                                                <a href=""
                                                    class="add_to_wishlist {{ $product->is_wishlist() == 1 ? 'is_wishlist' : '' }}"
                                                    id="wishlistbtn_{{ $product->id }}"
                                                    data-product_id="{{ $product->id }}"
                                                    data-seller_id="{{ $product->user_id }}">
                                                    <i class="ti-heart"></i>
                                                </a>
                                                <a class="quickView" data-product_id="{{ $product->id }}"
                                                    data-type="product">
                                                    <i class="ti-eye"></i>
                                                </a>
                                            </div>
                                            @if ($product->hasDeal)
                                                @if ($product->hasDeal->discount > 0)
                                                    <span class="badge_1">
                                                        @if ($product->hasDeal->discount > 0)
                                                            @if ($product->hasDeal->discount_type == 0)
                                                                {{ $product->hasDeal->discount }} % off
                                                            @else
                                                                {{ single_price($product->hasDeal->discount) }} off
                                                            @endif

                                                        @endif
                                                    </span>
                                                @endif
                                            @else
                                                @if ($product->hasDiscount == 'yes')
                                                    @if ($product->discount > 0)
                                                        <span class="badge_1">
                                                            @if ($product->discount > 0)
                                                                @if ($product->discount_type == 0)
                                                                    {{ $product->discount }} % off
                                                                @else
                                                                    {{ single_price($product->discount) }} off
                                                                @endif
                                                            @endif
                                                        </span>
                                                    @endif
                                                @endif
                                            @endif
                                        </div>
                                        <div class="product__meta text-center">
                                            <span
                                                class="product_banding ">{{ $product->brand->name ?? __('amazy.no_brand') }}</span>
                                            <a href="{{ singleProductURL($product->seller->slug, $product->slug) }}">
                                                <h4 class="text-nowrap">
                                                    @if ($product->product_name)
                                                        {{ textLimit(@$product->product_name, 35) }}
                                                    @else
                                                        {{ textLimit(@$product->product->product_name, 35) }}
                                                    @endif
                                                </h4>
                                            </a>
                                            <div class="stars justify-content-center">
                                                @php
                                                    $reviews = $product->reviews->where('status', 1)->pluck('rating');
                                                    if (count($reviews) > 0) {
                                                        $value = 0;
                                                        $rating = 0;
                                                        foreach ($reviews as $review) {
                                                            $value += $review;
                                                        }
                                                        $rating = $value / count($reviews);
                                                        $total_review = count($reviews);
                                                    } else {
                                                        $rating = 0;
                                                        $total_review = 0;
                                                    }
                                                @endphp
                                                <x-rating :rating="$rating" />
                                            </div>
                                            <div class="product_prise">
                                                <p>
                                                    @if ($product->hasDeal)
                                                        {{ single_price(selling_price($product->skus->first()->selling_price, $product->hasDeal->discount_type, $product->hasDeal->discount)) }}
                                                    @else
                                                        @if ($product->hasDiscount == 'yes')
                                                            {{ single_price(selling_price(@$product->skus->first()->selling_price, @$product->discount_type, @$product->discount)) }}
                                                        @else
                                                            {{ single_price(@$product->skus->first()->selling_price) }}
                                                        @endif
                                                    @endif
                                                </p>
                                                <a class="add_cart add_to_cart addToCartFromThumnail"
                                                    data-producttype="{{ @$product->product->product_type }}"
                                                    data-seller={{ $product->user_id }}
                                                    data-product-sku={{ @$product->skus->first()->id }}
                                                    @if (@$product->hasDeal) data-base-price={{ selling_price(@$product->skus->first()->selling_price, @$product->hasDeal->discount_type, @$product->hasDeal->discount) }}
                                                @else
                                                    @if (@$product->hasDiscount == 'yes')
                                                        data-base-price={{ selling_price(@$product->skus->first()->selling_price, @$product->discount_type, @$product->discount) }}
                                                    @else
                                                        data-base-price={{ @$product->skus->first()->selling_price }} @endif
                                                    @endif
                                                    data-shipping-method=0
                                                    data-product-id={{ $product->id }}
                                                    data-stock_manage="{{ $product->stock_manage }}"
                                                    data-stock="{{ @$product->skus->first()->product_stock }}"
                                                    data-min_qty="{{ $product->product->minimum_order_qty }}"
                                                    data-prod_info="{{ json_encode($showData) }}"
                                                    >Add To Cart</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <!-- conttent  -->
                        </div>
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <!-- conttent  -->
                            <div class="amaz_fieature_active fieature_crousel_area owl-carousel">
                                @foreach ($peoples_choice->getHomePageProductByQuery() as $key => $product)
                                    <div class="product_widget5 style2 mb_30">
                                        <div class="product_thumb_upper">
                                            @php
                                                if (@$product->thum_img != null) {
                                                    $thumbnail = showImage(@$product->thum_img);
                                                } else {
                                                    $thumbnail = showImage(@$product->product->thumbnail_image_source);
                                                }
                                                
                                                $price_qty = getProductDiscountedPrice(@$product);
                                                $showData = [
                                                    'name' => @$product->product->product_name,
                                                    'url' => singleProductURL(@$product->product->seller->slug, @$product->product->slug),
                                                    'price' => $price_qty,
                                                    'thumbnail' => $thumbnail,
                                                ];
                                            @endphp
                                            <a href="{{ singleProductURL($product->seller->slug, $product->slug) }}"
                                                class="thumb">
                                                <img data-src="{{ $thumbnail }}"
                                                    src="{{ showImage(themeDefaultImg()) }}" class="lazyload"
                                                    alt="{{ @$product->product_name }}">
                                            </a>
                                            <div class="product_action">
                                                <a href="" class="addToCompareFromThumnail"
                                                    data-producttype="{{ @$product->product->product_type }}"
                                                    data-seller={{ $product->user_id }}
                                                    data-product-sku={{ @$product->skus->first()->id }}
                                                    data-product-id={{ $product->id }}>
                                                    <i class="ti-control-shuffle"></i>
                                                </a>
                                                <a href=""
                                                    class="add_to_wishlist {{ $product->is_wishlist() == 1 ? 'is_wishlist' : '' }}"
                                                    id="wishlistbtn_{{ $product->id }}"
                                                    data-product_id="{{ $product->id }}"
                                                    data-seller_id="{{ $product->user_id }}">
                                                    <i class="ti-heart"></i>
                                                </a>
                                                <a class="quickView" data-product_id="{{ $product->id }}"
                                                    data-type="product">
                                                    <i class="ti-eye"></i>
                                                </a>
                                            </div>
                                            @if ($product->hasDeal)
                                                @if ($product->hasDeal->discount > 0)
                                                    <span class="badge_1">
                                                        @if ($product->hasDeal->discount > 0)
                                                            @if ($product->hasDeal->discount_type == 0)
                                                                {{ $product->hasDeal->discount }} % off
                                                            @else
                                                                {{ single_price($product->hasDeal->discount) }} off
                                                            @endif

                                                        @endif
                                                    </span>
                                                @endif
                                            @else
                                                @if ($product->hasDiscount == 'yes')
                                                    @if ($product->discount > 0)
                                                        <span class="badge_1">
                                                            @if ($product->discount > 0)
                                                                @if ($product->discount_type == 0)
                                                                    {{ $product->discount }} % off
                                                                @else
                                                                    {{ single_price($product->discount) }} off
                                                                @endif
                                                            @endif
                                                        </span>
                                                    @endif
                                                @endif
                                            @endif
                                        </div>
                                        <div class="product__meta text-center">
                                            <span
                                                class="product_banding ">{{ $product->brand->name ?? __('amazy.no_brand') }}</span>
                                            <a href="{{ singleProductURL($product->seller->slug, $product->slug) }}">
                                                <h4 class="text-nowrap">
                                                    @if ($product->product_name)
                                                        {{ textLimit(@$product->product_name, 35) }}
                                                    @else
                                                        {{ textLimit(@$product->product->product_name, 35) }}
                                                    @endif
                                                </h4>
                                            </a>
                                            <div class="stars justify-content-center">
                                                @php
                                                    $reviews = $product->reviews->where('status', 1)->pluck('rating');
                                                    if (count($reviews) > 0) {
                                                        $value = 0;
                                                        $rating = 0;
                                                        foreach ($reviews as $review) {
                                                            $value += $review;
                                                        }
                                                        $rating = $value / count($reviews);
                                                        $total_review = count($reviews);
                                                    } else {
                                                        $rating = 0;
                                                        $total_review = 0;
                                                    }
                                                @endphp
                                                <x-rating :rating="$rating" />
                                            </div>
                                            <div class="product_prise">
                                                <p>
                                                    @if ($product->hasDeal)
                                                        {{ single_price(selling_price($product->skus->first()->selling_price, $product->hasDeal->discount_type, $product->hasDeal->discount)) }}
                                                    @else
                                                        @if ($product->hasDiscount == 'yes')
                                                            {{ single_price(selling_price(@$product->skus->first()->selling_price, @$product->discount_type, @$product->discount)) }}
                                                        @else
                                                            {{ single_price(@$product->skus->first()->selling_price) }}
                                                        @endif
                                                    @endif
                                                </p>
                                                <a class="add_cart add_to_cart addToCartFromThumnail"
                                                    data-producttype="{{ @$product->product->product_type }}"
                                                    data-seller={{ $product->user_id }}
                                                    data-product-sku={{ @$product->skus->first()->id }}
                                                    @if (@$product->hasDeal) data-base-price={{ selling_price(@$product->skus->first()->selling_price, @$product->hasDeal->discount_type, @$product->hasDeal->discount) }}
                                            @else
                                                @if (@$product->hasDiscount == 'yes')
                                                    data-base-price={{ selling_price(@$product->skus->first()->selling_price, @$product->discount_type, @$product->discount) }}
                                                @else
                                                    data-base-price={{ @$product->skus->first()->selling_price }} @endif
                                                    @endif
                                                    data-shipping-method=0
                                                    data-product-id={{ $product->id }}
                                                    data-stock_manage="{{ $product->stock_manage }}"
                                                    data-stock="{{ @$product->skus->first()->product_stock }}"
                                                    data-min_qty="{{ $product->product->minimum_order_qty }}"
                                                    data-prod_info="{{ json_encode($showData) }}"
                                                    >Add To Cart</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <!-- conttent  -->
                        </div>
                        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                            <!-- conttent  -->
                            <div class="amaz_fieature_active fieature_crousel_area owl-carousel">
                                @foreach ($top_picks->getHomePageProductByQuery() as $key => $product)
                                    <div class="product_widget5 style2 mb_30">
                                        <div class="product_thumb_upper">
                                            @php
                                                if (@$product->thum_img != null) {
                                                    $thumbnail = showImage(@$product->thum_img);
                                                } else {
                                                    $thumbnail = showImage(@$product->product->thumbnail_image_source);
                                                }
                                                
                                                $price_qty = getProductDiscountedPrice(@$product);
                                                $showData = [
                                                    'name' => @$product->product->product_name,
                                                    'url' => singleProductURL(@$product->product->seller->slug, @$product->product->slug),
                                                    'price' => $price_qty,
                                                    'thumbnail' => $thumbnail,
                                                ];
                                            @endphp
                                            <a href="{{ singleProductURL($product->seller->slug, $product->slug) }}"
                                                class="thumb">
                                                <img data-src="{{ $thumbnail }}"
                                                    src="{{ showImage(themeDefaultImg()) }}" class="lazyload"
                                                    alt="{{ @$product->product_name }}">
                                            </a>
                                            <div class="product_action">
                                                <a href="" class="addToCompareFromThumnail"
                                                    data-producttype="{{ @$product->product->product_type }}"
                                                    data-seller={{ $product->user_id }}
                                                    data-product-sku={{ @$product->skus->first()->id }}
                                                    data-product-id={{ $product->id }}>
                                                    <i class="ti-control-shuffle"></i>
                                                </a>
                                                <a href=""
                                                    class="add_to_wishlist {{ $product->is_wishlist() == 1 ? 'is_wishlist' : '' }}"
                                                    id="wishlistbtn_{{ $product->id }}"
                                                    data-product_id="{{ $product->id }}"
                                                    data-seller_id="{{ $product->user_id }}">
                                                    <i class="ti-heart"></i>
                                                </a>
                                                <a class="quickView" data-product_id="{{ $product->id }}"
                                                    data-type="product">
                                                    <i class="ti-eye"></i>
                                                </a>
                                            </div>
                                            @if ($product->hasDeal)
                                                @if ($product->hasDeal->discount > 0)
                                                    <span class="badge_1">
                                                        @if ($product->hasDeal->discount > 0)
                                                            @if ($product->hasDeal->discount_type == 0)
                                                                {{ $product->hasDeal->discount }} % off
                                                            @else
                                                                {{ single_price($product->hasDeal->discount) }} off
                                                            @endif

                                                        @endif
                                                    </span>
                                                @endif
                                            @else
                                                @if ($product->hasDiscount == 'yes')
                                                    @if ($product->discount > 0)
                                                        <span class="badge_1">
                                                            @if ($product->discount > 0)
                                                                @if ($product->discount_type == 0)
                                                                    {{ $product->discount }} % off
                                                                @else
                                                                    {{ single_price($product->discount) }} off
                                                                @endif
                                                            @endif
                                                        </span>
                                                    @endif
                                                @endif
                                            @endif
                                        </div>
                                        <div class="product__meta text-center">
                                            <span
                                                class="product_banding ">{{ $product->brand->name ?? __('amazy.no_brand') }}</span>
                                            <a href="{{ singleProductURL($product->seller->slug, $product->slug) }}">
                                                <h4 class="text-nowrap">
                                                    @if ($product->product_name)
                                                        {{ textLimit(@$product->product_name, 35) }}
                                                    @else
                                                        {{ textLimit(@$product->product->product_name, 35) }}
                                                    @endif
                                                </h4>
                                            </a>
                                            <div class="stars justify-content-center">
                                                @php
                                                    $reviews = $product->reviews->where('status', 1)->pluck('rating');
                                                    if (count($reviews) > 0) {
                                                        $value = 0;
                                                        $rating = 0;
                                                        foreach ($reviews as $review) {
                                                            $value += $review;
                                                        }
                                                        $rating = $value / count($reviews);
                                                        $total_review = count($reviews);
                                                    } else {
                                                        $rating = 0;
                                                        $total_review = 0;
                                                    }
                                                @endphp
                                                <x-rating :rating="$rating" />
                                            </div>
                                            <div class="product_prise">
                                                <p>
                                                    @if ($product->hasDeal)
                                                        {{ single_price(selling_price($product->skus->first()->selling_price, $product->hasDeal->discount_type, $product->hasDeal->discount)) }}
                                                    @else
                                                        @if ($product->hasDiscount == 'yes')
                                                            {{ single_price(selling_price(@$product->skus->first()->selling_price, @$product->discount_type, @$product->discount)) }}
                                                        @else
                                                            {{ single_price(@$product->skus->first()->selling_price) }}
                                                        @endif
                                                    @endif
                                                </p>
                                                <a class="add_cart add_to_cart addToCartFromThumnail"
                                                    data-producttype="{{ @$product->product->product_type }}"
                                                    data-seller={{ $product->user_id }}
                                                    data-product-sku={{ @$product->skus->first()->id }}
                                                    @if (@$product->hasDeal) data-base-price={{ selling_price(@$product->skus->first()->selling_price, @$product->hasDeal->discount_type, @$product->hasDeal->discount) }}
                                            @else
                                                @if (@$product->hasDiscount == 'yes')
                                                    data-base-price={{ selling_price(@$product->skus->first()->selling_price, @$product->discount_type, @$product->discount) }}
                                                @else
                                                    data-base-price={{ @$product->skus->first()->selling_price }} @endif
                                                    @endif
                                                    data-shipping-method=0
                                                    data-product-id={{ $product->id }}
                                                    data-stock_manage="{{ $product->stock_manage }}"
                                                    data-stock="{{ @$product->skus->first()->product_stock }}"
                                                    data-min_qty="{{ $product->product->minimum_order_qty }}"
                                                    data-prod_info="{{ json_encode($showData) }}"
                                                    >Add To Cart</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <!-- conttent  -->
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @php
        $discount_banner = $widgets->where('section_name', 'discount_banner')->first();
    @endphp
    <div id="discount_banner" class="amaz_section amaz_deal_area {{ $discount_banner->status == 0 ? 'd-none' : '' }}">
        <div class="container">
            <div class="row">
                <div
                    class="col-xl-4 col-md-6 col-lg-4 mb_20 {{ !@$discount_banner->customSection->field_4 ? 'd-none' : '' }}">
                    <a href="{{ @$discount_banner->customSection->field_4 }}" class="mb_30">
                        <img data-src="{{ showImage(@$discount_banner->customSection->field_1) }}"
                            src="{{ showImage(themeDefaultImg()) }}" alt="" class="img-fluid lazyload">
                    </a>
                </div>
                <div
                    class="col-xl-4 col-md-6 col-lg-4 mb_20 {{ !@$discount_banner->customSection->field_5 ? 'd-none' : '' }}">
                    <a href="{{ @$discount_banner->customSection->field_5 }}" class=" mb_30">
                        <img data-src="{{ showImage(@$discount_banner->customSection->field_2) }}"
                            src="{{ showImage(themeDefaultImg()) }}" alt="" class="img-fluid lazyload">
                    </a>
                </div>
                <div
                    class="col-xl-4 col-md-6 col-lg-4 mb_20 {{ !@$discount_banner->customSection->field_6 ? 'd-none' : '' }}">
                    <a href="{{ @$discount_banner->customSection->field_6 }}" class=" mb_30">
                        <img data-src="{{ showImage(@$discount_banner->customSection->field_3) }}"
                            src="{{ showImage(themeDefaultImg()) }}" alt="" class="img-fluid lazyload">
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- amaz_recomanded::start  -->
    @php
        $more_products = $widgets->where('section_name', 'more_products')->first();
    @endphp
    <div class="amaz_recomanded_area {{ $more_products->status == 0 ? 'd-none' : '' }}">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div id="more_products" class="amaz_recomanded_box mb_60">
                        <div class="amaz_recomanded_box_head">
                            <h4 class="mb-0">{{ $more_products->title }}</h4>
                        </div>
                        <div class="amaz_recomanded_box_body2 dataApp">
                            @foreach ($more_products->getHomePageProductByQuery() as $key => $product)
                                <div class="product_widget5 style3 bg-white">
                                    <div class="product_thumb_upper">
                                        @php
                                            if (@$product->thum_img != null) {
                                                $thumbnail = showImage(@$product->thum_img);
                                            } else {
                                                $thumbnail = showImage(@$product->product->thumbnail_image_source);
                                            }
                                            
                                            $price_qty = getProductDiscountedPrice(@$product);
                                            $showData = [
                                                'name' => @$product->product_name,
                                                'url' => singleProductURL(@$product->seller->slug, @$product->slug),
                                                'price' => $price_qty,
                                                'thumbnail' => $thumbnail,
                                            ];
                                        @endphp
                                        <a href="{{ singleProductURL($product->seller->slug, $product->slug) }}"
                                            class="thumb">
                                            <img data-src="{{ $thumbnail }}"
                                                src="{{ showImage(themeDefaultImg()) }}"
                                                alt="{{ @$product->product_name }}" class="lazyload">
                                        </a>
                                        <div class="product_action">
                                            <a href="" class="addToCompareFromThumnail"
                                                data-producttype="{{ @$product->product->product_type }}"
                                                data-seller={{ $product->user_id }}
                                                data-product-sku={{ @$product->skus->first()->id }}
                                                data-product-id={{ $product->id }}>
                                                <i class="ti-control-shuffle"></i>
                                            </a>
                                            <a href=""
                                                class="add_to_wishlist {{ $product->is_wishlist() == 1 ? 'is_wishlist' : '' }}"
                                                id="wishlistbtn_{{ $product->id }}"
                                                data-product_id="{{ $product->id }}"
                                                data-seller_id="{{ $product->user_id }}">
                                                <i class="ti-heart"></i>
                                            </a>
                                            <a class="quickView" data-product_id="{{ $product->id }}"
                                                data-type="product">
                                                <i class="ti-eye"></i>
                                            </a>
                                        </div>
                                        <div class="recomanded_discount">
                                            @if ($product->hasDeal)
                                                @if ($product->hasDeal->discount > 0)
                                                    <span class="badge_1">
                                                        @if ($product->hasDeal->discount > 0)
                                                            @if ($product->hasDeal->discount_type == 0)
                                                                {{ $product->hasDeal->discount }} % off
                                                            @else
                                                                {{ single_price($product->hasDeal->discount) }} off
                                                            @endif

                                                        @endif
                                                    </span>
                                                @endif
                                            @else
                                                @if ($product->hasDiscount == 'yes')
                                                    @if ($product->discount > 0)
                                                        <span class="badge_1">
                                                            @if ($product->discount > 0)
                                                                @if ($product->discount_type == 0)
                                                                    {{ $product->discount }} % off
                                                                @else
                                                                    {{ single_price($product->discount) }} off
                                                                @endif
                                                            @endif
                                                        </span>
                                                    @endif
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                    <div class="product__meta text-center">
                                        <span
                                            class="product_banding ">{{ @$product->brand->name ?? __('amazy.no_brand') }}</span>
                                        <a href="{{ singleProductURL(@$product->seller->slug, $product->slug) }}">
                                            <h4>
                                                @if ($product->product_name)
                                                    {{ textLimit(@$product->product_name, 50) }}
                                                @else
                                                    {{ textLimit(@$product->product->product_name, 50) }} @endif
                                            </h4>
                                        </a>
                                        <div class="stars justify-content-center">
                                            @php
                                                $reviews = $product->reviews->where('status', 1)->pluck('rating');
                                                if (count($reviews) > 0) {
                                                    $value = 0;
                                                    $rating = 0;
                                                    foreach ($reviews as $review) {
                                                        $value += $review;
                                                    }
                                                    $rating = $value / count($reviews);
                                                    $total_review = count($reviews);
                                                } else {
                                                    $rating = 0;
                                                    $total_review = 0;
                                                }
                                            @endphp
                                            <x-rating :rating="$rating" />
                                        </div>
                                        <div class="product_prise">
                                            <p>
                                                @if ($product->hasDeal)
                                                    {{ single_price(selling_price($product->skus->first()->selling_price, $product->hasDeal->discount_type, $product->hasDeal->discount)) }}
                                                @else
                                                    @if ($product->hasDiscount == 'yes')
                                                        {{ single_price(selling_price(@$product->skus->first()->selling_price, @$product->discount_type, @$product->discount)) }}
                                                    @else
                                                        {{ single_price(@$product->skus->first()->selling_price) }}
                                                    @endif
                                                @endif
                                            </p>
                                            <a class="add_cart add_to_cart addToCartFromThumnail"
                                                data-producttype="{{ @$product->product->product_type }}"
                                                data-seller={{ $product->user_id }}
                                                data-product-sku={{ @$product->skus->first()->id }}
                                                @if (@$product->hasDeal) data-base-price={{ selling_price(@$product->skus->first()->selling_price, @$product->hasDeal->discount_type, @$product->hasDeal->discount) }}
                                            @else
                                                @if (@$product->hasDiscount == 'yes')
                                                    data-base-price={{ selling_price(@$product->skus->first()->selling_price, @$product->discount_type, @$product->discount) }}
                                                @else
                                                    data-base-price={{ @$product->skus->first()->selling_price }} @endif
                                                @endif
                                                data-shipping-method=0
                                                data-product-id={{ $product->id }}
                                                data-stock_manage="{{ $product->stock_manage }}"
                                                data-stock="{{ @$product->skus->first()->product_stock }}"
                                                data-min_qty="{{ @$product->product->minimum_order_qty }}"
                                                data-prod_info="{{ json_encode($showData) }}"
                                                >{{ __('defaultTheme.add_to_cart') }}</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-12 text-center">
                    @if ($more_products->getHomePageProductByQuery()->lastPage() > 1)
                        <a id="loadmore" class="amaz_primary_btn2 min_200 load_more_btn_homepage">Load more</a>
                    @endif

                    <input type="hidden" id="login_check" value="@if (auth()->check()) 1 @else 0 @endif">
                </div>
            </div>
        </div>
    </div>
    <!-- amaz_recomanded::end -->
    <x-top-brand-component />
    <!-- amaz_brand::start  -->

    <!-- amaz_brand::end  -->

    <!-- Popular Searches::start  -->
    <x-popular-search-component />
    <!-- Popular Searches::end  -->
    @include(theme('partials._subscription_modal'))
@endsection
@include(theme('partials.add_to_cart_script'))
@include(theme('partials.add_to_compare_script'))
