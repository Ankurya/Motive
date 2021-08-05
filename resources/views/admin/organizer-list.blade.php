@extends('layouts.admin_hf')   
@section('title', '')  
@section('pageContent')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
	<div class="custom-breadcrumb">
	   <div class="container-fluid">
		  <p><a href="{{('dashboard')}}"> Home</a> <i class="ti-angle-right"></i> <a href="javascript:void(0);"> User Management</a> <i class="ti-angle-right"></i> <span>Organizer List </span> </p>
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
			<div class="">
				<div class="content table-full-width" >
				<table class="table table-striped card table-responsive" id="example" >
					<thead>
						<th>Sr No.</th>
						<th class="action">Image</th>
						<th>Name</th>
						<th>Phone Number</th>
						<th>Email</th>
						<th width="1000px";>Created_at</th>
						<th>Block status</th>
						<th  width="1000px";>Action</th>
						
					</thead>
					<tbody>
					   @php($inc=1)
						@foreach($user_list as $user_list)
						<tr>
							<td>{{$inc++}}</td> 
							<td><img src="@if(!empty($user_list->image_url)) {{$user_list->image_url}} @else {{asset('public/admin/img/nopicture.gif')}}@endif"></td>
							<td> @if(!empty($user_list->name)) {{$user_list->name}} @else {{'N/A'}}@endif</td>
							<td>@if(!empty($user_list->phone_number)) {{$user_list->phone_number}} @else {{'N/A'}}@endif</td>
							<td>@if(!empty($user_list->email)) {{$user_list->email}} @else {{'N/A'}}@endif</td>
							<td><span>@if(!empty($user_list->created_at)) {{$user_list->created_at}} @else {{'N/A'}}@endif </span></td>
							<td>@if($user_list->blockStatus == 1) Blocked @else Unblock @endif</td>
							<td><a href="{{url('view-organizer/'.$user_list->id)}}" class="view_table">View </a> <a href="{{url('block_unblock_organizer/'.$user_list->id)}}" class="table_block">@if($user_list->blockStatus == 1)Unblock @else Block @endif </a></td>
						</tr>
						@endforeach
						<?php Session::forget('status') ?>   
					<?php
						header('Cache-Control: no-store, private, no-cache, must-revalidate');
						header('Cache-Control: pre-check=0, post-check=0, max-age=0, max-stale = 0', false);
					?>	
					</tbody>
				</table>
				</div>
			</div>
			
		</div>
	</div>
  <script type="text/javascript" charset="utf8" src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.8.2.min.js"></script>
  <script type="text/javascript" charset="utf8" src="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/jquery.dataTables.min.js"></script>
  <script>
  $(function(){
    $("#example").dataTable({
	"columnDefs": [ 
	     {  
		"targets":[1,3,4,-1],
		"bSortable": false,
		"orderable": false,
		"width": "100%"
		},
		{"width": "2000px",
		"bSortable": false,
		"orderable": false,
		"targets":[-1],
		"width": "1000px"
		} ] 
	});
	
  })
  </script>	
	@endsection('pageContent')