@extends('website.common')
@section('title', 'Motive')

@section('content')
<style type="text/css">
  .heading-top {
    padding: 77px 0 0 0;

}

input#copyy {
    max-width: 248px;
    width: 100%;
    padding: 15px 11px;
    text-align: center;


}
</style>
<div class="container">
    <div class="heading-top">
        <h1>Referral code</h1>
    </div>
    <div class="tabs inbox">
        <div class="col-md-8 col-md-offset-2">
            <div id="tab1" class="tab active">
              <div class="new-formula-referal text-center">
                  <figure><img src="{{url('public/website/images/new-referal.png')}}"></figure>
              </div>
              <div class="bottom-referal text-center">
                  <h3>YOUR REFERRAL CODE</h3>
                  <!-- <a href="javascript:void" id="copyy" class="new-homes">{{$user->refferal_code}}</a> -->
                  <input type="text" class="new-homes" name="copy" id="copyy" value="{{$user->refferal_code}}" style="" readonly>
              </div>
              <div class="bottom-referal text-center">
                  <h3>SHARE YOUR REFERRAL CODE WITH FRIENDS TO WIN PRIZES</h3>
                  <button type="submit" onclick="copy()" class="new-homes">share</button>
              </div>
          </div>
      </div>
  </div>
</div>

@endsection()
@section('js')
<script type="text/javascript">
  function copy() {
  var copyText = document.getElementById("copyy");
  copyText.select(); 
  copyText.setSelectionRange(0, 99999);
  document.execCommand("copy");
  alert("Referral code is copied. Now you can share it.");
}

</script>
@endsection()