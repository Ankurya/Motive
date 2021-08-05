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
  <link rel="icon" href="{{url('public/website/images/logo-top.png')}}" type="image/x-icon">
  <link href="{{url('public/website/css/font-awesome.min.css')}}" rel="stylesheet">
  <link href="{{url('public/website/css/owl.carousel.css')}}" rel="stylesheet">
  <link href="{{url('public/website/css/wow.css')}}" rel="stylesheet">
  <link href="{{url('public/website/css/bootstrap.min.css')}}" rel="stylesheet">
  <link href="{{url('public/website/css/style.css')}}" rel="stylesheet">
  <link href="{{url('public/website/css/responsive.css')}}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
</head>
<style type="text/css">
.form-control {
  text-transform: unset;
}
.dropdown-menu {
 min-width: unset; 
}
.form-group.calender img {
  right: 5px;
}
.modal-body {
  height: 581px;
  overflow-y: scroll;

}
  .profile {
    text-align: center;
    margin: 0 auto 0px;
    width: 148px;
    position: relative;
        display: inline-block;

}
.profile span.pro-img {
    border-radius: 50%;
    display: inline-block;
    overflow-x: hidden;
        vertical-align: middle;

}

.profile span.pro-img img {
      top: 0;
    left: 0;
    right: 0;
    margin: auto;
    height:80px;
    width: 80px;
}

.profile span.camera {
       display: block;
    height: 65px;
    width: 65px;
    border-radius: 50%;
    position: absolute;
    bottom: -21px;
    right: 27px;
}
.profile span.camera a {
    position: relative;
    top: 15px;
    left: 0;
    right: 0;
    border: 0px solid;
    margin: auto;
    display: block;
}
.profile span.camera a input {
    opacity: 0;
    position: absolute;
    height: 100%;
   width:100%;
    cursor: pointer;
}
span.text-danger.error {
    display: inline-block;
    text-align: center;
}
.top-build-info .profile-pic h3{
      margin-right: 57px;

}
.home-error {
    text-align: center;
    margin-top: 18px;
}
.new-select-info p:hover{
  color: #ffdc73;
  
}
input#date {
    cursor: pointer;
        background: transparent;

}
#interest .user-wrap,
#interest1 .user-wrap{
      margin: 17px 48.4% 25px;

}
#interest .user-wrap label.check:before{
      top: -4px;

}
 .user-wrap input[type='checkbox'] {
    position: absolute;
    /* visibility: hidden; */
    cursor: pointer;
    height: 32px;
    width: 32px;
    right: initial;
    top: 0px;
    opacity: 0;
    z-index: 9;
    margin: auto;
    left: 0;
}
label.container_check.user-wrap
{
  position: relative;
  padding-left: 45px;
}
 .user-wrap label.check:before {
    background-color: transparent;
    left: 0px;
    background-image: none;
    top: 0px;
    height: 32px;
    width: 32px;
    border: 1px solid #f5cf5f;
    content: "";
    display: block;
    border-radius: 0;
    position: absolute;
}


  label.container_check.user-wrap a {
      color: #e9c048;
  }
