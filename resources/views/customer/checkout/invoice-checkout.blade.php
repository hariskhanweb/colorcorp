@extends('layouts.customer-layout')

@section('title', __('Checkout'))

@section('content')
@php
  header('Access-Control-Allow-Origin: *');
  $fullname=Auth::user()->name;
  $user_id = Auth::user()->id;
  $shopslug=Helper::getShopslug(Auth::user()->vendor_id);

@endphp

<section class="cart_page lg:py-14 py-8 px-3">
  <div class="container mx-auto">
    <form id="checkout-form" method="post" class="w-full" action="{{ route('place.invoiceOrder', ['vendor_name' => $shopslug ]) }}">
      {{ csrf_field() }}
      <div class="row flex flex-wrap">
        <div class="lg:w-1/5 w-full"></div>
        <div class="lg:w-3/5 w-full lg:pl-3 pl-0 lg:mt-0 mt-5 lg:p-10" data-animation="slideInLeft" data-animation-delay=".1s">
          <h4 class="text-3xl text-black font-futura-med lg:mb-6 mb-4">{{ __('Your installation invoice') }}</h4>
          <div class="p-4 bg-gray-100">

            <div class="flex flex-wrap mb-2">
              <div class="w-1/2 flex flex-wrap items-center">
                <p><strong>{{ __('Order Number') }} : </strong>{{$Orders->order_number}}</p>
              </div>
            </div>

            <div class="flex flex-wrap mb-2">
              <div class="w-1/2 flex flex-wrap items-center">
                <p><strong>{{ __('Installation Invoice Number') }} : </strong>{{$ICdata->inv_number}}</p>
              </div>
            </div>
            <br/>
            <!-- Product Row -->
            <div class="flex flex-wrap mb-2">
              <div class="w-1/2">
                <p><strong>{{ __('Product') }}</strong></p>

              </div>
              <div class="w-1/2 flex justify-end">
                <p><strong>{{ __('Installation Charges') }}</strong></p>
              </div>
            </div>
            
            <div class="flex flex-wrap mb-2 pb-2 border-b border-solid border-gray-400">
              @foreach($ICIdata as $item)
              @php
              $fimage = Helper::getFeaturedImage($item->product_id);
              @endphp
              <div class="w-1/2 flex flex-wrap items-center">
                <img src="{{ asset('storage/'.$fimage) }}" alt="" class="inline mr-2 product_img"> <span>{{$item->order_item}}</span>
                <div class="moadl-btn" style="color:#007934;text-align: center;width: 100%;"> 
                  <a class="py-2 px-4 showm" onclick="toggleModal({{$ICdata->order_id}},'{{$item->order_item}}')">Show More</a></div>
              </div>
              <div class="w-1/2 flex flex-wrap items-center justify-end">
                {{setting('payment-setting.currency')}}{{number_format($item->charges, 2)}}
              </div>
              @endforeach

            </div>
            <!-- End Product Row -->

            <div class="flex flex-wrap mb-2">
              <div class="w-1/2 flex flex-wrap items-center">
                <p><strong>{{ __('Total Installation Charge') }}</strong></p>
              </div>
              <div class="w-1/2 flex flex-wrap items-center justify-end">
                <p><strong>{{setting('payment-setting.currency')}}{{number_format($ICdata->total_charges, 2)}}</strong></p>
              </div>
            </div>
          </div>

          <p class="text-black text-base p-4 bg-gray-100 rounded-md ml-0 mt-3">{{ __('Your personal data will be used to process your order, support your experience throughout this website, and for other purposes described in our privacy policy.') }}</p>

          <div class="p-4 card-wrapper">
            <div id="securepay-ui-container"></div>
            <input id="securepayapi-token" name="securePayApiToken" type="hidden">
            <input name="order_number" id="order_number" type="hidden" value="" /> 
            <input name="transaction_id" id="transaction_id" type="hidden" value="" /> 
            <input name="ICdata_id" id="ICdata_id" type="hidden" value="{{$ICdata->id}}" />
            <input name="order_id" id="order_id" type="hidden" value="{{$Orders->id}}" />  
          </div>
          
          <button id="plord" type="button" onClick="onPlaceOrderClick();" class="green_btn py-4 px-12" ><span>{{ __('Place order') }}</span></button>  
          <button type="button" class="reset_btn" onclick="mySecurePayUI.reset();">Reset</button>
        </div>
        <div class="lg:w-1/5 w-full"></div>
      </div>
    </form>
  </div>

  <!----modal-->

  <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="closeModal()"><i class="fa fa-times"></i> </button>
      </div>
      <div class="modal-body">
        <div id="cart-details">
        </div>
      </div>
    </div>
  </div>
