<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<link rel="apple-touch-icon" sizes="76x76" href="{{url('public/admin/img/apple-icon.png')}}">
	<link rel="icon" type="image/png" sizes="96x96" href="{{url('public/admin/img/favicon.png')}}">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>MOTIV</title>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />


    <!-- Bootstrap core CSS     -->
    <link href="{{url('public/admin/css/bootstrap.min.css')}}" rel="stylesheet" />

    <!-- Animation library for notifications   -->
    <link href="{{url('public/admin/css/animate.min.css')}}" rel="stylesheet"/>

    <!--  Paper Dashboard core CSS    -->
    <link href="{{url('public/admin/css/paper-dashboard.css')}}" rel="stylesheet"/>


    <!--  CSS for Demo Purpose, don't include it in your project     -->
    <link href="{{url('public/admin/css/demo.css')}}" rel="stylesheet" />


    <!--  Fonts and icons     -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Muli:400,300' rel='stylesheet' type='text/css'>
    <link href="{{url('admin/css/themify-icons.css')}}" rel="stylesheet">
	<!-- custom style -->
    <link href="{{url('public/admin/css/custom-style.css')}}" rel="stylesheet">
	<link href="{{url('public/admin/css/responsive.css')}}" rel="stylesheet">

</head>
<body>

<div class="wrapper login-page full-screen">
<div class="login-content">
<div class="logo text-center">
<img src="{{url('public/admin/img/logo-inner.png')}}">
</div>
   <div class="login-box">
   <h2 class="login-head">Admin Login </h2>
   
@if ($errors->any())
				<div class="alert alert-danger">
					<ul>
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
						</ul>
					</div>
				@endif
				@if(Session::has('message'))
					<p class="alert alert-success">{{ Session::get('message') }}</p>
				@endif
   <form method="post" action={{url('admin/login')}}>
   <div class="form-group">
   <input class="form-control border-input" placeholder="Email" name="email" type="email">
   </div>	{{csrf_field() }} 
   <div class="form-group pos-rel password-form">
   <input class="form-control border-input" placeholder="Password" name="password" type="password">
   <span><a href="{{url('forgot-password')}}">Forgot? </a><span>
   </div>
   <div class="form-group submit text-center">
   
   
     <input class="btn btn-info btn-fill btn-wd btn-clr"  type="submit" value="login">
   </div>
  
   
   </form> 
   </div>
   
</div>


</body>
</div>
    <!--   Core JS Files   -->
    <script src="{{url('public/admin/js/jquery-1.10.2.js')}}" type="text/javascript"></script>
	<script src="{{url('public/admin/js/bootstrap.min.js')}}" type="text/javascript"></script>

	<!--  Checkbox, Radio & Switch Plugins -->
	<script src="{{url('public/admin/js/bootstrap-checkbox-radio.js')}}"></script>

	<!--  Charts Plugin -->
	<script src="{{url('public/admin/js/chartist.min.js')}}"></script>

    <!--  Notifications Plugin    -->
    <script src="{{url('public/admin/js/bootstrap-notify.js')}}"></script>

    <!--  Google Maps Plugin    -->
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script>

    <!-- Paper Dashboard Core javascript and methods for Demo purpose -->
	<script src="{{url('public/admin/js/paper-dashboard.js')}}"></script>

	<!-- Paper Dashboard DEMO methods, don't include it in your project! -->
	<script src="{{url('public/admin/js/demo.js')}}"></script>

	

</html>
