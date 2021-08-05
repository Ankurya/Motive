<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<link rel="apple-touch-icon" sizes="76x76" href="{{url('public/admin/img/apple-icon.png')}}">
	<link rel="icon" type="image/png" sizes="96x96" href="{{url('public/admin/img/favicon.png')}}">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>Mo-Tiv</title>
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
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker3.standalone.css">
</head>
<body>
<style>
.main-content-form .bootstrap-select .dropdown-toggle {
	border-color: #66615B !important;
	outline: none !important;
   }
  .dropdown-menu {
   display: none;
  }
</style>           
<style>
 .datepicker.dropdown-menu{
	 opacity: 1 !important;
 }
 .bootstrap-timepicker-widget.dropdown-menu.open {
	 opacity: 1 !important;
 }
 .custom-btn-select .dropdown-toggle, .dropdown-menu {
    width: 150px;
}
  .datepicker.dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    z-index: 1000;
    display: none;
    float: left;
    min-width: 160px;
    list-style: none;
    background-color: #fff;
    border: 1px solid #ccc;
    border: 1px solid rgba(0, 0, 0, 0.15);
    border-radius: 4px;
    -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
    -moz-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
    -webkit-background-clip: padding-box;
    -moz-background-clip: padding;
    background-clip: padding-box;
    color: #333333;
    font-size: 13px;
    line-height: 1.42857143;
    width: 331px;
}   
/*!
 * Timepicker Component for Twitter Bootstrap
 *
 * Copyright 2013 Joris de Wit
 *
 * Contributors https://github.com/jdewit/bootstrap-timepicker/graphs/contributors
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
.bootstrap-timepicker {
  position: relative;
}
.bootstrap-timepicker.pull-right .bootstrap-timepicker-widget.dropdown-menu {
  left: auto;
  right: 0;
}
.bootstrap-timepicker.pull-right .bootstrap-timepicker-widget.dropdown-menu:before {
  left: auto;
  right: 12px;
}
.bootstrap-timepicker.pull-right .bootstrap-timepicker-widget.dropdown-menu:after {
  left: auto;
  right: 13px;
}
.bootstrap-timepicker .input-group-addon {
  cursor: pointer;
}
.bootstrap-timepicker .input-group-addon i {
  display: inline-block;
  width: 16px;
  height: 16px;
}
.bootstrap-timepicker-widget.dropdown-menu {
  padding: 4px;
}
.bootstrap-timepicker-widget.dropdown-menu.open {
  display: inline-block;
}
.bootstrap-timepicker-widget.dropdown-menu:before {
  border-bottom: 7px solid rgba(0, 0, 0, 0.2);
  border-left: 7px solid transparent;
  border-right: 7px solid transparent;
  content: "";
  display: inline-block;
  position: absolute;
}
.bootstrap-timepicker-widget.dropdown-menu:after {
  border-bottom: 6px solid #FFFFFF;
  border-left: 6px solid transparent;
  border-right: 6px solid transparent;
  content: "";
  display: inline-block;
  position: absolute;
}
.bootstrap-timepicker-widget.timepicker-orient-left:before {
  left: 6px;
}
.bootstrap-timepicker-widget.timepicker-orient-left:after {
  left: 7px;
}
.bootstrap-timepicker-widget.timepicker-orient-right:before {
  right: 6px;
}
.bootstrap-timepicker-widget.timepicker-orient-right:after {
  right: 7px;
}
.bootstrap-timepicker-widget.timepicker-orient-top:before {
  top: -7px;
}
.bootstrap-timepicker-widget.timepicker-orient-top:after {
  top: -6px;
}
.bootstrap-timepicker-widget.timepicker-orient-bottom:before {
  bottom: -7px;
  border-bottom: 0;
  border-top: 7px solid #999;
}
.bootstrap-timepicker-widget.timepicker-orient-bottom:after {
  bottom: -6px;
  border-bottom: 0;
  border-top: 6px solid #ffffff;
}
.bootstrap-timepicker-widget table {
  width: 100%;
  margin: 0;
}
.bootstrap-timepicker-widget table td {
  text-align: center;
  height: 30px;
  margin: 0;
  padding: 2px;
}
.bootstrap-timepicker-widget table td:not(.separator) {
  min-width: 30px;
}
.bootstrap-timepicker-widget table td span {
  width: 100%;
}
.bootstrap-timepicker-widget table td a {
  border: 1px transparent solid;
  width: 100%;
  display: inline-block;
  margin: 0;
  padding: 8px 0;
  outline: 0;
  color: #333;
}
.bootstrap-timepicker-widget table td a:hover {
  text-decoration: none;
  background-color: #eee;
  -webkit-border-radius: 4px;
  -moz-border-radius: 4px;
  border-radius: 4px;
  border-color: #ddd;
}
.bootstrap-timepicker-widget table td a i {
  margin-top: 2px;
  font-size: 18px;
}
.bootstrap-timepicker-widget table td input {width: 25px;margin: 0;text-align: center;border:none}
.bootstrap-timepicker-widget .modal-content {
  padding: 4px;
}
@media (min-width: 767px) {
  .bootstrap-timepicker-widget.modal {
    width: 200px;
    margin-left: -100px;
  }
}
@media (max-width: 767px) {
  .bootstrap-timepicker {
    width: 100%;
  }
  .bootstrap-timepicker .dropdown-menu {
    width: 100%;
  }
}

.custom_error{
		color:red;
		font-size:15px;
		position:absolute !important;
	}
	.profile_left .form-group{
		margin-top:10px;
	}
	
	

</style>
<div class="wrapper user-management-page events-page">
<div class="sidebar" data-background-color="white" data-active-color="danger">
<div class="sidebar-wrapper">
            <div class="logo">
                <a href="{{url('dashboard')}}" class="simple-text">
                    <img src="{{asset('public/admin/img/MO-TIV-LOGO.png')}}">
                </a>
            </div>

            <ul class="nav">
                <li class="@if(Request::is('dashboard')) active @endif" >
                    <a href="{{url('dashboard')}}">
                        <i class="ti-panel"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="sub-menu @if(Request::is('user-list') || Request::is('view-user/*') || Request::is('edit-user/*') || Request::is('organizer-list') || Request::is('view-organizer/*')|| Request::is('edit-organizer/*')) active open-menu @endif" >
                    <a href="{{url('user-list')}}">
                        <i class="ti-user"></i>
                        <p>User Management<i class="fa fa-caret-down pull-right"></i></p>
                    </a>
					<ul class="inner-menu"> 
					<li class="@if(Request::is('user-list')|| Request::is('view-user/*') || Request::is('edit-user/*')) active @endif"><a href="{{url('user-list')}}">
                        <p>User List</p> </a> </li>
					<li class="@if(Request::is('organizer-list')|| Request::is('view-organizer/*') || Request::is('edit-organizer/*')) active @endif"><a href="{{url('organizer-list')}}">
                        <p>Organizer List</p> </a> </li>
					</ul>
                </li>  
 			   <li class="sub-menu @if(Request::is('private-events') || Request::is('private-view-event-details/*') || Request::is('edit-private-event-details/*') || Request::is('public-events')|| Request::is('private-view-event-details/*')|| Request::is('public-view-event-details/*') || Request::is('edit_public_event/*') ) active open-menu @endif">
                     <a href="{{url('private-events')}}">
                        <i class="ti-view-list-alt"></i>
                        <p>Event Organizer Management<i class="fa fa-caret-down pull-right"></i></p>
                     </a>
					 <ul class="inner-menu">
                        <li class="@if(Request::is('private-events') || Request::is('private-view-event-details/*') || Request::is('edit-private-event-details/*')) active @endif">
                           <a href="{{url('private-events')}}">
                              <p>Private Events</p>   
                           </a>
                        </li>
                        <li class="@if(Request::is('public-events')|| Request::is('public-view-event-details/*')|| Request::is('edit_public_event/*')) active @endif">
                           <a href="{{url('public-events')}}">
                              <p>Public Events</p>
                           </a>   
                        </li>
                     </ul>
                  </li>
			    <li class="@if(Request::is('payment-list')) active @endif">
                    <a href="{{url('payment-list')}}">
                        <i class="fa fa-money"></i>
                        <p>Payment Management</p>
                    </a>
                </li>
                <li class="@if(Request::is('coin-management')) active @endif">
                    <a href="{{url('coin-management')}}">
                        <i class="ti-money"></i>
                        <p>Coin Management</p>
                    </a>
                </li>
                <li class="@if(Request::is('public-interest') || Request::is('edit-public-interest/*')) active @endif">
                    <a href="{{url('public-interest')}}">
                        <i class="ti-map"></i>
                        <p>Public Interest</p>
                    </a>
                </li>
				<li class="@if(Request::is('music-interest'))||Request::is('edit-music-interest/*') active @endif">
                    <a href="{{url('music-interest')}}">
                        <i class="ti-map"></i>
                        <p>Music Interest</p>
                    </a>
                </li>
				
				<li class="@if(Request::is('send-push-message')) active @endif">
                    <a href="{{url('admin/send-push-message')}}">
                        <i class="ti-comment-alt"></i>
                        <p>Send Message</p>
                    </a>
                </li>
				
				<li class="@if(Request::is('maintenance')) active @endif">
                    <a href="{{url('admin/maintenance')}}">
                        <i class="ti-comment-alt"></i>
                        <p>Maintenance</p>
                    </a>
                </li>
            </ul>
    	</div>
	</div>	
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

   
<?php //// echo'<pre>'; print_r($event_detail);die; ?>
		<div class="custom-breadcrumb">
		<div class="container-fluid">
		<p><a href="{{url('dashboard')}}"> Home</a> <i class="ti-angle-right"></i> <a href="javascript:void(0);"> Event Management</a> <i class="ti-angle-right"></i> <a href="javascript:void(0);"> Public Events</a> <i class="ti-angle-right"></i> <span>Edit Event Details </span> </p>
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
			<form class="main-content-form" action="{{url('update_public_event')}}"  method="post" enctype="multipart/form-data">
				{{csrf_field()}}
				@if($event_detail->event_media_type == 1)
					<div class="form-group col-sm-16 col-xs-12" >
					<!-- <div class="left-form-content"><label>Profile Picture</label></div> -->
					<div class="pic-video-uploader">
					<div class="pic-uploader">
					<div class="img-sec upload-pro-pic">  
						<div class="img-inner-sec"> 
							<div class="file-upload-content">
							<img class="file-upload-image" src="@if(!empty($event_detail->event_image_url)) {{$event_detail->event_image_url}} @else {{asset('public/admin/img/dummy.png')}}@endif" alt="your image">
							</div>
						</div>
					
						
							<div class="file-upload">
						   <button class="file-upload-btn" type="button" onclick="$('.file-upload-input').trigger( 'click' )"><img src="{{asset('public/admin/img/camera-icon.png')}}" alt=""></button> 
							<div class="image-upload-wrap" style="display: none;">
							<input class="file-upload-input" name="image" type="file" onchange="readURL(this);" accept="image/*">
							</div>
							</div>
						
					</div>
					<label class="text-center"> Event Gallery</label>
					</div>
					
					
					</div>
					
					
					</div>
			
				@elseif($event_detail->event_media_type == 2)
			
				<div class="form-group col-sm-16 col-xs-12" >
					<!-- <div class="left-form-content"><label>Profile Picture</label></div> -->
					<div class="pic-video-uploader">
					
					<div class="pic-uploader">
					<div class="img-sec upload-pro-pic">  
						<div class="img-inner-sec "> 
							<div class="file-upload-content">
							<video width="150" height="110" controls>
						  <source id="click_video_thubnail_hide_video" src="@if(!empty($event_detail->event_video_url)) {{$event_detail->event_video_url}} @endif" type="video/mp4">
							Your browser does not support the video stream.
						</video>
							
							</div>
						</div>
							
						<div class="file-upload">
							<button class="file-upload-btn" type="button" onclick="$('#click_video_thubnail_video').trigger( 'click' )"><img src="{{asset('public/admin/img/camera-icon.png')}}" alt=""></button> 
							<div class="image-upload-wrap" id ="click_video_thubnail_show_video" style="display: none;">
							<input class="file-upload-input"  id="click_video_thubnail_video" name="video_thumbnail" type="file" onchange="readURL_video(this);" accept="image/*">
							</div>
						</div>
						
					</div>
					<label class="text-center">Event Video</label>
					</div>
						<div class="video-uploader">
						<div class="img-sec upload-pro-pic">  
							<div class="img-inner-sec"> 
								<div class="" >
								<img class="file-upload-image" id="click_video_thubnail_hide" src="@if(!empty($event_detail->event_image_url)) {{$event_detail->event_image_url}} @else {{asset('public/admin/img/dummy.png')}}@endif" alt="your image">
								</div>
							</div>
							<div class="file-upload">
							   <button class="file-upload-btn" type="button" onclick="$('#click_video_thubnail').trigger( 'click' )"><img src="{{asset('public/admin/img/camera-icon.png')}}" alt=""></button> 
								<div class="" id ="click_video_thubnail_show" style="display: none;">
								<input class=""  id="click_video_thubnail" name="image" type="file" onchange="readURL1(this);" accept="image/*">
								</div>
							</div>
						</div>
						<label class="text-center"> Video Thumbnail</label>
						</div> 
					
					</div>
					
					
					</div>
				@endif
			
			<div class="col-sm-6 col-xs-12 form-group">
			<label>Event Name</label>
			<input type="text" name="event_name" class="form-control border-input" value="{{$event_detail->event_name}}">
			</div>   
			
			<div class="form-group col-sm-6 col-xs-12 ">
			<label>Event Location</label>
			<input type="text" id="autocomplete" name="event_address" class="form-control border-input" value="{{$event_detail->event_location}}" >
			</div>
			<input type="hidden" name="event_id" value="{{$event_detail->id}}"></input>
			<input type="hidden" class="field" name="lat"  id="latitude"></input>
			<input type="hidden" class="field" name="long" id="longitude"></input>
			<div class="form-group col-sm-6 col-xs-12 ">
			<label>Event Description</label>
			<!--<input type="text" class="form-control border-input" name="event_description" value="{{$event_detail->description}}" >-->
			<textarea name="event_description" row="6" columns="12">{{$event_detail->description}}</textarea>
			</div>
			
			<div class="form-group col-sm-6 col-xs-12 ">
			<label>Event Date</label>
			<input type="text" class="form-control border-input "  id="datepicker" name="event_date"  value="{{$event_detail->event_date}}" >
			</div>
			<div class="form-group col-sm-6 col-xs-12">
			<label>Start Time</label>
			<input type="text" class="form-control border-input bootstrap-timepicker" name="event_time" value="{{$event_detail->event_time}}" >
			</div>
			<div class="form-group col-sm-6 col-xs-12">
			<label>End Time</label>
			<input type="text" class="form-control border-input bootstrap-timepicker" name="end_time" value="{{$event_detail->end_time}}" >
			</div>
			
			
			<div class="form-group col-sm-6 col-xs-12">
			<label>Age</label>
			<input type="text" class="form-control border-input" name="age" value="@if($event_detail->age_restrictions !=0) {{(int)$event_detail->age_restrictions}}  @endif" placeholder='Enter restriction age'>
			</div>
			<?php $public_int=DB::table('public_interest')
					->leftJoin('event_public_interest_list', 'event_public_interest_list.public_interest_id', '=', 'public_interest.id')
					->where(['event_id'=>$event_detail->id])
					->first();
					if(!empty($public_int)){
						$public_cat_id=$public_int->public_interest_id;
					}else{
						$public_cat_id='0';
					}
					//print_r($public_int);die;
					?>
		    <div class="form-group col-sm-6 col-xs-12">
                       <label>Public Category</label>
                    	 <select name="public_interest" class="form-control border-input">
					    <option disabled selected value> -- select an option -- </option>	
						<?php $get_public_interest = DB::table('public_interest')->get(); 
						foreach($get_public_interest  as $get_public_name){ ?>	
							<option value="{{$get_public_name->id}}" <?php  if(strpos($public_cat_id,(string)$get_public_name->id) !== false) echo "selected"; ?> >{{$get_public_name->name}}</option>
						<?php }?>
						 </select>	   
                        
            </div>
			<div class="form-group col-sm-6 col-xs-12">
                       <label>Repeat Interval</label>
                    	 <select name="repeat_interval" class="form-control border-input" >
						<option disabled selected value> -- select an option -- </option>
						<?php $get_public_interest = DB::table('repeat_intervals')->get(); 
						foreach($get_public_interest  as $get_public_interest){ ?>	
							<option value="{{$get_public_interest->name}}" <?php  if(strpos($event_detail->repeat_interval,(string)$get_public_interest->name) !== false) echo "selected"; ?> >
							<?php 
							if($get_public_interest->name == 'one_day'){
								 echo 'One day';
							 }elseif($get_public_interest->name == 'weekly'){
								 echo 'Weekly';
							 }elseif($get_public_interest->name == '2_weekly'){
								 echo 'Fortnightly';
							 }elseif($get_public_interest->name == 'monthly'){
								 echo 'Monthly';
							 }else{
								 echo 'wrong format';
							 } 
							?></option>
						<?php }?>
						 </select>	   
        	</div>
			 <?php $music_int=DB::table('music_interest')
				->leftJoin('event_music_interest_list', 'event_music_interest_list.music_interest_id', '=', 'music_interest.id')
				->where(['event_id'=>$event_detail->id])
				->first();
				if(!empty($music_int)){
					$music_cat_id=$music_int->id;
				}else{
					$music_cat_id='0';
				}
				?>
			<div class="form-group col-sm-6 col-xs-12">
				<label>Music Category</label>
				<select name="music_interest[]"  class="form-control border-input selectpicker" multiple>
						<?php $get_music_interest = DB::table('music_interest')->get();
						foreach($get_music_interest  as $get_music_name){ ?>	
							<option value="{{$get_music_name->id}}"<?php  if(strpos($event_detail->music_int_id,(string)$get_music_name->id) !== false) echo "selected";?> >{{$get_music_name->name}}</option>
						<?php }?>
				</select>
			</div>
			
			<div class="form-group col-sm-12 col-xs-12 main-form-update">
			<input type="submit" value="Update"  class="btn btn-info btn-fill btn-wd btn-clr">
			
			</div>
			
			</form>
			</div>
            </div>
	    </div>
	    </div>
	</div>
</body>	

  <!--   Core JS Files   -->
    <script src="{{url('public/admin/js/jquery-1.10.2.js')}}" type="text/javascript"></script>
	<script src="{{url('public/admin/js/bootstrap.min.js')}}" type="text/javascript"></script>
	
	<!-- Paper Dashboard DEMO methods, don't include it in your project! -->
	<script src="{{url('public/admin/js/demo.js')}}"></script>
	<script src="{{url('public/admin/js/custom.js')}}"></script>
	
	
<!--multiple select picker -->
<script src="https://code.jquery.com/jquery-3.3.1.js"  integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
<!--multiple select picker end -->	
<script src="https://code.jquery.com/jquery-1.7.2.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script src='http://maps.googleapis.com/maps/api/js?v=3&sensor=false&amp;libraries=places&key=AIzaSyBYToPFnw0EICcKRzKWU3_CzN539HGDL2w'></script>
<!--datepicker -->
<script src="https://code.jquery.com/jquery-1.7.2.min.js"></script>
<script  src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.js"></script>

<!----->   

<script src="http://jdewit.github.io/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>
 

 <script>
$(function () {
    $('.bootstrap-timepicker').timepicker({
       timeFormat: 'G:i',
   // show2400: true,
	 showMeridian: false 
    });
});

var dateToday = new Date();
	$("#datepicker").datepicker({
		// minDate: new Date(),
		startDate: new Date(),
		format:"yyyy-mm-dd"
	});
</script>
<script>
  // This example displays an address form, using the autocomplete feature
// of the Google Places API to help users fill in the information.

$("#autocomplete").on('focus', function () {
    geolocate();
});

var placeSearch, autocomplete;
var componentForm = {
    street_number: 'short_name',
    route: 'long_name',
    locality: 'long_name',
    administrative_area_level_1: 'short_name',
    country: 'long_name',
    postal_code: 'short_name'
};

function initialize() {
    // Create the autocomplete object, restricting the search
    // to geographical location types.
    autocomplete = new google.maps.places.Autocomplete(
    /** @type {HTMLInputElement} */ (document.getElementById('autocomplete')), {
        types: ['geocode']
    });
    // When the user selects an address from the dropdown,
    // populate the address fields in the form.
    google.maps.event.addListener(autocomplete, 'place_changed', function () {
        fillInAddress();
    });
}

