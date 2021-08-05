@extends('layouts.admin_hf')   
@section('title', '')  
@section('pageContent')
<?php //echo '<pre>';print_r($event_detail);die; ?>
		<div class="custom-breadcrumb">
		<div class="container-fluid">
		<p><a href="{{url('dashboard')}}"> Home</a> <i class="ti-angle-right"></i> <a href="javascript:void(0);">Event Report</a><i class="ti-angle-right"></i> <a href="javascript:void(0);"> View Event Report</a> <i class="ti-angle-right"></i></p>
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
			<div class="form-group col-sm-16 col-xs-12" >
			<!-- <div class="left-form-content"><label>Profile Picture</label></div> -->
			
			<div class="img-sec upload-pro-pic">  
            <div class="img-inner-sec"> 
                <div class="file-upload-content">
				<img class="file-upload-image" src="@if(!empty($event_detail->event_image_url)) {{$event_detail->event_image_url}} @else {{asset('admin/img/dummy.png')}}@endif" alt="your image">
				</div>
			</div>
			<div class="file-upload">
				<!--   <button class="file-upload-btn" type="button" onclick="$('.file-upload-input').trigger( 'click' )"><img src="assets/img/camera-icon.png" alt=""></button> -->
			<div class="image-upload-wrap" style="display: none;">
			<input class="file-upload-input" type="file" onchange="readURL(this);" accept="image/*">
			</div>
			</div>
            </div>
			<label class="text-center"> Event Gallery</label>
			</div>
						<div class="col-sm-6 col-xs-12 form-group">
			<label>Event Name</label> 
			<input type="text" class="form-control border-input" value="{{$event_detail->event_name}}" readonly>
			</div>
			<div class="form-group col-sm-6 col-xs-12 ">
			<label>Event Location</label>
			<input type="text" class="form-control border-input" value="{{$event_detail->event_location}}" readonly>
			</div>
			<div class="form-group col-sm-6 col-xs-12 ">
			<label>Event Description</label>
			<input type="text" class="form-control border-input" value="{{$event_detail->description}}" readonly>
			</div>
			
			<div class="form-group col-sm-6 col-xs-12 ">
			<label>Event Date</label>
			<input type="text" class="form-control border-input" value="{{$event_detail->event_date}}" readonly>
			</div>
			<div class="form-group col-sm-6 col-xs-12">
			<label>Start Time</label>
			<input type="text" class="form-control border-input" value="{{$event_detail->event_time}}" readonly>
			</div>
			<div class="form-group col-sm-6 col-xs-12">
			<label>End Time</label>
			<input type="text" class="form-control border-input" value="{{$event_detail->end_time}}" readonly>
			</div>
			<div class="form-group col-sm-6 col-xs-12">
			<label>Age</label>
			<input type="text" class="form-control border-input" value="@if(!empty($event_detail->age_restrictions)){{$event_detail->age_restrictions}} @else {{'N/A'}} @endif" readonly>
			</div>
			<div class="form-group col-sm-6 col-xs-12">
			<label>Public Category</label>
			<?php $public_int=DB::table('public_interest')
					->leftJoin('event_public_interest_list', 'event_public_interest_list.public_interest_id', '=', 'public_interest.id')
					->where(['event_id'=>$event_detail->id])
					->first(); 
					
					?>
			<input type="text" class="form-control border-input" value="@if(!empty($public_int->name)) {{$public_int->name}} @else {{'N/A'}} @endif" readonly>
			</div>
			<div class="form-group col-sm-6 col-xs-12">
			<label>Music Category</label>
			<?php  $public_int=DB::table('music_interest')
					->leftJoin('event_music_interest_list', 'event_music_interest_list.music_interest_id', '=', 'music_interest.id')
					->where(['event_id'=>$event_detail->id])
					->first(); 
					//print $public_int;die('ok');
					 $music_interests=explode(",",$event_detail->music_int_id);
					if(count($music_interests)>0){
					foreach	($music_interests as $music_interests){
						$get_music_interest = DB::table('music_interest')->where(['id'=>$music_interests])->first();
						if(!empty($get_music_interest)){
							$names[]=$get_music_interest->name;
						}
					}
					
					}
					if(empty($names)){
						$names=array();
					} 
					$music_interet_name=implode(",",$names); 
					?>
					
			<input type="text" class="form-control border-input" value="@if(!empty($music_interet_name)) {{$music_interet_name}} @else {{'N/A'}} @endif" readonly>
			</div>
			<div class="form-group col-sm-6 col-xs-12">
			<label>Guests List</label>
			
			 <div class="btn-group custom-btn-select">
			<button type="button" class="btn btn-default dropdown-toggle form-control text-left border-input" data-toggle="dropdown" readonly aria-haspopup="true" aria-expanded="false">
			  Guests
			  <span class="fa fa-caret-down pull-right"></span>
			</button>
    
			<ul class="dropdown-menu">
			<?php 	 $users=DB::table('invitations')
					->leftJoin('users', 'users.id', '=', 'invitations.receiver_id')
					->where(['request_status'=>1,'event_id'=>$event_detail->id])
					->select('users.name','users.email','users.image_url','users.id')
					->get();
					foreach($users as $user){ 
			  
			?>
					 
			  <li>
				<a href="#" title="Select this card"><img src="@if(!empty($user->image_url)) {{$user->image_url}} @else {{asset('admin/img/dummy.png')}}@endif">{{$user->name}}</a>
				
			  </li>
					<?php  } ?>
			</ul>
			</div>
			
			</div>
			<div class="form-group col-sm-6 col-xs-12">     
			<label>Age</label>   
			<input type="text" class="form-control border-input" value="@if(!empty($event_detail->age_restrictions)) {{$event_detail->age_restrictions}} @else {{'N/A'}}  @endif" readonly>
			</div>
			<div class="form-group col-sm-6 col-xs-12">
			<label>Repeat Interval</label>
			<input type="text" class="form-control border-input" 
			value="<?php if($event_detail->repeat_interval == 'one_day'){
				     echo 'One day';
			     }elseif($event_detail->repeat_interval == 'weekly'){
					 echo 'Weekly';
				 }elseif($event_detail->repeat_interval == '2_weekly'){
					 echo 'Fortnightly';
				 }elseif($event_detail->repeat_interval == 'monthly'){
					 echo 'Monthly';
				 }else{
					 echo 'wrong format';
				 }  
			?>"
			 readonly>
			</div>
			<div class="form-group col-sm-12 col-xs-12">
			<label>Report Message</label>
			<textarea class="form-control border-input" readonly>{{$reports->message}}</textarea>
			</div>
			<div class="form-group col-sm-12 col-xs-12 main-form-update">
			<a href="{{url('block-event/'.$reports->id)}}" class="view_table"><input type="button" value="Block" class="btn btn-info btn-fill btn-wd btn-clr"></a>
			<a href="{{url('delete_report_event/'.$event_detail->id)}}" class="view_table"><input type="button" value="Delete" class="btn btn-info btn-fill btn-wd btn-clr btn-del"></a>
			
			</div>
			</form>
			</div>
            </div>
        </div>
  
@endsection('pageContent')
