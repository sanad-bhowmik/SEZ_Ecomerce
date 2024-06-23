@php
    $current_url = url()->current();
@endphp
@if($current_url == url('/cart') || $current_url == url('/checkout'))
@else
    <!-- side_chartView_total::start  -->
    @if($items > 0)
        <div class="side_chartView_total d-flex align-items-center add_to_cart  gj-cursor-pointer ">
            <span class="remove_sidebar_cart gj-cursor-pointer d-inline-flex align-items-center justify-content-center" id="remove_cart_sidebar">
                <i class="ti-close"></i>
            </span>
            <div class="icon_lock">
                <img src="{{url('/')}}/public/frontend/amazy/img/svg/lock_icon.svg" alt="">
            </div>
            <div class="cart_view_text">
                <span>{{$items}} Items</span>
                <h5 class="lh-1">{{single_price($carts->sum('total_price'))}}</h5>
            </div>
        </div>
    @endif
    <!-- side_chartView_total::end  -->


    <!-- shoping_cart::start  -->
    <div class="shoping_wrapper {{$items < 1?'d-none':''}}">
        <!-- <div class="dark_overlay"></div> -->
        <div class="shoping_cart">
            <div class="shoping_cart_inner">
                <div class="cart_header d-flex justify-content-between">
                    <div class="cart_header_text">
                        <h4>{{__('amazy.Shoping Cart')}}</h4>
                        <p>{{$items}} {{__('amazy.Item’s selected')}}</p>
                    </div>
                    
                    <div class="chart_close">   
                        <i class="ti-close"></i>
                    </div>
                </div>
                @php
                    $subtotal = 0;
                    $base_url = url('/');
                    $current_url = url()->current();
                    $just_path = trim(str_replace($base_url,'',$current_url));
                @endphp
                @foreach ($carts as $key => $cart)
                    @php
                        $subtotal += $cart->price * $cart->qty;
                    @endphp
                    @if ($cart->product_type == "gift_card")
                        <div class="single_cart">
                            <div class="thumb d-flex align-items-center gap_10 mr_15">
                                {{-- <label class="primary_checkbox d-flex">
                                    <input type="checkbox">
                                    <span class="checkmark"></span>
                                </label> --}}
                                <div class="thumb_inner">
                                    <img src="{{showImage(@$cart->giftCard->thumbnail_image)}}" alt="">
                                </div>
                            </div>
                            <div class="cart_content flex-fill">
                                <a href="{{route('frontend.gift-card.show',$cart->giftCard->sku)}}">
                                    <h5>{{ textLimit(@$cart->giftCard->name, 20) }}</h5>
                                </a>
                                <div class="cart_content_text d-flex align-items-center gap_10 flex-fill flex-wrap">
                                    <div class="product_number_count style_2" data-target="amountc-1">
                                        <span class="count_single_item inumber_decrement"> <i class="ti-minus"></i></span>
                                        <input id="amountc-1" class="count_single_item input-number" type="text" value="{{$cart->qty}}">
                                        <span class="count_single_item number_increment"> <i class="ti-plus"></i></span>
                                    </div>
                                    <p><span class="prise" >{{single_price($cart->total_price)}}</span> </p>
                                </div>
                                
                            </div>
                            @if($just_path != '/checkout')
                                <div class="cart_trash_icon d-flex align-items-center  justify-content-end cursor_pointer" id="submenu_cart_btn_{{$cart->id}}">
                                    <img class="remove_from_submenu_btn" data-id="{{$cart->id}}" data-product_id="{{$cart->product_id}}" data-btn="#submenu_cart_btn_{{$cart->id}}" src="{{url('/')}}/public/frontend/amazy/img/svg/trash.svg" alt="">
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="single_cart">
                            <div class="thumb d-flex align-items-center gap_10 mr_15">
                                {{-- <label class="primary_checkbox d-flex">
                                    <input type="checkbox">
                                    <span class="checkmark"></span>
                                </label> --}}
                                <div class="thumb_inner">
                                    <img src="
                                    @if(@$cart->product->product->product->product_type == 1)
                                        {{showImage(@$cart->product->product->product->thumbnail_image_source)}}
                                    @else
                                        {{showImage(@$cart->product->sku->variant_image?@$cart->product->sku->variant_image:@$cart->product->product->product->thumbnail_image_source)}}
                                    @endif
                                    " alt="">
                                </div>
                            </div>
                            <div class="cart_content flex-fill">
                                <a href="{{singleProductURL($cart->seller->slug, $cart->product->product->slug)}}">
                                    <h5>{{ textLimit(@$cart->product->product->product_name, 20) }}</h5>
                                </a>
                                <div class="cart_content_text d-flex align-items-center gap_10 flex-fill flex-wrap">
                                    <div class="product_number_count style_2" data-target="amountc-1">
                                        <button id="sidebar_cart_minus_{{$cart->id}}" type="button" class="count_single_item inumber_decrement cart_qty_sidebar" value="-" data-value="-" data-id="{{$cart->id}}" data-product-id="{{$cart->product_id}}" data-qty="#sidebar_cart_qty_{{$cart->id}}" data-qty-minus-btn-id="#sidebar_cart_plus_{{$cart->id}}" data-maximum-qty="{{@$cart->product->product->product->max_order_qty}}" data-minimum-qty="{{@$cart->product->product->product->minimum_order_qty}}" data-stock-manage="{{@$cart->product->product->stock_manage}}" data-product-stock="{{@$cart->product->product_stock}}"> <i class="ti-minus"></i></button>
                                        <input id="sidebar_cart_qty_{{$cart->id}}" class="count_single_item input-number" type="text" value="{{$cart->qty}}" readonly>
                                        <button id="sidebar_cart_plus_{{$cart->id}}" type="button" class="count_single_item number_increment cart_qty_sidebar" value="+" data-value="+" data-id="{{$cart->id}}" data-product-id="{{$cart->product_id}}" data-qty="#sidebar_cart_qty_{{$cart->id}}" data-qty-plus-btn-id="#sidebar_cart_plus_{{$cart->id}}" data-maximum-qty="{{@$cart->product->product->product->max_order_qty}}" data-minimum-qty="{{@$cart->product->product->product->minimum_order_qty}}" data-stock-manage="{{@$cart->product->product->stock_manage}}" data-product-stock="{{@$cart->product->product_stock}}"> <i class="ti-plus"></i></button>
                                    </div>
                                    <p><span class="prise" >{{single_price($cart->total_price)}}</span> </p>
                                </div>
                                
                            </div>
                            @if($just_path != '/checkout')
                                <div class="cart_trash_icon d-flex align-items-center  justify-content-end cursor_pointer" id="submenu_cart_btn_{{$cart->id}}">
                                    <img class="remove_from_submenu_btn" data-id="{{$cart->id}}" data-product_id="{{$cart->product_id}}" data-btn="#submenu_cart_btn_{{$cart->id}}" src="{{url('/')}}/public/frontend/amazy/img/svg/trash.svg" alt="">
                                </div>
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>
            <div class="shoping_cart_subtotal d-flex justify-content-between align-items-center">
                <h4 class="m-0">{{__('common.subtotal')}}</h4>
                <span>{{single_price($subtotal)}}</span>
            </div>
            <div class="view_checkout_btn d-flex justify-content-end mb_30 flex-column gap_10">
                <a href="{{url('/cart')}}" class="amaz_primary_btn style2 text-uppercase ">{{__('defaultTheme.view_shopping_cart')}}</a>
                <a href="{{url('/checkout')}}" class="amaz_primary_btn style2 text-uppercase ">{{__('defaultTheme.proceed_to_checkout')}}</a>
            </div>
        </div>
    </div>
    <!-- shoping_cart::end  -->
@endif