// [START region_fillform]
function fillInAddress() {
    // Get the place details from the autocomplete object.
    var place = autocomplete.getPlace();

    document.getElementById("latitude").value = place.geometry.location.lat();
    document.getElementById("longitude").value = place.geometry.location.lng();

    for (var component in componentForm) {
        document.getElementById(component).value = '';
        document.getElementById(component).disabled = false;
    }

    // Get each component of the address from the place details
    // and fill the corresponding field on the form.
    for (var i = 0; i < place.address_components.length; i++) {
        var addressType = place.address_components[i].types[0];
        if (componentForm[addressType]) {
            var val = place.address_components[i][componentForm[addressType]];
            document.getElementById(addressType).value = val;
        }
    }
}
// [END region_fillform]

// [START region_geolocation]
// Bias the autocomplete object to the user's geographical location,
// as supplied by the browser's 'navigator.geolocation' object.
function geolocate() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            var geolocation = new google.maps.LatLng(
            position.coords.latitude, position.coords.longitude);

            var latitude = position.coords.latitude;
            var longitude = position.coords.longitude;
            document.getElementById("latitude").value = latitude;
            document.getElementById("longitude").value = longitude;

            autocomplete.setBounds(new google.maps.LatLngBounds(geolocation, geolocation));
        });
    }

}