</div>
{{-- <div class="fixed z-10 overflow-y-auto top-0 w-full left-0 hidden" id="modal">
    <div class="flex items-center justify-center min-height-100vh pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <div class="fixed inset-0 transition-opacity">
        <div class="absolute inset-0 bg-gray-900 opacity-75" />
      </div>
      <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
      <div class="inline-block align-center bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
        <div class="bg-gray-200 px-4 py-3 text-right">
          <button type="button" class="py-2 px-4 bg-gray-500 text-white rounded hover:bg-gray-700 mr-2" onclick="closeModal()"><i class="fa fa-times"></i> 
          </button>
        </div>
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 text-center" id="cart-details">  
        </div>
      </div>
    </div>
  </div> --}}
  <!---modal end-->
</section>
<div class="processing_loader">
  <div class="inner_loader">
    <div class="process_message">
      <div class="process_icon"><i class="fa fa-spinner fa-spin"></i></div>
      We are processing your payment.<br/>
      Please wait....<br/>
      Don't close browser until payment completed.
    </div>
  </div>
</div>

<script id="securepay-ui-js" src="https://payments-stest.npe.auspost.zone/v3/ui/client/securepay-ui.min.js"></script>
<script type="text/javascript">
  function showMore(e) {
    let cartid = $(e).attr('data-id');
    var txt = $(".content_" + cartid).is(':visible') ? 'Show More' : 'Show Less';
    $(".show_hide_" + cartid).text(txt);
    $('.content_' + cartid).slideToggle(200);
  }

  function toggleModal(order_id,itemname) {
    getProductDetails(order_id,itemname);
  }

  function closeModal(){
    $("#modal").hide();
  }

  function getProductDetails(order_id,itemname) {
    var action = "{{url('get-installation-product')}}";
    $.ajax({
      type: 'POST',
      url: action,
      data: {
        order_id: order_id,
        itemname: itemname,
        _token: "{{ csrf_token() }}",
      },
      dataType: 'JSON',
      success: function(data) {
        if (data.success == 1) {
          $('#cart-details').html(data.result);
          $("#modal").show();
        }
      }
    });
  }




  
function onPlaceOrderClick(){
  var isvalidateBilling  = true; 
  if(isvalidateBilling ){
    mySecurePayUI.tokenise();
  }
}

