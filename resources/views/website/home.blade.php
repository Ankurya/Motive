@extends('website.common')
@section('title', 'Motive')
@section('content')
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.2/jquery.min.js"> </script>

<style type="text/css">
  .modal-header h4.modal-title {
  text-align: left;
    max-width: 65%;
    line-height: 45px;
    font-size: 26px;
  }
 .modal-footer .new-button{
        padding: 9px 50px;
        border: 0;
            font-size: 20px;
                color: #000;
  }
  .modal-footer {
    text-align: left;
}
.modal-body input.form-control {
    background-color: #fff;
    border-radius: 26px;
    color: #000;
    max-width: 556px;
    width: 100%;
        height: 50px;

}
.modal-body p {
    font-size: 30px;
    font-size: 24px;
    color: #e0ca7f;
    padding-bottom: 13px;
}
.modal-body,.modal-header{
  background:transparent;
}
.modal-dialog {
    max-width: 761px;
    width: 100%;
    margin: 100px auto;
}
.modal-header{
  border-bottom: 0;
}
.modal-footer {
    padding-top: 0;
}
.modal-body input.form-control::placeholder{
  color:#000;
}
.new-button{
  color:#000;
}
.new-icons.icons figure img {
    border: 1px solid #f5cf5f;
} 
.new-bottom-effects figure img{
      border: 1px solid #f5cf5f;

}

</style>
<section class="banner-home">
   <div class="container">
      <div class="row">
         <div class="col-md-12 text-center">
            <div class="logo-info">
               <!-- <p>Download App On:</p> -->
               <div class="new-icons icons">
                  <ul>
                     <li><figure><a href="https://itunes.apple.com/us/app/discover-your-motiv/id1367825584?mt=8"> <img src="{{url('public/website/images/apple-logo.png')}}"></a></figure></li>
                    <li><figure><a href="https://play.google.com/store/apps/details?id=com.app.motiv&hl=en"><img src="{{url('public/website/images/google-play.png')}}"></a></figure></li>
                 </ul>
             </div>
         </div>
     </div>
 </section>
 <section class="apps-screen">
   <div class="container">
      <div class="heading"><h3>app screenshots</h3></div>
      <div class="row">
         <div class="col-md-12 text-center">
            <div class="new-icons">
              <div class="col-sm-4">
               <figure><img src="{{url('public/website/images/search-home.png')}}"><span>FIND</span></figure>
           </div>
           <div class="col-sm-4">
              <figure><img src="{{url('public/website/images/card-new.png')}}"><span>CREATE</span></figure>
          </div>
          <div class="col-sm-4">

           <figure><img src="{{url('public/website/images/campuss.png')}}"><span>DISCOVER</span></figure>
       </div>
   </div>
</div>
<div class="row">
 <div class="new-mobile-section">
    <div class="col-md-4">
     <figure>
        <img src="{{url('public/website/images/mobile-1.png')}}">
      </figure>
    </div>
    <div class="col-md-4">
      <figure>
        <img src="{{url('public/website/images/mobile-2.png')}}">
      </figure>
    </div>
    <div class="col-md-4">
      <figure>
        <img src="{{url('public/website/images/mobile-3.png')}}">
      </figure>
    </div>
 </div>
</div>
<div class="new-bottom-effects">
  <figure><a href="https://itunes.apple.com/us/app/discover-your-motiv/id1367825584?mt=8"> <img src="{{url('public/website/images/apple-logo.png')}}"></a></figure>
  <figure><a href="https://play.google.com/store/apps/details?id=com.app.motiv&hl=en"><img src="{{url('public/website/images/google-play.png')}}"></a></figure>
  <p>download now and</p>
  <h3>“discover your motiv today”</h3>
</div>
</div>
</div>
</section>
<section class="motiv">
   <div class="container">
      <div class="heading"><h3>why should you use motiv?</h3></div>
      <div class="row">
         <div class="col-sm-4">
            <div class="new-size">
               <figure><img src="{{url('public/website/images/icon1.png')}}"></figure>
               <h3>Exclusive Events</h3>
               <p>Lorem lpsum is simply dummy text of the 
               printing and typesetting industry.</p>
           </div>
       </div>
       <div class="col-sm-4">
        <div class="new-size">
           <figure><img src="{{url('public/website/images/icon2.png')}}"></figure>
           <h3>invitation</h3>
           <p>Lorem lpsum is simply dummy text of the 
           printing and typesetting industry.</p>
       </div>
   </div>
   <div class="col-sm-4">
    <div class="new-size">
       <figure><img src="{{url('public/website/images/icon3.png')}}"></figure>
       <h3>motiv membership</h3>
       <p>Lorem lpsum is simply dummy text of the 
       printing and typesetting industry.</p>
   </div>
</div>
<div class="col-sm-4">
    <div class="new-size">
       <figure><img src="{{url('public/website/images/icon4.png')}}"></figure>
       <h3>information hub</h3>
       <p>Lorem lpsum is simply dummy text of the 
       printing and typesetting industry.</p>
   </div>
