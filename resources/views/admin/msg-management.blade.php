@extends('layouts.admin_hf')   
@section('title', '')  
@section('pageContent')
		<div class="custom-breadcrumb">
		<div class="container-fluid">
		<p><a href="{{url('dashboard')}}"> Home</a> <i class="ti-angle-right"></i>  <span>Send Message Management </span> </p>
		</div>
		</div>


        <div class="content">
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
            <div class="container-fluid">
			<div class="row main-right-content card">
			<form class="main-content-form" method="post" id="frm" action="{{url('admin/send-push-message')}}">
					<div class="col-sm-10 col-xs-12 form-group">
						<label>Enter Message</label>
						<!-- <input type="text" class="form-control border-input" value="John"> -->
						{{ csrf_field() }}
						<textarea style="margin-top: 8px;" class="form-control border-input" placeholder ="Enter your message" rows="4" name="message">{{ old('message') }}</textarea>
					</div>
					
					<div class="form-group col-sm-12 col-xs-12 main-form-update">
						<input type="submit" id="send" value="Send Message" class="btn btn-info btn-fill btn-wd btn-clr">
					</div>
			
			</form>
			
			
			</div>
              
			  </div>
        </div>

 @endsection('pageContent')
 
 @section('jqueryPageContent')
 <script>
	$('#frm').bind('submit', function (e) {
		var button = $('#send');
		console.log('click done');
		// Disable the submit button while evaluating if the form should be submitted
		button.prop('disabled', true);
	});

 </script>
@endsection('jqueryPageContent')