<!-- header start here -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,  initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <title>MOTIV</title>
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    <link rel="icon" href="{{url('public/website/images/fav.png')}}" type="image/x-icon">
    <link href="{{url('public/website/css/font-awesome.min.css')}}" rel="stylesheet">
    <link href="{{url('public/website/css/owl.carousel.css')}}" rel="stylesheet">
    <link href="{{url('public/website/css/wow.css')}}" rel="stylesheet">
    <link href="{{url('public/website/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{url('public/website/css/style.css')}}" rel="stylesheet">
    <link href="{{url('public/website/css/responsive.css')}}" rel="stylesheet">
</head>
<style type="text/css">
    .form-control {
    text-transform: unset;
}
   .form-control::placeholder {
    text-transform:capitalize;
}
.home-alert i {
   background: #2a2a2a;
    display: inline-block;
    width: 35px;
    height: 35px;
    text-align: center;
    line-height: 33px;
    box-shadow: 0 0 12px #000;
    color: #fff;
}
.home-alert {
    margin-left: 2px;
}
</style>
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
                        <div class="collapse navbar-collapse" id="myNavbar">
                             <ul class="nav navbar-nav navbar-right">
                                <li class="active"><a href="{{url('website/current-events')}}">Find a Motiv </a></li>
                                <!-- <li><a href="#">Organiser Portal </a></li> -->
                                <li><a href="#"> Support</a></li>
                                <li><a href="{{url('website/login')}}">Login</a></li>
                                <li><a href="{{url('website/register')}}"><span data-hover="My Events">Sign Up</span></a></li>
                            </ul>
                        </div>
                    </div>
                </nav>

            </div>
        </div>
        <div class="row new-blue">
            <div class="col-md-6 col-md-offset-3">
                <div class="form-content register less">
                    <div class="form-long_content">
                        <form method="post">
                            {{csrf_field()}}
                            <div class="form-info-settings lvert">
                                <div class="top-build-info">
                                    <div class="home-alert">
                                        <a href="{{url('website/login')}}">
                                       <i class="fa fa-arrow-left" aria-hidden="true"></i>
                                       </a>

                                    </div>
                                   
                                    <div class="new-small-logo">
                                <img src="{{url('public/website/images/logo-inner.png')}}">
                                 <h2>Forgot Password</h2>
                                 @include('website.notifications_message')
                            </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>EMAIL ADDRESS</label>
                                            <input type="text" autocomplete="off" class="form-control" name="email" placeholder="EMAIL ADDRESS">
                                            <span class="text-danger">{{$errors->first('email')}}</span>
                                          
                                        </div>
                                    </div>
                                   
                                    </div>
                                  
                                    <div class="new-formula-home-sd">
                                        <button type="submit" class="new-homes">SUBMIT</a>
                                    </div>
                                </div>
                            </form>
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
    <div class="new-news-letter">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3 text-center">
                    <!-- <div class="letter-info">
                        <div class="new-line">
                            <h3>keep updated on latest events</h3>
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Email Address">
                                <button type="submit" class="new-btn">SUBSCRIBE</button>
                            </div>
                        </div>
                    </div> -->
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
                                <li><a href="javascript:void(0);"> Get in touch </a></li>
                                <li><a href="javascript:void(0);"> Faqs</a></li>
                                <li><a href="javascript:void(0);"> terms & conditions</a></li>
                                <li><a href="javascript:void(0);"> privacy policy</a></li>
                                <li><a href="javascript:void(0);"> support</a></li>
                            </ul>
                        </div>
                        <div class="new-connect">
                            <p>CONNECT WITH US</p>
                            <div class="new-home">
                                <a href="javascript:void(0);"><img src="{{url('public/website/images/instragram.png')}}"></a>
                                <a href="javascript:void(0);"><img src="{{url('public/website/images/facebook-new.png')}}"></a>
                                <a href="javascript:void(0);"><img src="{{url('public/website/images/twitter-new.png')}}"></a>
                            </div>
                            <div class="new-formula">
                                        <p>&copy; Copyright 2019 Motiv. All Rights Reserved.</p>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </footer>
    </div>

    <script type="text/javascript">
   
    </script> 



        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script src="{{url('public/website/js/bootstrap.min.js')}}"></script>
        <script type="{{url('public/website/js/wow.js')}}"></script>
        <script src="{{url('public/website/js/custom.js')}}"></script>
        <script type="text/javascript">
            $(function() {
              setTimeout(function(){
                  $(".alertz").hide();
              }, 3000);
          });
        </script> 
        <script type="text/javascript">
            jQuery(document).ready(function() {
                jQuery('.tabs .tab-links a').on('click', function(e) {
                    var currentAttrValue = jQuery(this).attr('href');

                    // Show/Hide Tabs
                    jQuery('.tabs ' + currentAttrValue).show().siblings().hide();

                    // Change/remove current tab to active
                    jQuery(this).parent('li').addClass('active').siblings().removeClass('active');

                    e.preventDefault();
                });
            });

        </script>
        <script>
    $(document).ready(function(){
        var date_input=$('input[name="date"]'); //our date input has the name "date"
        var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
        date_input.datepicker({
            format: 'mm/dd/yyyy',
            container: container,
            todayHighlight: true,
            autoclose: true,
        })
    })
</script>
    <script type="text/javascript">
     $('form').submit(function() {
        $(this).find("button[type='submit']").prop('disabled',true);
      });

    </script>

    <!-- <script type="text/javascript">
    $(function() {
      setTimeout(function(){
          $(".alertz").hide();
      }, 3000);
    });
    </script> -->



</body>

</html>