</div>
<div class="col-sm-4">
    <div class="new-size">
       <figure><img src="{{url('public/website/images/icon6.png')}}"></figure>
       <h3>shortlist your event</h3>
       <p>Lorem lpsum is simply dummy text of the 
       printing and typesetting industry.</p>
   </div>
</div>
<div class="col-sm-4">
    <div class="new-size">
       <figure><img src="{{url('public/website/images/icon6.png')}}"></figure>
       <h3>e-ticket & guest</h3>
       <p>Lorem lpsum is simply dummy text of the 
       printing and typesetting industry.</p>
   </div>
</div>
</div>
<div class="new-right-left">
   <a href="{{url('website/current-events')}}" class="new-button">FIND EVENT</a>
</div>
</section>
<section class="motiv again rule">
   <div class="container">
      <div class="heading"><h3>list your events on motiv</h3></div>
      <div class="row">
         <div class="col-sm-4">
            <div class="new-size">
               <figure><img src="{{url('public/website/images/quick1.png')}}"></figure>
               <h3>quick payment</h3>
               <p>Lorem lpsum is simply dummy text of the 
               printing and typesetting industry.</p>
           </div>
       </div>
       <div class="col-sm-4">
        <div class="new-size">
           <figure><img src="{{url('public/website/images/quick2.png')}}"></figure>
           <h3>manage events on 
           app & site</h3>
           <p>Lorem lpsum is simply dummy text of the 
           printing and typesetting industry.</p>
       </div>
   </div>
   <div class="col-sm-4">
    <div class="new-size">
       <figure><img src="{{url('public/website/images/quick3.png')}}"></figure>
       <h3>analytics</h3>
       <p>Lorem lpsum is simply dummy text of the 
       printing and typesetting industry.</p>
   </div>
</div>
<div class="col-sm-4">
    <div class="new-size">
       <figure><img src="{{url('public/website/images/quick4.png')}}"></figure>
       <h3>live event date</h3>
       <p>Lorem lpsum is simply dummy text of the 
       printing and typesetting industry.</p>
   </div>
</div>
<div class="col-sm-4">
    <div class="new-size">
       <figure><img src="{{url('public/website/images/quick5.png')}}"></figure>
       <h3>promotional
       packages</h3>
       <p>Lorem lpsum is simply dummy text of the 
       printing and typesetting industry.</p>
   </div>
</div>
<div class="col-sm-4">
    <div class="new-size">
       <figure><img src="{{url('public/website/images/icon6.png')}}"></figure>
       <h3>sell tickets/guestlist</h3>
       <p>Lorem lpsum is simply dummy text of the 
       printing and typesetting industry.</p>
   </div>
</div>
</div>
<div class="new-right-left">
   <a href="{{url('website/current-events')}}" class="new-button bottom" data-toggle="modal" data-target="#myModal">list your event</a>
</div>
</div>
</section>
<!-- Trigger the modal with a button -->

<!-- Modal -->
<div id="myModal-new" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
         <div class="">
          <button type="button" class="close" data-dismiss="modal"><img src="{{url('public/website/images/popup-cross.png')}}"></button>
      </div>
       
        <h4 class="modal-title">MOTIV IS THE UTLIMATE WEAPON FOR DISCOVERING THE ATTEND EVENTS NEAR YOU!</h4>
      </div>
    <form>
      <div class="modal-body">
        <div style="padding:11px;font-weight: 400;" class="alert alert-success alert-dismissible text-center alertzzz test1"></div>
        <p>ENTER YOUR EMAIL TO BE SENT A DOWNLOAD LINK</p>
        <input type="text" name="email" id="email" class="form-control" placeholder="Enter Email">
        <span class="text-danger error email_error">{{$errors->first('email')}}</span>
      </div>
      <div class="modal-footer">
        <button type="button" onclick="return submit_form5();" class="new-button">SUBMIT</button>
      </div>
    </form>
    </div>

  </div>
</div>

<script type="text/javascript">
$(document).ready(function(){
   setTimeout(function(){
       $('#myModal-new').modal('show');
   }, 30000);
});
</script>

<script type="text/javascript">
  $('.test1').hide();
  $('.loader').hide();

  $(".loderclose").click(function(){
      $('.loader').show();
    });
  function submit_form5(){
    var email = $('#email').val();
    var data = {
      '_token': "{{csrf_token()}}",
      'email': email,

  };

  $.ajax({
      url:"{{url('website/send-link')}}",
      type:'POST',
      data:data,

      success: function(res){
        if(res.message) {
            $(".alertzzz").text(res.message).show();

            $('.email_error').hide();
        }
        setTimeout(function(){
            $(".alertzzz").hide();
            $('#myModal-new').modal('hide');
            $('.email_error').hide();
        }, 3000);
        console.log(res)
    },
    error: function(data, textStatus, xhr) {
        if(data.status == 422){
          var data = data.responseJSON;
          var email_error = data.errors['email'] ? data.errors['email'] : 0;
            if (email_error) { 
                $('.email_error').html(email_error);
                $('.email_error').show();
                $('.loader').hide();

           }else{
              $('.email_error').hide();
           }           

        } 
      }

});
}
</script> 
<script type="text/javascript">
  $(".hide").hide();
</script>
@endsection()
