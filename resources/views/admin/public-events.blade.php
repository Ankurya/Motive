@extends('layouts.admin_hf')   
@section('title', '')  
@section('pageContent')
<?php //print_r($events);die; ?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
            <div class="custom-breadcrumb">
               <div class="container-fluid">
                  <p><a href="{{url('dashboard')}}"> Home</a> <i class="ti-angle-right"></i> <a href="javascript:void(0);"> Event Management</a> <i class="ti-angle-right"></i><a href="javascript:void(0);"> Public Events </a> <i class="ti-angle-right"></i> <span>Event List </span> </p>
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
                     <div class="content table-full-width">
                        <table class="table table-striped card table-responsive" id="userlist-table">
                           <thead>
                              <th>Sr No.</th>
                              <th>Event Name</th>
                              <th>Event Location</th>
                              <th width="100px">Event Date</th>
                              <th>Event Time</th>
							  <th width="150px">Created_at</th>
                              <th width="150px">Event Status</th>
                              <th width="250px">Action</th>
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
                                 <td>@if($events->status == 1) Not-Approved @else Approved @endif</td>
								
                                 <td><a href="{{url('public-view-event-details/'.$events->id)}}" class="view_table">View </a> <a href="{{url('block_unblock_public_event/'.$events->id)}}" class="table_block">@if($events->status == 1) Approve @else Un-Approve @endif</a></td>
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
<script>
      $(document).ready(function(){
         $('#userlist-table').DataTable({
			"columnDefs": [ {
				"targets":[7],
				"bSortable": false,
				"orderable": false
				} ] 
		});
      responsive: true
      });
   </script>			
   @endsection('pageContent')

   