var mySecurePayUI = new securePayUI.init({
  containerId: 'securepay-ui-container',
  scriptId: 'securepay-ui-js',
  mode: 'dcc',
  checkoutInfo: {
    orderToken: '123456789012587412369857410dghdgdgdgdgd'
  },    
  clientId: "{{setting('payment-setting.client_id')}}",
  merchantCode: "{{setting('payment-setting.merchant_code')}}",
  card: {
      allowedCardTypes: ['visa', 'mastercard'],
      showCardIcons: false,
      onCardTypeChange: function(cardType) {
        // card type has changed
        // alert("card change");
      },
      onBINChange: function(cardBIN) {
        // card BIN has changed
      },
      onFormValidityChange: function(valid) {
        // validateFormFields();
      },
      onDCCQuoteSuccess: function(quote) {
        // dynamic currency conversion quote was retrieved 
      },
      onDCCQuoteError: function(errors) {
        // quote retrieval failed  
      },
      onTokeniseSuccess: function(tokenisedCard) {
        $("#securepayapi-token").val(tokenisedCard.token);
        jQuery.ajax({
            url: '/api/invoice-checkout-payment',
            data: {
              securePayApiToken: tokenisedCard.token,
              saveCard: 0,
              user_id: @php echo $user_id; @endphp,
              ICdata_id: @php echo $ICdata->id;@endphp,
              _token: "{{ csrf_token() }}"
            },
            dataType: 'json',
            method: 'post',
            beforeSend: function () {
              $(".processing_loader").addClass("active"); 
            },
            complete: function () {
              // $(".processing_loader").removeClass("active"); 
            },
            success: function (json) {
              // console.log(json)
              if (json['success'] && json['orderId']) {
                $("#order_number").val(json['orderId']);
                $("#transaction_id").val(json['bankTransactionId']);
                setTimeout(function(){
                  // $(".processing_loader").removeClass("active"); 
                  $("#checkout-form").submit();
                }, 1000);
              } else {
                $(".processing_loader").removeClass("active"); 
                if (json['message']) {
                  $( ".card-wrapper" ).after( '<div id="transaction-error">'+json['message']+'</div>' );
                } else {
                  $( ".card-wrapper" ).after( '<div id="transaction-error">NOT Working</div>' );
                }
              }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
        // card was successfully tokenised or saved card was successfully retrieved 
      },
      onTokeniseError: function(errors) {
        // tokenization failed
      }
  },
  style: {
    backgroundColor: 'rgba(135, 206, 250, 0.1)',
    padding: '10px',
    label: {
      font: {
          family: 'Arial, Helvetica, sans-serif',
          size: '1.1rem',
          color: 'darkblue'
      }
    },
    input: {
     font: {
         family: 'Arial, Helvetica, sans-serif',
         size: '1.1rem',
         color: 'darkblue'
     }
   }  
  },
  onLoadComplete: function () {
    // the UI Component has successfully loaded and is ready to be interacted with
  }
});

</script>

<style type="text/css">
  .card-wrapper{ background: rgb(232 246 253); margin: 15px 0px; }
  #securepay-ui-container{ margin: 15px 0px; }
  #securepay-ui-container iframe{ width:100%; }
  .reset_btn {
    background-color: #fff;
    color: #474747;
    padding: 10px 20px;
    border-radius: 4px;
    font-size: 16px;
    line-height: 19px;
    font-family: 'futura-med';
    border: 1px solid #474747;
    transition: all 0.3s;
    font-weight: 400;
    display: inline-block;
    overflow: hidden;
    position: relative;
    transition: all 0.3s;
    float: right;
  }
  #transaction-error{
    border: solid 1px #cfcfcf;
    padding: 10px;
    text-align: center;
    margin-bottom: 20px;
    color: red;
  }
  .processing_loader {
    display: none;
    position: fixed;
    top: 0px;
    left: 0px;
    width: 100%;
    height: 100%;
    z-index: 99991;
    background-color: rgba(255, 255, 255, 0.5);
  }
  .processing_loader.active {
    display: block;
  }
  .inner_loader {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100vw;
    height: 100vh;
  }
  .process_message {
      background-color: #fff;
      padding: 30px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      gap: 15px;
      border-radius: 30px;
      box-shadow: 0px 0px 30px 0px #00000026;
      border: 1px solid #ddd;
      font-size: 24px;
      width: 100%;
      max-width: 545px;
      text-align: center;
      color: #5c5c5c;
  }
  .process_icon i{ font-size:40px; color: #007934; }
  span.data-label {
    display: inline-block;
    width: 140px;
  }
  #modal{ height: auto; }
  #modal .modal-header{ text-align: right; }
  #cart-details p.w-full{ margin-bottom: 7px; }
</style>
@endsection
