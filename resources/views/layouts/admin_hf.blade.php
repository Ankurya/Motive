<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<link rel="apple-touch-icon" sizes="76x76" href="{{url('public/admin/img/apple-icon.png')}}">
	<link rel="icon" type="image/png" sizes="96x96" href="{{url('public/admin/img/fev-icon-transparent.png')}}">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>MOTIV</title>
   <link href="//netdna.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
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
    <link href="{{url('public/admin/css/themify-icons.css')}}" rel="stylesheet">
	
	<!-- custom style -->
    <link href="{{url('public/admin/css/custom-style.css')}}" rel="stylesheet">
	<link href="{{url('public/admin/css/responsive.css')}}" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
</head>
<body>

<div class="wrapper dashboard-page">
			<!-- sidebar menu -->
			@include('admin/sidebar')
			<!-- /sidebar menu --> 
    <div class="main-panel">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar bar1"></span>
                        <span class="icon-bar bar2"></span>
                        <span class="icon-bar bar3"></span>
                    </button>
                    <a class="navbar-brand" href="#">Dashboard</a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
      					<li>
                            <a href="{{url('logout')}}">
								<i class="fa fa-sign-out"></i>
								<p>Logout</p>
                            </a>
                        </li>
                    </ul>

                </div>
            </div>
        </nav>
<!-- end header-->
	
<!-- Call pageContent start-->
    @yield('pageContent') 
<!-- Call pageContent end-->


	<!-- start footer-->
	
	
    </div>
</div>

</body>
     
    <!--   Core JS Files   -->
    <script src="{{url('public/admin/js/jquery-1.10.2.js')}}" type="text/javascript"></script>
	<script src="{{url('public/admin/js/bootstrap.min.js')}}" type="text/javascript"></script>

	<!--  Checkbox, Radio & Switch Plugins -->
	<script src="{{url('public/admin/js/bootstrap-checkbox-radio.')}}"></script>

	<!--  Charts Plugin -->
	<script src="{{url('public/admin/js/chartist.min.js')}}"></script>

    <!--  Notifications Plugin    -->
    <script src="{{url('public/admin/js/bootstrap-notify.js')}}"></script>

    <!--  Google Maps Plugin    -->
    <!--<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script>

    <!-- Paper Dashboard Core javascript and methods for Demo purpose -->
	<script src="{{url('public/admin/js/paper-dashboard.js')}}"></script>

	<!-- Paper Dashboard DEMO methods, don't include it in your project! -->
	<script src="{{url('public/admin/js/demo.js')}}"></script>
	<script src="{{url('public/admin/js/custom.js')}}"></script>
       <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"> </script>
	   
	   <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>

<!-- (Optional) Latest compiled and minified JavaScript translation files -->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/i18n/defaults-*.min.js"></script>-->

	   
<script>
      $(document).ready(function(){
         $('#userlist-table').DataTable({
			"columnDefs": [ {
				"targets":[7],
				"bSortable": false,
				"orderable": false
				} ] 
		});
      responsive: true
      });
   </script>			
   <script>
		 $(".alert-success").fadeTo(2000, 5000).slideUp(500, function(){
			$(".alert-success").slideUp(500);
		});
	 </script>
</html>

@yield('jqueryPageContent') 	
		
		
		
		
		