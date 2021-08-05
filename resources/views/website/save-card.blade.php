@extends('website.common')
@section('title', 'Motive')

@section('content')
<?php
    require 'vendor/autoload.php';
?>
<div class="container">
    <div class="heading-top">
        <h1>Payment</h1>
    </div>
    
<script type="text/javascript">
    window.applicationId = "{{ env('ACCESS_TOKEN') }}";
    window.locationId = "{{ env('LOCATION_ID') }}";
</script>
<script src="https://js.squareup.com/v2/paymentform"></script>
    
</head>
<body>
  <!-- Begin Payment Form -->
  <div class="sq-payment-form">
    <!--
      Square's JS will automatically hide these buttons if they are unsupported
      by the current device.
  -->
  <div id="sq-walletbox">
      <button id="sq-google-pay" class="button-google-pay"></button>
      <button id="sq-apple-pay" class="sq-apple-pay"></button>
      <button id="sq-masterpass" class="sq-masterpass"></button>
      <div class="sq-wallet-divider">
        <span class="sq-wallet-divider__text">Or</span>
    </div>
</div>
<div id="sq-ccbox">
    <form id="nonce-form" novalidate action="/process-card.php" method="post">
        <div class="sq-field">
          <label class="sq-label">Card Number</label>
          <div id="sq-card-number"></div>
      </div>
      <div class="sq-field-wrapper">
          <div class="sq-field sq-field--in-wrapper">
            <label class="sq-label">CVV</label>
            <div id="sq-cvv"></div>
        </div>
        <div class="sq-field sq-field--in-wrapper">
            <label class="sq-label">Expiration</label>
            <div id="sq-expiration-date"></div>
        </div>
        <div class="sq-field sq-field--in-wrapper">
            <label class="sq-label">Postal</label>
            <div id="sq-postal-code"></div>
        </div>
    </div>
    <div class="sq-field">
      <button id="sq-creditcard" class="sq-button" onclick="onGetCardNonce(event)">
        Pay $1.00 Now
    </button>
</div>
        <!--
          After a nonce is generated it will be assigned to this hidden input field.
      -->
      <div id="error"></div>
      <input type="hidden" id="card-nonce" name="nonce">
  </form>
</div>
</div>



</div>
</div>
</div>
@endsection()
@section('js')
<script type="text/javascript" src="{{url('public/website/js/sq-payment-form.js')}}"></script>



@endsection()