<!-- header start here -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,  initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <title>MOTIV</title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,200i,300,400,400i,500,500i,600" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    <link rel="icon" href="{{url('public/website/images/fav.png')}}" type="image/x-icon">
    <link href="{{url('public/website/css/font-awesome.min.css')}}" rel="stylesheet">
    <link href="{{url('public/website/css/owl.carousel.css')}}" rel="stylesheet">
    <!-- <link href="{{url('public/website/css/wow.css')}}" rel="stylesheet"> -->
    <link href="{{url('public/website/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{url('public/website/css/style.css')}}" rel="stylesheet">
    <link href="{{url('public/website/css/responsive.css')}}" rel="stylesheet">
    <!-- <link href="{{url('public/website/css/normalize.css')}}" rel="stylesheet"> -->
    <link href="{{url('public/website/date-time/style.css')}}" rel="stylesheet">
     <link rel="stylesheet" type="text/css" href="https://cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css">
     <link rel="stylesheet" type="text/css" href="{{url('public/website/css/sq-payment-form.css')}}">

<style type="text/css">
    .header-mo ul.nav.navbar-nav li a i {
    font-size: 19px;
    background-image: linear-gradient(#ffdc73, #e1b73a);
    padding: 8px;
    color: #111;
    border-radius: 90px;
    width: 40px;
    height: 40px;
    text-align: center;
    padding-top: 9px;
    position: relative;
    top: -7px;
    left: -10px;
}

.new-news-letter label#email-error {
    float: left;
    color: red;
    text-transform: unset;
    font-size: 18px;
}

.text-danger {
    color: #a94442;
    float: left;
    font-size: 16px;
}

.loader {

  border: 5px solid #f3f3f3;
  border-radius: 50%;
  border-top: 5px solid #3498db;
  width:33px;
  height: 33px;
  -webkit-animation: spin 2s linear infinite; /* Safari */
  animation: spin 2s linear infinite;
}
.loader {
    position: absolute;
    top: 12px;
    right: -43px;
}

/* Safari */
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

</style>

</head>

