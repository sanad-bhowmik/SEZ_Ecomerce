<div class="main_header_area">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="shop_header_wrapper d-flex align-items-center">
                    <div class="menu_logo">
                        <a href="{{ url('/') }}">
                            <img src="{{ showImage(app('general_setting')->logo) }}" alt="">
                        </a>
                    </div>
                    @foreach($menus as $key => $menu)
                        @if($menu->menu_type == 'multi_mega_menu')
                            <div class="dropdown show category_menu">
                                <a class="Categories_togler">
                                    {{ textLimit($menu->name, 25) }} 
                                    <i class="fas fa-chevron-down"></i>
                                </a>
                                <ul class="dropdown_menu catdropdown_menu">
                                    @foreach(@$menu->menus as $key => $menu)
                                        @if(@$menu->menu->menu_type == 'mega_menu' && @$menu->menu->status == 1)
                                            <li><a href="#" class="dropdown-item has_arrow d-flex align-items-center">
                                                <i class="{{@$menu->menu->icon}}"></i>
                                                {{ textLimit(@$menu->menu->name, 25) }}</a>
                                                <!-- 2nd level  --> 
                                                <!-- mega_width_menu  -->
                                                <ul class="mega_width_menu">
                                                    @if(@$menu->menu->columns->count())
                                                        @php
                                                            $is_same = 1;
                                                            $column_size = $menu->menu->columns[0]->size;

                                                            foreach($menu->menu->columns as $key => $column){
                                                            if($column->size != $column_size){
                                                                $is_same =0;
                                                            }
                                                            }
                                                        @endphp
                                                        <!-- single_menu  -->
                                                        @foreach(@$menu->menu->columns as $key => $column)
                                                            <li class="pt-0 
                                                                @if($column->size == '1/1')
                                                                flex_100
                                                                @elseif($column->size == '1/2')
                                                                flex_50
                                                                @elseif($column->size == '1/3')
                                                                flex_33
                                                                @elseif($column->size == '1/4')
                                                                flex_25
                                                                @endif
                                                            ">
                                                                <a class="mega_metu_title" href="#">{{ textLimit($column->column, 25) }}</a>
                                                                <ul>
                                                                    @foreach(@$column->elements as $key => $element)
                                                                        @if($element->type == 'link')
                                                                        <li><a target="{{$element->is_newtab == 1?'_blank':''}}" href="@if($element->link != null)
                                                                            {{url($element->link)}}
                                                                            @else
                                                                            javascript:void(0);
                                                                            @endif">{{textLimit($element->title, 25)}}</a></li>
                                
                                                                        @elseif($element->type == 'category' && $element->category->status == 1)
                                                                            <li><a target="{{$element->is_newtab == 1?'_blank':''}}" href="{{route('frontend.category-product',['slug' => @$element->category->slug, 'item' =>'category'])}}">{{ ucfirst(textLimit($element->title, 25)) }}</a></li>
                                
                                                                        @elseif(@$element->type == 'product' && @$element->product)
                                                                        <li><a target="{{$element->is_newtab == 1?'_blank':''}}" href="{{singleProductURL(@$element->product->seller->slug, $element->product->slug)}}">{{ ucfirst(textLimit($element->title,25)) }}</a></li>
                                
                                                                        @elseif($element->type == 'brand' && $element->brand->status == 1)
                                                                        <li><a target="{{$element->is_newtab == 1?'_blank':''}}" href="{{route('frontend.category-product',['slug' => @$element->brand->slug, 'item' =>'brand'])}}">{{ ucfirst(textLimit($element->title, 25)) }}</a></li>
                                
                                                                        @elseif($element->type == 'page' && $element->page->status == 1)
                                                                                @if(!isModuleActive('Lead') && $element->page->module == 'Lead')
                                                                                    @continue
                                                                                @endif
                                                                        <li><a target="{{$element->is_newtab == 1?'_blank':''}}" href="{{ url(@$element->page->slug) }}">{{ ucfirst(textLimit($element->title, 25)) }}</a></li>
                                
                                                                        @elseif($element->type == 'tag')
                                                                        <li><a target="{{$element->is_newtab == 1?'_blank':''}}" href="{{route('frontend.category-product',['slug' => @$element->tag->name, 'item' =>'tag'])}}">{{ ucfirst(textLimit($element->title,25)) }}</a></li>
                                
                                                                        @endif
                                                                    @endforeach
                                                                </ul>
                                                            </li>
                                                        @endforeach
                                                    @endif
                                                    <!-- single_menu  -->
                                                    <li class="img_menu pt-0 position-relative {{@$menu->menu->menuAds->status?'':'d-none'}}">
                                                        <div class="sub_menu_bg_img position-absolute end-0 bottom-0">
                                                            <img class="img-fluid lazyload" data-src="{{showImage(@$menu->menu->menuAds->image)}}" src="{{showImage(themeDefaultImg())}}" alt="">
                                                        </div>
                                                        <ul>
                                                            <li>
                                                                <h6>{{@$menu->menu->menuAds->subtitle}}</h6>
                                                            </li>
                                                            <li>
                                                            <h4>{{@$menu->menu->menuAds->title}}</h4>
                                                            </li>
                                                            <li>
                                                                <a class="shop_now" href="{{@$menu->menu->menuAds->link}}">Shop Now »</a>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                </ul>
                                                <!--/ mega_width_menu -->
                                            </li>
                                        @endif
                                    @endforeach
                                    
                                </ul>
                            </div>
                        @endif
                    @endforeach
                        <!-- main_menu_start  -->
                    <div class="main_menu  d-none d-lg-block">
                        <nav>
                            @php
                                $main_menu = $menus->where('menu_type','normal_menu')->where('menu_position', 'main_menu')->first();
                                $function = null;
                            @endphp
                            
                            <ul id="mobile-menu">
                                @if($main_menu)
                                    @foreach($main_menu->elements as $element)
                                        @if($element->type == 'page')
                                            @if(!isModuleActive('Lead') && $element->page->module == 'Lead')
                                                @continue
                                            @endif
                                            @if(!isModuleActive('MultiVendor') && $element->page->slug == 'merchant' || !isModuleActive('MultiVendor') && $element->page->module == 'MultiVendor')
                                                @continue
                                            @endif
                                            @if($element->childs->count() > 0)
                                                <li class="submenu_active"><a href="{{ url(@$element->page->slug) }}" target="{{$element->is_newtab == 1?'_blank':''}}">{{ ucfirst(textLimit($element->title, 20)) }}  <i class="ti-angle-down"></i></a>
                                                    <ul class="submenu">
                                                        @foreach($element->childs as $key => $element)
                                                            @if($element->type == 'page')
                                                                @if(!isModuleActive('Lead') && $element->page->module == 'Lead')
                                                                    @continue
                                                                @endif
                                                                @if(!isModuleActive('MultiVendor') && $element->page->slug == 'merchant' || !isModuleActive('MultiVendor') && $element->page->module == 'MultiVendor')
                                                                    @continue
                                                                @endif
                                                                <li class="submenu_active">
                                                                    <a href="{{ url(@$element->page->slug) }}" target="{{$element->is_newtab == 1?'_blank':''}}">{{ ucfirst(textLimit($element->title, 20)) }} @if($element->childs->count()) <i class="ti-angle-down"></i> @endif</a>
                                                                </li>
                                                            @elseif($element->type == 'category')
                                                                <li class="">
                                                                    <a href="{{route('frontend.category-product',['slug' => $element->category->slug, 'item' =>'category'])}}" target="{{$element->is_newtab == 1?'_blank':''}}">{{textLimit($element->title,20)}}</a>
                                                                </li>
                                                            @elseif($element->type == 'brand')
                                                                <li class="">
                                                                    <a href="{{route('frontend.category-product',['slug' => $element->brand->slug, 'item' =>'brand'])}}" target="{{$element->is_newtab == 1?'_blank':''}}">{{textLimit($element->title,20)}}</a>
                                                                </li>
                                                            @elseif($element->type == 'tag')
                                                                <li class="">
                                                                    <a href="{{route('frontend.category-product',['slug' => $element->tag->name, 'item' =>'tag'])}}" target="{{$element->is_newtab == 1?'_blank':''}}">{{textLimit($element->title,20)}}</a>
                                                                </li>
                                                            @elseif($element->type == 'product' && @$element->product)
                                                                <li class="">
                                                                    <a href="{{singleProductURL(@$element->product->seller->slug, @$element->product->slug)}}" target="{{$element->is_newtab == 1?'_blank':''}}">{{textLimit($element->title,20)}}</a>
                                                                </li>
                                                            @elseif($element->type == 'link')
                                                                <li class="">
                                                                    <a href="{{ $element->link }}" target="{{$element->is_newtab == 1?'_blank':''}}">{{textLimit($element->title,20)}}</a>
                                                                </li>
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                </li>
                                            @else
                                                <li class="">
                                                    <a href="{{ url(@$element->page->slug) }}" target="{{$element->is_newtab == 1?'_blank':''}}">{{ ucfirst(textLimit($element->title, 20)) }}</a>
                                                </li>
                                            @endif
                                        @elseif($element->type == 'category')
                                            <li class="">
                                                <a href="{{route('frontend.category-product',['slug' => $element->category->slug, 'item' =>'category'])}}" target="{{$element->is_newtab == 1?'_blank':''}}">{{textLimit($element->title,20)}}</a>
                                            </li>
                                        @elseif($element->type == 'brand')
                                            <li class="">
                                                <a href="{{route('frontend.category-product',['slug' => $element->brand->slug, 'item' =>'brand'])}}" target="{{$element->is_newtab == 1?'_blank':''}}">{{textLimit($element->title,20)}}</a>
                                            </li>
                                        @elseif($element->type == 'tag')
                                            <li class="">
                                                </li><a href="{{route('frontend.category-product',['slug' => $element->tag->name, 'item' =>'tag'])}}" target="{{$element->is_newtab == 1?'_blank':''}}">{{textLimit($element->title,20)}}</a>
                                            </li>
                                        @elseif($element->type == 'product' && @$element->product)
                                            <li class="">
                                                <a href="{{singleProductURL(@$element->product->seller->slug, @$element->product->slug)}}" target="{{$element->is_newtab == 1?'_blank':''}}">{{textLimit($element->title,20)}}</a>
                                            </li>
                                        @elseif($element->type == 'link')
                                            <li class="">
                                                <a href="{{ $element->link }}" target="{{$element->is_newtab == 1?'_blank':''}}">{{textLimit($element->title,20)}}</a>
                                            </li>
                                        @elseif($element->type == 'function' & $element->element_id == 1)
                                            @php
                                                $function = $element;
                                            @endphp
                                        @endif
                                        
                                    @endforeach
                                @endif
                                
                            </ul>
                        </nav>
                    </div>
                    @if($function)
                    <div class="mobile_lang d-lg-none">
                        <div class="single_top_lists position-relative  d-flex align-items-center shoping_language">
                            <div class="">
                                <div class="language_toggle_btn gj-cursor-pointer d-flex align-items-center gap_5 ">
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
                                            <select class="amaz_select6 wide mb_20 middle_menu_select" name="lang">
                                                @foreach($langs as $key => $lang)
                                                <option {{ $locale==$lang->code?'selected':'' }} value="{{$lang->code}}">
                                                    {{$lang->native}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="lag_select">
                                            <span class="font_12 f_w_500 text-uppercase mb_10 d-block">{{ __('defaultTheme.currency') }}</span>
                                            <select class="amaz_select6 wide middle_menu_select" name="currency">
                                                @foreach($currencies as $key => $item)
                                                <option class="d-block" {{$currency_code==$item->code?'selected':'' }}
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
                    </div>
                    @endif 
                    
                    <div class="main_header_media d-none d-xl-flex">
                        @if (isset($new_user_zone))
                            <a href="{{ route('frontend.new-user-zone', $new_user_zone->slug) }}" class="single_top_lists d-flex align-items-center d-none d-lg-inline-flex">
                                <img src="{{showImage('frontend/amazy/img/amaz_icon/new_user.svg')}}" alt="">
                                <span>{{ __('defaultTheme.new_user_zone') }}</span>
                            </a>
                            <span class="vertical_line style2 d-none d-lg-inline-flex"></span>
                        @endif
                        @if (isset($flash_deal))
                            <a href="{{ route('frontend.flash-deal', $flash_deal->slug) }}" class="single_top_lists d-flex align-items-center d-none d-md-inline-flex">
                                <img src="{{showImage('frontend/amazy/img/amaz_icon/deals.svg')}}" alt="">
                                <span>{{__('amazy.Daily Deals')}}</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>