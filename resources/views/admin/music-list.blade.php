@extends('layouts.admin_hf')   
@section('title', '')  
@section('pageContent')
            <div class="custom-breadcrumb">
               <div class="container-fluid">
                  <p><a href="{{url('dashboard')}}"> Home</a> <i class="ti-angle-right"></i> <a href="javascript:void(0);"> Music Interest Management</a> <i class="ti-angle-right"></i> <span>Music List </span> </p>
               </div>
            </div>
            <div class="content">
               <div class="container-fluid">
                  <div class="">
                     <div class="content table-full-width pos-rel">
                        <a href="{{url('add-music-interest')}}" class="add-btn btn btn-clr custom-add">Add</a>
                        <table class="table table-striped card table-responsive" id="userlist-table">
                           <thead>
                              <th>Sr No.</th>
                              <th>Event Name</th>

                              <th>Created_at</th>
                              
                              <th>Action</th>
                           </thead>
                           <tbody>
                              <tr>
                                 <td>1</td>
                                 <td>John Doe</td>
                                
                                 <td><span>	2018-02-07 </span> <span class="create_time">15:10:10 </span></td>
                                
                                 <td><a href="javascript:void0;" class="view_table">Delete </a> <a href="{{url('edit-music-interest')}}" class="table_block">Edit</a></td>
                              </tr>
                             
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
            </div>
        @endsection('pageContent')