@php
    if (env('Jazz_MODE') == "sandbox") {
        $PAYU_BASE_URL = "https://sandbox.jazzcash.com.pk/CustomerPortal/transactionmanagement/merchantform/";
    }
    else {
        $PAYU_BASE_URL = env('JAZZ_LIVE_URL');
    }
    $pp_Amount 	= ($total_amount - $coupon_am) * 100;
    $DateTime 		= new \DateTime();
    $pp_TxnDateTime = $DateTime->format('YmdHis');
    $ExpiryDateTime = $DateTime;
    $ExpiryDateTime->modify('+' . 1 . ' hours');
    $pp_TxnExpiryDateTime = $ExpiryDateTime->format('YmdHis');
    $pp_TxnRefNo = 'T'.$pp_TxnDateTime;
    $post_data =  array(
        "pp_Version" 			=> "2.0",
        "pp_IsRegisteredCustomer"=>"No",
        "pp_TxnType" 			=> "MPAY",
        "pp_Language" 			=> "EN",
        "pp_MerchantID" 		=> env('Jazz_MERCHANT_ID'),
        "pp_SubMerchantID" 		=> "",
        "pp_Password" 			=> env('Jazz_PASSWORD'),
        "pp_BankID" 			=> "",
        "pp_ProductID" 			=> "",
        "pp_TxnRefNo" 			=> $pp_TxnRefNo,
        "pp_Amount" 			=> $pp_Amount,
        "pp_TxnCurrency" 		=> "PKR",
        "pp_TxnDateTime" 		=> $pp_TxnDateTime,
        "pp_BillReference" 		=> "checkoutPay",
        "pp_Description" 		=> "Checkout Purpose Payment",
        "pp_TxnExpiryDateTime" 	=> $pp_TxnExpiryDateTime,
        "pp_ReturnURL" 			=> route('jazzcash.payment_status'),
        "pp_SecureHash" 		=> "",
        "ppmpf_1" 				=> "1",
        "ppmpf_2" 				=> "2",
        "ppmpf_3" 				=> "3",
        "ppmpf_4" 				=> "4",
        "ppmpf_5" 				=> "5",
    );

    $str = '';
    foreach($post_data as $key => $value){
        if(!empty($value)){
            $str = $str . '&' . $value;
        }
    }

    $str = env('Jazz_SALT').$str;

    $pp_SecureHash = hash_hmac('sha256', $str, env('Jazz_SALT'));

    $post_data['pp_SecureHash'] = $pp_SecureHash;
@endphp

<form id="contactForm" action="{{ $PAYU_BASE_URL }}" class="p-0" method="POST" class="d-none">

    @foreach($post_data as $key => $value)
    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
    @endforeach
    <button class="btn_1 d-none" type="submit" id="jazzcash_btn">{{ __('wallet.continue_to_pay') }}</button>
</form>