p.new-sign-up {
    text-align: center;
    margin-top: 56px;
        color: #fff;

    font-size: 20px;
}
.new-sign-up a.news-hover {
    color: #fedb71!important;
}
.top-build-info .profile-pic h3{
      margin-top: 12px;

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
      <div class="form-content register">
        <div class="form-long_content">
          <form method="post" class="login_form" enctype="multipart/form-data">
            {{csrf_field()}} 
            <div class="form-info-settings lvert">
              <div class="top-build-info">
                <h2>REGISTER</h2>
                <div class="new-small-logo">
                  <img src="{{url('public/website/images/logo-inner.png')}}">
                </div>
                <div class="profile-pic">
                  <h3>profile picture</h3>
                
                  <div class="profile">
                    <span class="pro-img">
                       <img onclick="$('#imgInp').click()" id="oldImg" src="{{url('public/website/images/user-news.png')}}" accept="image/*">
                        </span>
                      <span class="camera">
                        <a href="javascript:;">
                          <input type="file" id="imgInp" name="image">
                            <img src="{{url('public/website/images/new.png')}}">
                         
                        </a>
                      </span>
                    </div>      
                  <div class="home-error">           
                    <span class="text-danger error msz">{{$errors->first('image')}}</span>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>FIRST NAME</label>
                    <input type="text" value = "{{old('firstname')}}" autocomplete="off"  placeholder="FIRST NAME" name="firstname" class="form-control">
                    <span class="text-danger error">{{$errors->first('firstname')}}</span>

                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>LAST NAME</label>
                    <input type="text" value = "{{old('lastname')}}" placeholder="LAST NAME" autocomplete="off" name="lastname" class="form-control">
                    <span class="text-danger error">{{$errors->first('lastname')}}</span>


                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label>username</label>
                    <input type="text" value = "{{old('user_name')}}" placeholder="USERNAME" autocomplete="off" name="user_name" class="form-control">
                    <span class="text-danger error">{{$errors->first('user_name')}}</span>


                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Email</label>
                    <input type="text" name="email" placeholder="EMAIL ADDRESS" autocomplete="off" value = "{{old('email')}}" class="form-control">
                    <span class="text-danger error">{{$errors->first('email')}}</span>


                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label>phone number (optional)</label>
                    <input type="text" name="phone_number" placeholder="PHONE NUMBER" autocomplete="off" value = "{{old('phone_number')}}" class="form-control">
                    <span class="text-danger error">{{$errors->first('phone_number')}}</span>

                  </div>
                </div>
                
                <div class="col-md-12">
                  <div class="form-group">
                    <label>password</label>
                    <input type="password" placeholder="PASSWORD" name="password" autocomplete="off" class="form-control">
                    <span class="text-danger error">{{$errors->first('password')}}</span>


                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label>confirm password</label>
                    <input type="password"  name="confirm_password" placeholder="CONFIRM PASSWORD" autocomplete="off" class="form-control">
                    <span class="text-danger error">{{$errors->first('confirm_password')}}</span>


                  </div>
                </div>

                
                <div class="col-md-12">
                  <div class="form-group  calender">
                    <label>dob</label>
                    <input class="form-control" id="date" name="dob" autocomplete="off" value = "{{old('dob')}}" placeholder="DATE OF BIRTH" readonly="true"/><img src="{{url('public/website/images/calender.png')}}">
                    <span class="text-danger error dateerror">{{$errors->first('dob')}}</span>

                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Refferal Code (optional)</label>
                    <input type="text"  name="user_refferal_code" placeholder="REFFERAL CODE" autocomplete="off" class="form-control">
                    <span class="text-danger error">{{$errors->first('user_refferal_code')}}</span>
                  </div>
                </div>
               
                <div class="col-md-12">
                  <div class="form-group">
                    <label>ABOUT YOU</label>
                    <textarea name="about" placeholder="ABOUT YOU" value = "{{old('about')}}"  class="form-control"></textarea>
                    <span class="text-danger error">{{$errors->first('about')}}</span>


                  </div>
                </div>
                <div class="col-sm-12">
                <div class="terms">
                <label class="container_check user-wrap user-wrap2" style="margin-bottom: 0px;">
                 <input class="checkbox" type="checkbox" name="privacy" value="1" for="check1">
                 <label id="check1`" class="check">I agree <a href="{{url('website/terms')}}">Terms of Service</a> and <a href="{{url('website/privacy')}}">Privacy Policy</a></label>
                </label>
                  <span class="text-danger error">{{$errors->first('privacy')}}</span>
                </div>
              </div>
            </div>
                <div class="col-md-12">
                  <div class="new-select-info"
                  data-toggle="modal" data-target="#interest"><p>SELECT YOUR INTERESTS</p>
                  <img src="{{url('public/website/images/blacknew.png')}}">  
                </div>
              </div>
               <div class="row">
              
            </div>
            <button type="submit" class="btn btn-default register" data-dismiss="modal">SIGN UP</button>
            <P class="new-sign-up"><a class="news-hover" href="{{url('website/organiser-register')}}" style="color:#fff;">SIGNUP HERE </a>IF YOU ARE AN EVENT ORGANISER</P>
          </div>
          </div>
          <div id="interest" class="modal fade" role="dialog">
            <div class="modal-dialog">

              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal"><img src="{{url('public/website/images/popup-cross.png')}}"> </button>
                  <h4 class="modal-title">Select your interests</h4>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="user-wrap">
                        <input type="checkbox" id="CheckAll" name="">
                        <label class="check">All</label>
                      </div>
                    </div>
                  </div>
                  <div class="first-content">
                    <div class="row">
                      <div class="col-sm-4">
                        <div class="form-group">
                         <a href="javascript:void(0);" data-toggle="modal" data-target="#interest1"><input type="checkbox" name="" class="checkBoxClass music_int1"><label class="check animal new">
                          <img src="{{url('public/website/images/jazz.png')}}">music</label></a>
                        </div>
                      </div>
                      @foreach($public_interest as $public)
                      @php 
                      $image = $public->image ? url('/public') . DIRECTORY_SEPARATOR . $public->image : url('/') . DIRECTORY_SEPARATOR . env('ADMIN_IMAGES_PATH') . 'user.png';
                      @endphp
                      <div class="col-sm-4">
                        <div class="form-group">
                         <input type="checkbox" name="public[]" value="{{$public->id}}" class="checkBoxClass public_int"><label class="check Community new"><img src="{{$image}}">{{$public->name}}</label>
                       </div>
                     </div>
                     @endforeach()

                   </div>

                 </div>

                 <div class="new-button_right">
                  <div class="col-md-12">
                    <button type="button" class="btn btn-default register yes skip" data-dismiss="modal">Skip</button>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
        <div id="interest1" class="modal fade" role="dialog">
          <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><img src="{{url('public/website/images/popup-cross.png')}}"> </button>
                <h4 class="modal-title">Select  music interests</h4>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-sm-12">
                    <div class="user-wrap">
                      <input type="checkbox" id="CheckAlls" name="">
                      <label class="check">All</label>
                    </div>
                  </div>
                </div>
                <div class="first-content">

                  <div class="row">
                    @foreach($music_interest as $music)
                    @php 
                    $image = $music->image ? url('/public') . DIRECTORY_SEPARATOR . $music->image : url('/') . DIRECTORY_SEPARATOR . env('ADMIN_IMAGES_PATH') . 'user.png';
                    @endphp
                    <div class="col-sm-4">
                      <div class="form-group">
                        <input type="checkbox"  name="music[]" value="{{$music->id}}" class="checkBoxClass music_int" ><label class="check animal new"><img src="{{$image}}">{{$music->name}}</label></label>
                      </div>
                    </div>  
                    @endforeach
                  </div>



                  <div class="new-button_right">

                    <div class="col-md-12">
                    }
                    <button type="button" class="btn btn-default register yes skip1" data-dismiss="modal">Skip</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>

