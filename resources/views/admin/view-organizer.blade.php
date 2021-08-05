@extends('layouts.admin_hf')   
@section('title', '')  
@section('pageContent')
		<div class="custom-breadcrumb">
		<div class="container-fluid">
		<p><a href="{{url('dashboard')}}"> Home</a> <i class="ti-angle-right"></i> <a href="javascript:void(0);"> User Management</a> <i class="ti-angle-right"></i> <span>View Organizer </span> </p>
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
			<form class="main-content-form">
			<div class="form-group col-sm-12 col-xs-12" >
			<!-- <div class="left-form-content"><label>Profile Picture</label></div> -->
			
			<div class="img-sec upload-pro-pic">  
                <div class="img-inner-sec"> 
                <div class="file-upload-content">
    <img class="file-upload-image" src="@if(!empty($user_list->image_url)) {{$user_list->image_url}} @else {{asset('public/admin/img/dummy.png')}}@endif" alt="your image">

  </div>
</div>

            <div class="file-upload">
  <!-- <button class="file-upload-btn" type="button" onclick="$('.file-upload-input').trigger( 'click' )"><img src="assets/img/camera-icon.png" alt=""></button> -->

  <div class="image-upload-wrap" style="display: none;">
    <input class="file-upload-input" type="file" onchange="readURL(this);" accept="image/*">

  </div>

</div>
               </div>
			<label class="text-center">Profile Picture</label>
			</div>
						<div class="col-sm-6 col-xs-12 form-group">
			<label>Name</label>
			<input type="text" class="form-control border-input" readonly value="@if(!empty($user_list->name)) {{$user_list->name}}@endif">
			</div>
			<div class="form-group col-sm-6 col-xs-12 ">
			<label>Email</label>
			<input type="email" class="form-control border-input" readonly value="@if(!empty($user_list->email)) {{$user_list->email}}@endif">
			</div>
			<!--
            <div class="form-group col-sm-6 col-xs-12 ">
            <label>Age</label>
            <input type="text" class="form-control border-input" readonly value="@if(!empty($user_list->age)) {{$user_list->age}}@endif">
            </div>
			
			-->
			
			<div class="form-group col-sm-6 col-xs-12 ">
			<label>Phone Number</label>
			<input type="text" class="form-control border-input" readonly value="@if(!empty($user_list->phone_number)) {{$user_list->phone_number}}@endif">
			</div>
			<!-- <div class="form-group col-sm-6 col-xs-12 ">
			<label>Credit Card</label>
			<input type="Number" class="form-control border-input" value="122344" readonly value="11xxxxxxxx11">
			</div> -->
			<!--
			<div class="form-group col-sm-6 col-xs-12">
			<label>Public Interest</label>
			<?php $explode=explode(',',$user_list->public_interest); 
				foreach ($explode as $explode){
					$interest_name=DB::table('public_interest')->where(['id'=>$explode])->first();
					if($interest_name){
						$nn[]=$interest_name->name;
					}
				}  	
				if(!empty($nn)){
					$implode_name=implode(',',$nn);
				}else{
					$implode_name="";
				}
			
			?>
			<input type="text" class="form-control border-input" readonly value="{{$implode_name}}">
			</div>
			<div class="form-group col-sm-6 col-xs-12">
			<label>Music Interest</label>
			<?php $explode=explode(',',$user_list->music_interest); 
				foreach ($explode as $explode){
					$interest_name=DB::table('music_interest')->where(['id'=>$explode])->first();
					if($interest_name){
						$nn2[]=$interest_name->name;
					}
				}  
				if(!empty($nn2)){
					$implode_name=implode(',',$nn2);
				}else{
					$implode_name="";
				}
				
			
			?>
			<input type="text" class="form-control border-input" readonly value="{{$implode_name}}">
			</div>
			-->
			<div class="form-group col-sm-12 col-xs-12 main-form-update">
			<a href="{{url('edit-organizer/'.$user_list->id)}}" ><input type="button" value="Edit" class="btn btn-info btn-fill btn-wd btn-clr"></a>
			<a href="{{url('delete_organizer/'.$user_list->id)}}">
			<input type="button" value="Delete" class="btn-del btn btn-info btn-fill btn-wd btn-clr"></a>
			</div>
			
			
			</form>
			
			
			</div>
              
			  </div>
        </div>


  
 @endsection('pageContent')