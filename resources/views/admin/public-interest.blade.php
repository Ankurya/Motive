@extends('layouts.admin_hf')   
@section('title', '')  
@section('pageContent')
<?php //print_r($public_interest);die;?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
	<div class="custom-breadcrumb">
	   <div class="container-fluid">
		  <p><a href="{{url('dashboard')}}"> Home</a> <i class="ti-angle-right"></i> <a href="javascript:void(0);"> Public Interest Management</a> <i class="ti-angle-right"></i> <span>Interest List </span> </p>
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
			<div class="content table-full-width pos-rel">
			 <a href="{{url('add-public-interest')}}" class="add-btn btn btn-clr custom-add">Add</a>
				<table class="table table-striped card table-responsive" id="example">
				   <thead>
					  <th>Sr No.</th>
					  <th> Name</th>
					  <th>Created_at</th>
					  <th>Action</th>
				   </thead>
				   <tbody>
				    @php($inc=1)
				   @foreach($public_interest as $public_interest)
					  <tr>
						 <td>{{$inc++}}</td>
						 <td>{{$public_interest->name}}</td>
						 <td>{{$public_interest->created_at}}</td>
						<td><a href="{{url('delete_public_interest/'.$public_interest->id)}}" class="view_table">Delete </a> <a href="{{url('edit-public-interest/'.$public_interest->id)}}" class="table_block">Edit</a></td>
					  </tr>
					@endforeach  
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
	"columnDefs": [ {
		"targets":[-1],
		"bSortable": false,
		"orderable": false
		} ] 
	});
	
  })
  
  
  </script>	
@endsection('pageContent')