<script src="{{url('public/website/js/bootstrap.min.js')}}"></script>
<script src="{{url('public/website/js/owl.carousel.js')}}"></script>
<script type="text/javascript" src="{{url('public/website/js/bootstrap-tabcollapse.js')}}"></script>
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


<script type="text/javascript">
  function readURL(input) {
    if (input.files && input.files[0]) {

      var type = input.files[0].type;

      if(type == 'image/jpeg' || type == 'image/png'){

        var reader = new FileReader();

        reader.onload = function (e) {
          $('#oldImg').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);

      } else {
        $('.msz').text('Only Jpeg and Png format is allowed.');
      }
    }
  }

  $("#imgInp").change(function(){
    readURL(this);
  });
</script>

<script type="text/javascript">
  $(document).ready(function() {
    $("ul.nav.navbar-nav li").click(function () {
      $("ul.nav.navbar-nav li").removeClass("active");
      $(this).addClass("active");   
      
    });
    $("#CheckAll").click(function () {

      $(".public_int").prop('checked', $(this).prop('checked'));
      $(".music_int").prop('checked', $(this).prop('checked'));
      $(".music_int1").prop('checked', $(this).prop('checked'));
      $("#CheckAlls").prop('checked', $(this).prop('checked'));

    });

  });
</script>
<script type="text/javascript">
  $(document).ready(function() {
    $("ul.nav.navbar-nav li").click(function () {
      $("ul.nav.navbar-nav li").removeClass("active");
      $(this).addClass("active");   
      
    });
    $("#CheckAlls").click(function () {
      $(".music_int").prop('checked', $(this).prop('checked'));
      $(".music_int1").prop('checked', $(this).prop('checked'));

    });

  });

</script>

<script>
  $(document).ready(function(){
        var date_input=$('input[name="dob"]'); //our date input has the name "date"
        var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
        var end = new Date();
        end.setFullYear(end.getFullYear() - 16);
        // alert(end)
        date_input.datepicker({
          format: 'dd/mm/yyyy',
          container: container,
          endDate: "today",
          maxDate: "today",
          todayHighlight: true,
          autoclose: true,
        })
      })

  $('#date').on('change', function() {
    var dob = this.value;
    dob = new Date(dob);
    var today = new Date();
    var age = Math.floor((today-dob) / (365.25 * 24 * 60 * 60 * 1000));
    if(age <= 16) {
      $('.dateerror').text('You are not eligible for this.');
    } else {
      $('.dateerror').hide();
    }
  });

$(".public_int").click(function(){
  if(!$(this).prop("checked")){
    $("#CheckAll").prop("checked",false);
  }
});

$(".music_int").click(function(){
  if(!$(this).prop("checked")){
    $("#CheckAlls").prop("checked",false);
  }
});

$(".music_int").click(function(){

let status = false;
$(".music_int").each(function(){
  if($(this).prop("checked")){
    status = true;
  }
  
});

if(status) {
    $(".music_int1").prop("checked",true);

  }else {
    $(".music_int1").prop("checked",false);
  }

});




</script>
<script type="text/javascript">
  $(document).ready(function(){
    $(".skip").click(function(){
      // alert('skip');
          $("#CheckAll").prop("checked",false);
          $(".public_int").prop("checked",false);
          $(".music_int").prop("checked",false);
          $(".music_int1").prop("checked",false);
          $("#CheckAlls").prop("checked",false);
          
      });

  $(".skip1").click(function(){
      // alert('mus');
      $("#CheckAll").prop("checked",false);
      // $(".public_int").prop("checked",false);
      $(".music_int").prop("checked",false);
      $(".music_int1").prop("checked",false);
      $("#CheckAlls").prop("checked",false);
      
  });
});
</script>

  </body>

  </html>
