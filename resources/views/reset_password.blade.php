<html>
<link rel="icon" type="image/png" sizes="96x96" href="{{asset('public/admin/img/favicon.png')}}">
<head>
<style>

</style>
</head>
<div style="margin-bottom: 20px;text-align: center; boder">

  <body class="login">
    <div>
      <div class="login_wrapper">
        <!-- Login Form -->
        <div class="animate form login_form">
          <section class="login_content">
          <div class="text-center logo-wrapper">
          	<img src="{{ url('public/img/app-logo-for-website.png' ) }}" alt="Mo-Tiv" width="190"/>
          </div>
		  
		  @if($errors->any())
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
	
            <form method="post">
			{{ csrf_field() }}
            <div class="col-xs-12">
              <h1>Reset Password</h1>
              </div>
              <div class="col-xs-12">
                <input type="text" class="form-control" placeholder="Password"  name="password" value="" required/>
              </div><br>
              <div class="col-xs-12">
                <input type="password" class="form-control" placeholder="Confirm Password" name="confirm_password" required/>
              </div><br>
              <div class="col-xs-12">
               <!-- <a class="btn btn-primary" href="">Login</a> -->
				<input type="submit" name="submit" value="Update Password" class="btn btn-primary">
              </div>

              <div class="clearfix"></div>
            </form>
          </section>
        </div>
        
       </div>
      </div>
    </div>
  </body>
</html>