<body>
    <div class="new-parrellex new">
        <div class="header-mo">
        <div class="container">
      <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{{url('website/home')}}"><img src="{{url('public/website/images/logo-top.png')}}"></a>
            </div>
                <nav class="navbar">
                    <div class="container">
                        @if($user = auth()->guard('web')->user())
                         <div class="collapse navbar-collapse" id="myNavbar">
                             <ul class="nav navbar-nav navbar-right">
                                <li class="active"><a href="{{url('website/current-events')}}">Find a Motiv </a></li>
                                @if($user->role == 3)
                                <li><a href="{{url('website/check-in-event')}}">Check In </a></li>
                                @else 
                                @endif
                                <li><a href="{{url('website/support')}}"> Support</a></li>
                                <li><a href="{{url('website/my-profile')}}">My Profile </a></li>
                                <li><a href="{{url('website/settings')}}"> Settings</a></li>
                            </ul>
                        </div>
                        @else 
                        <div class="collapse navbar-collapse" id="myNavbar">
                             <ul class="nav navbar-nav navbar-right">
                                <li class="active"><a href="{{url('website/current-events')}}">Find a Motiv </a></li>
                                <!-- <li><a href="#">Organiser Portal </a></li> -->
                                <li><a href="{{url('website/support')}}"> Support</a></li>
                                <li><a href="{{url('website/login')}}">Login</a></li>
                                <li><a href="{{url('website/register')}}"><span data-hover="My Events">Sign Up</span></a></li>
                            </ul>
                        </div>
                        @endif
                    </div>
                </nav>
            
        </div>
    </div>

    <!-- Content -->
    @yield('content')

    </div>
    </div>
    </div>
    </div>
    <div class="images-last-banner hide">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <div class="new-info">
                        <h2>Download Our App</h2>
                        <figure><img src="{{url('public/website/images/google-play.png')}}"></figure>
                        <figure><img src="{{url('public/website/images/apple-logo.png')}}"></figure>

                    </div>
                </div>
            </div>

        </div>
    </div>

     
    <div class="new-news-letter">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3 text-center">
                    <div class="letter-info">
                        <div class="new-line">
                            <h3>keep updated on latest events</h3>
                              <div style="padding:11px;font-weight: 400;" class="alert alert-success alert-dismissible text-center alertzzz test1"></div>
                            <form method="post" id="form_validate">
                                <div class="form-group">
                                    <input type="text" id="email_subscribe" name="email" class="form-control" placeholder="Email Address">
                                  
                                      <div class="loader"></div>
                                    <span class="text-danger email_error">{{$errors->first('email')}}</span>
                                    <button type="button" onclick="return submit_form2();" class="new-btn loderclose">SUBSCRIBE</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         <div class="new-light">
        <div class="container">
            <div class="col-md-12">
                <div class="new-light">
                    <ul>
                        <li><img src="{{url('public/website/images/footer-one.png')}}"></li>
                        <li><img src="{{url('public/website/images/footer-two.png')}}"></li>
                        <li><img src="{{url('public/website/images/footer-three.png')}}"></li>
                        <li><img src="{{url('public/website/images/footer-four.png')}}"></li>
                        <li><img src="{{url('public/website/images/footer-five.png')}}"></li>
                        <li><img src="{{url('public/website/images/footer-six.png')}}"></li>
                        <li><img src="{{url('public/website/images/footer-seven.png')}}"></li>
                        <li><img src="{{url('public/website/images/footer-eight.png')}}"></li>
                        <li><img src="{{url('public/website/images/footer-nine.png')}}"></li>

                    </ul>
                </div>
            </div>
        </div>
    </div>

        <footer class="new-footer">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="new-footer-contains text-center">
                            <ul>
                              <li><a href="javascript:void(0);">Download The App</a></li>
                                <li><a href="{{url('website/get-in-touch')}}"> Get in touch </a></li>
                                <li><a href="{{url('website/faq')}}"> Faqs</a></li>
                                <li><a href="{{url('website/terms')}}"> terms & conditions</a></li>
                                <li><a href="{{url('website/privacy')}}"> privacy policy</a></li>
                                <li><a href="{{url('website/support')}}"> support</a></li>
                            </ul>
                        </div>
                        <div class="new-connect">
                            <p>CONNECT WITH US</p>
                            <div class="new-home">
                                <a href="javascript:void(0);"><i class="fa fa-facebook-square" aria-hidden="true"></i></a>
                                <a href="javascript:void(0);"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                                <a href="javascript:void(0);"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                            </div>
                            <div class="new-formula">
                                <p>&copy; Copyright <?php echo date("Y");?> Motiv. All Rights Reserved.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </footer>
    </div>




<!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

 -->
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.2/jquery.min.js"> </script>

<script src="{{url('public/website/js/bootstrap.min.js')}}"></script>
<script src="{{url('public/website/js/owl.carousel.js')}}"></script>
<script type="text/javascript" src="{{url('public/website/js/bootstrap-tabcollapse.js')}}"></script>
<script type="{{url('public/website/js/wow.js')}}"></script>
<script src="{{url('public/website/js/custom.js')}}"></script>
<script src="{{url('public/website/js/modernizr.custom.js')}}"></script>
 <script src=" https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
<script src=" https://cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>

@yield('js')
<script type="text/javascript">
    $(function() {
      setTimeout(function(){
          $(".alertz").hide();
      }, 3000);
  });
</script>

<script type="text/javascript">
  $('.test1').hide();
  $('.loader').hide();

  $(".loderclose").click(function(){
      $('.loader').show();
    });
  function submit_form2(){
    // $('.loader').sho();
    var email = $('#email_subscribe').val();
    // alert(email); return false;
    var data = {
      '_token': "{{csrf_token()}}",
      'email': email,

  };

  $.ajax({
      url:"{{url('website/email-subscribe')}}",
      type:'POST',
      data:data,

      success: function(res){
        if(res.message) {
            $(".alertzzz").text(res.message).show();
            $('.email_error').hide();
        }
        setTimeout(function(){
            $(".alertzzz").hide();
            $('.loader').hide();
            $('.email_error').hide();
        }, 3000);
        // console.log(res.message)
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



</body>

</html>
