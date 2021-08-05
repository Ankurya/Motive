@extends('layouts.admin_hf')   
@section('title', '')  
@section('pageContent')
<?php //print_r($events);die; ?>
<style>


</style>ddfd
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.css">

            <div class="custom-breadcrumb">
               <div class="container-fluid">
                  <p><a href="{{url('dashboard')}}"> Home</a> <i class="ti-angle-right"></i> <a href="javascript:void(0);"> Event Management</a> <i class="ti-angle-right"></i><a href="javascript:void(0);"> Private Events </a> <i class="ti-angle-right"></i> <span>Private Events Schedule List </span> </p>
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
				<p class="alert alert-success" style="color:black;">{{ Session::get('status')}}</p>
					@endif

					
               <div class="container-fluid">
                  <div class="">
                     <div class="">
                        <table class="table table-striped card table-responsive" id="example" width="100%">
                           <thead>
                              <th>Sr No.</th>
                              <th>Event Name</th>
                              <th>Event Location</th>
                              <th width="100px">Event Date</th>
                              <th>Event Time</th>
							  <th width="150px">Created_at</th>
							  <!--<th width="150px">Event Status</th>-->
                              <th style="width:400px;">Action</th>
                           </thead>
                           <tbody>
						     @php($inc=1)
						   @foreach($events as $events)
                              <tr>
                                 <td>{{$inc++}}</td>
                                 <td>{{$events->event_name}}</td>
                                 <td><?php echo substr($events->event_location,0,25); ?>...</td>
                                 
                                 <td>{{$events->event_date}}</td>
								   
                                 <td>{{$events->event_time}}</td>
								 <td>{{$events->created_at}}</td>
                                 <!--<td>@if($events->status == 1) Not-Approved @else Approved @endif</td>-->
								  <td><a href="{{url('private-view-event-details/'.$events->id)}}" class="view_table">View </a> <a href="{{url('edit-private-event-details/'.$events->id)}}" class="table_block">Edit</a><!--<a href="{{url('delete_private_event/'.$events->id)}}" class="table_block">Delete</a>--></td>
								  
								  
								 
                              </tr>  
							  <?php //$request->session()->forget('status'); ?>
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
	"columnDefs": [ {
		"targets":[-1],
		"bSortable": false,
		"orderable": false
		},{"width": "100px",
		"bSortable": false,
		"orderable": false,
		"targets":[-1],
		} ] 
	});
	
  })
  
  
  </script>	 
         @endsection('pageContent')