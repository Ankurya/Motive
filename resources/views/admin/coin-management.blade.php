@extends('layouts.admin_hf')   
@section('title', '')  
@section('pageContent')
		<div class="custom-breadcrumb">
		<div class="container-fluid">
		<p><a href="{{url('dashboard')}}"> Home</a> <i class="ti-angle-right"></i>  <span>Coin Management </span> </p>
		</div>
		</div>


        <div class="content">
            <div class="container-fluid">
			<div class="row main-right-content card">
			<form class="main-content-form">
			
						<div class="col-sm-6 col-xs-12 form-group">
			<label>Gold Coin Worth($)</label>
			<input type="text" class="form-control border-input" value="John">
			</div>
			
			<div class="form-group col-sm-12 col-xs-12 main-form-update">
			<input type="button" value="Update" class="btn btn-info btn-fill btn-wd btn-clr">
			</div>
			
			
			
			</form>
			
			
			</div>
              
			  </div>
        </div>

 @endsection('pageContent')
