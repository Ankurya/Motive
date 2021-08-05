<!DOCTYPE html>
<html>
<link rel="icon" type="image/png" sizes="96x96" href="{{asset('public/admin/img/favicon.png')}}">
<head>
  <title>MOTIV</title>
</head>
<body>

</body>
</html>
<div style="margin-bottom: 20px;text-align: center;">
  <body class="login">
    <div>
     
      <div class="login_wrapper">
        <!-- Login Form -->
        <div class="animate form login_form">
          <section class="login_content">
          <div class="text-center logo-wrapper">
          	<img src="{{url('public/website/logo.png')}}" alt="Mo-Tiv" width="190"/>
          </div>
  					<p class="alert alert-success">{{$message}}</p>
          <!-- @if(Session::has('success')) -->
  				<!-- @endif -->
          </section>
        </div>
        </div>
       
      </div>
    </div>
  </body>
</html>
