@extends('layouts.admin_hf')   
@section('title', '')  
@section('pageContent')
		<div class="custom-breadcrumb">
		<div class="container-fluid">
		<p><a href="{{url('dashboard')}}"> Home</a> <i class="ti-angle-right"></i><a href="{{url('public-interest')}}"> Public Interest Management</a> <i class="ti-angle-right"></i>  <span>Add </span> </p>
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
				<form action="{{url('add-public-interest')}}" class="main-content-form" method="post" >
				{{csrf_field()}}
				<div class="col-sm-6 col-xs-12 form-group">
				<label>Public Interest Name</label>
				<input type="text" name="name" class="form-control border-input">
				</div>
				<div class="form-group col-sm-12 col-xs-12 main-form-update">
				<a href="" ><input type="submit" value="Add"  class="btn btn-info btn-fill btn-wd btn-clr"></a>
				</div>
				</form>
				</div>
        	</div>
        </div>

@endsection('pageContent')