initialize();

function readURL1(input) {
  if (input.files && input.files[0]) {

    var reader = new FileReader();

    reader.onload = function(e) {
      $('#click_video_thubnail_show').hide();
	  // alert(e.target.result);
      $('#click_video_thubnail_hide').attr('src', e.target.result);
      $('#click_video_thubnail_hide').show();
      $('.image-title').html(input.files[0].name);
    };

    reader.readAsDataURL(input.files[0]);

  } else {
    removeUpload1();
  }
}

function removeUpload1() {
  $('#click_video_thubnail').replaceWith($('#click_video_thubnail').clone());
  $('#click_video_thubnail_hide').hide();
  $('#click_video_thubnail_show').show();
}
function readURL_video(input) {
  if (input.files && input.files[0]) {

    var reader = new FileReader();

    reader.onload = function(e) {
      $('#click_video_thubnail_show_video').hide();
	  // alert(e.target.result);
      $('#click_video_thubnail_hide_video').attr('src', e.target.result);
      $('#click_video_thubnail_hide_video').show();
      $('.image-title').html(input.files[0].name);
    };

    reader.readAsDataURL(input.files[0]);

  } else {
    removeUpload1();
  }
}

function removeUpload1() {
  $('#click_video_thubnail_video').replaceWith($('#click_video_thubnail_video').clone());
  $('#click_video_thubnail_hide_video').hide();
  $('#click_video_thubnail_show_video').show();
}

// [END region_geolocation]

</script>

</html>
