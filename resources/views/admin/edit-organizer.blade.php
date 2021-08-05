@extends('layouts.admin_hf')   
@section('title', '')  
@section('pageContent')
<style>
.main-content-form .bootstrap-select .dropdown-toggle {
	border-color: #66615B !important;
	outline: none !important;
}
.dropdown-menu {
    background-color: #FFFCF5;
    border: 0 none;
    border-radius: 6px;
    display: block;
    margin-top: 10px;
    padding: 0px;
    position: absolute;
    visibility: visible;
    z-index: 9000;
    opacity: 1;
    display: none;
    filter: alpha(opacity=0);
    -webkit-box-shadow: 0 2px rgba(17, 16, 15, 0.1), 0 2px 10px rgba(17, 16, 15, 0.1);
    box-shadow: 0 2px rgba(17, 16, 15, 0.1), 0 2px 10px rgba(17, 16, 15, 0.1);
}
</style>
	<div class="custom-breadcrumb">
	<div class="container-fluid">
	<p><a href="index.html"> Home</a> <i class="ti-angle-right"></i> <a href="javascript:void(0);"> User Management</a> <i class="ti-angle-right"></i> <span>Edit User </span> </p>
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
			<form action="{{url('update_organizer')}}" method="post" class="main-content-form" enctype="multipart/form-data">
				{{ csrf_field() }}
				<div class="form-group col-sm-16 col-xs-12" >
				<!-- <div class="left-form-content"><label>Profile Picture</label></div> -->
					<div class="img-sec upload-pro-pic">  
					<div class="img-inner-sec"> 
					<div class="file-upload-content">
					<img class="file-upload-image" src="@if(!empty($user_list->image_url)) {{$user_list->image_url}} @else {{asset('admin/img/dummy.png')}}@endif"" alt="your image">

					</div>
					</div>

					<div class="file-upload">
					<button class="file-upload-btn" type="button" onclick="$('.file-upload-input').trigger( 'click' )"><img src="{{url('public/admin/img/camera-icon.png')}}" alt=""></button>

					<div class="image-upload-wrap" style="display: none;">
					<input class="file-upload-input" type="file" name="image" onchange="readURL(this);" accept="image/*">
					</div>

					</div>
					</div>
					<label class="text-center">Upload Profile Picture</label>
				</div>
				<div class="col-sm-6 col-xs-12 form-group">
				<label>Name</label>
				<input type="text" name="name"   class="form-control border-input" value="@if(!empty($user_list->name)) {{$user_list->name}}@endif">
				</div>
				<div class="form-group col-sm-6 col-xs-12 ">
				<label>Email</label>
				<input type="email" name="email" class="form-control border-input" value="@if(!empty($user_list->email)) {{$user_list->email}}@endif" disabled>
				</div>
				<div class="form-group col-sm-6 col-xs-12 ">
				<label>Phone Number</label>
				<input type="text" name="phone" class="form-control border-input" value="@if(!empty($user_list->phone_number)) {{$user_list->phone_number}}@endif">
				</div>
				<!--
				<div class="form-group col-sm-6 col-xs-12 ">
				<label>Credit Card</label>
					<input type="Number" name="card_number" class="form-control border-input" value="122344" disabled>
				</div>
				
				-->
				<!--
				<div class="form-group col-sm-6 col-xs-12">
				   <label>Public Interest</label>
					 <select name="public_interest[]" class="selectpicker" multiple data-actions-box="true" >
					<?php $get_public_interest = DB::table('public_interest')->get(); 
					foreach($get_public_interest  as $get_public_interest){ ?>	
						<option value="{{$get_public_interest->id}}" <?php  if(strpos($user_list->public_interest,(string)$get_public_interest->id) !== false) echo "selected"; ?> >{{$get_public_interest->name}}</option>
					<?php }?>
					 </select>	   
                </div>
				<div class="form-group col-sm-6 col-xs-12">
                       <label>Music Interest</label>
                    	 <select name="music_interest[]" class="selectpicker" multiple data-actions-box="true">
						<?php $get_music_interest = DB::table('music_interest')->get();
						foreach($get_music_interest  as $get_music_name){ ?>	
							<option value="{{$get_music_name->id}}"<?php  if(strpos($user_list->music_interest,(string)$get_music_name->id) !== false) echo "selected";?> >{{$get_music_name->name}}</option>
						<?php }?>
						 </select>	   
                </div>-->
				<input type="hidden" name='user_id' value="{{$user_list->id}}">
				<div class="form-group col-sm-12 col-xs-12 main-form-update">
				<a href=""><input type="submit" value="Update" class="btn btn-info btn-fill btn-wd btn-clr"></a>
				</div>
			</form>
		</div>
     </div>
       


 @endsection('pageContent')