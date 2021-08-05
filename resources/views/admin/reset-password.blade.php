<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<link rel="apple-touch-icon" sizes="76x76" href="{{asset('public/admin/img/apple-icon.png')}}">
	<link rel="icon" type="image/png" sizes="96x96" href="{{asset('public/admin/img/favicon.png')}}">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>Reset Password</title>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />


    <!-- Bootstrap core CSS     -->
    <link href="{{asset('public/admin/css/bootstrap.min.css')}}" rel="stylesheet" />

    <!-- Animation library for notifications   -->
    <link href="{{asset('public/admin/css/animate.min.css')}}" rel="stylesheet"/>

    <!--  Paper Dashboard core CSS    -->
    <link href="{{asset('public/admin/css/paper-dashboard.css')}}" rel="stylesheet"/>


    <!--  CSS for Demo Purpose, don't include it in your project     -->
    <link href="{{asset('public/admin/css/demo.css')}}" rel="stylesheet" />


    <!--  Fonts and icons     -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Muli:400,300' rel='stylesheet' type='text/css'>
    <link href="{{asset('admin/css/themify-icons.css')}}" rel="stylesheet">
	<!-- custom style -->
    <link href="{{asset('public/admin/css/custom-style.css')}}" rel="stylesheet">
	<link href="{{asset('public/admin/css/responsive.css')}}" rel="stylesheet">

</head>
<body>

<div class="wrapper login-page full-screen">
<div class="login-content">
@if($errors->any())
					<div class="alert alert-danger" style="padding-right: 55px;">
						<ul>
							@foreach ($errors->all() as $error)
							<li >{{ $error }}</li>
							@endforeach
						</ul>
					</div>
					@endif
					@if(Session::has('status'))
				<p class="alert alert-success" style="color:black;">{{ Session::get('status') }}</p>
					@endif
<div class="logo text-center">
<img src="{{asset('public/admin/img/MO_COIN.png')}}">
</div>
   <div class="login-box">
   <h2 class="login-head">Reset Password </h2>
  <form  method="post" class="main-content-form">
	{{csrf_field()}}
   <div class="form-group">
  
    <input type="password" class="form-control border-input" placeholder="New Password"  name = "password"  />
   </div>
   <div class="form-group">
  
   <input type="password" class="form-control border-input" placeholder="Confirm Password" name = "confirm_password"  />
   </div>
   
   <div class="form-group submit text-center">
  
    <input type="submit" name="submit" value="Update Password" class="btn btn-clr">
   </div>
  
   
   </form>
   </div>
   
</div>


</body>
</div>
    <!--   Core JS Files   -->
    <script src="{{asset('public/admin/js/jquery-1.10.2.js')}}" type="text/javascript"></script>
	<script src="{{asset('public/admin/js/bootstrap.min.js')}}" type="text/javascript"></script>

	<!--  Checkbox, Radio & Switch Plugins -->
	<script src="{{asset('public/admin/js/bootstrap-checkbox-radio.js')}}"></script>

	<!--  Charts Plugin -->
	<script src="{{asset('public/admin/js/chartist.min.js')}}"></script>

    <!--  Notifications Plugin    -->
    <script src="{{asset('public/admin/js/bootstrap-notify.js')}}"></script>

    <!--  Google Maps Plugin    -->
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script>

    <!-- Paper Dashboard Core javascript and methods for Demo purpose -->
	<script src="{{asset('public/admin/js/paper-dashboard.js')}}"></script>

	<!-- Paper Dashboard DEMO methods, don't include it in your project! -->
	<script src="{{asset('public/admin/js/demo.js')}}"></script>

	

</html>
