@extends('layouts.admin_hf')   
@section('title', '')  
@section('pageContent')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
            <div class="custom-breadcrumb">
               <div class="container-fluid">
                  <p><a href="{{url('dashboard')}}"> Home</a> <i class="ti-angle-right"></i> <a href="javascript:void(0);"> Payment Management</a> <i class="ti-angle-right"></i> <span>Payment List </span> </p>
               </div>
            </div>
            <div class="content">
               <div class="container-fluid">
                  <div class="">
                     <div class="content table-full-width">
                        <table class="table table-striped card table-responsive" id="userlist-table">
                           <thead>
                              <th>Sr No.</th>
                              <th>Event Name</th>
                              <th>Paid Coin</th>
                              <th>Event Date</th>
                              <th>User Name</th>
                              <th>User Email</th>
                              <th>Booking Date</th>
                              <th>Organizer Name</th>
                              <th>Action</th>
                           </thead>
                           <tbody>
                              <tr>
                                 <td>1</td>
                                 
                                 <td>John Doe</td>
								 <td> 5</td>
                                 <td>2018-02-08</td>
                                 <td>John</td>
                                 <td>John@gmail.com</td>
                                 <td><span>	2018-02-07 </span> <span class="create_time">15:10:10 </span></td>
                               
								 <td>abc</td>
								 <td>pay</td>
                              </tr>
                              <tr>
                                 <td>2</td>
                                 
                                 <td>John Doe</td>
								  <td> 5</td>
                                 <td>2018-02-08</td>
                                 <td>John</td>
								 <td>John@gmail.com</td>
                                 <td><span>	2018-02-07 </span> <span class="create_time">15:10:10 </span></td>
                                 
								 <td>abc</td>
								 <td>pay</td>
                              </tr>
                              <tr>
                                 <td>3</td>
                                 
                                 <td>John Doe</td>
								  <td> 5</td>
                                 <td>2018-02-08</td>
                                 <td>John</td>
								 <td>John@gmail.com</td>
                                 <td><span>	2018-02-07 </span> <span class="create_time">15:10:10 </span></td>
                                
								 <td>abc</td>
								 <td>pay</td>
                              </tr>
                              <tr>
                                 <td>4</td>
                                 
                                 <td>John Doe</td>
								  <td> 5</td>
                                 <td>2018-02-08</td>
                                 <td>John</td>
								 <td>John@gmail.com</td>
                                 <td><span>	2018-02-07 </span> <span class="create_time">15:10:10 </span></td>

								 <td>abc</td>
								 <td>pay</td>
                              </tr>
                              <tr>
                                 <td>5</td>
                                 
                                 <td>John Doe</td>
								  <td> 5</td>
                                 <td>2018-02-08</td>
                                 <td>John</td>
								 <td>John@gmail.com</td>
                                 <td><span>	2018-02-07 </span> <span class="create_time">15:10:10 </span></td>
                                
								 <td>abc</td>
								 <td>paid</td>
                              </tr>
                              <tr>
                                 <td>6</td>
                                 
                                 <td>John Doe</td>
								  <td> 5</td>
                                 <td>2018-02-08</td>
                                 <td>John</td>
								 <td>John@gmail.com</td>
                                 <td><span>	2018-02-07 </span> <span class="create_time">15:10:10 </span></td>
                                 
								 <td>abc</td>
								 <td>pay</td>
                              </tr>
                              <tr>
                                 <td>7</td>
                                 
                                 <td>John Doe</td>
								  <td> 5</td>
                                 <td>2018-02-08</td>
                                 <td>John</td>
								 <td>John@gmail.com</td>
                                 <td><span>	2018-02-07 </span> <span class="create_time">15:10:10 </span></td>

								 <td>abc</td>
								 <td>pay</td>
                              </tr>
                              <tr>
                                 <td>8</td>
                                 
                                 <td>John Doe</td>
								  <td> 5</td>
                                 <td>2018-02-08</td>
                                 <td>John</td>
								 <td>John@gmail.com</td>
                                 <td><span>	2018-02-07 </span> <span class="create_time">15:10:10 </span></td>
                                
								 <td>abc</td>
								 <td>paid</td>
                              </tr>
                              <tr>
                                 <td>9</td>
                                 
                                 <td>John Doe</td>
								  <td> 5</td>
                                 <td>2018-02-08</td>
                                 <td>John</td>
								 <td>John@gmail.com</td>
                                 <td><span>	2018-02-07 </span> <span class="create_time">15:10:10 </span></td>
                               
								 <td>abc</td>
								 <td>pay</td>
                                 
                              </tr>
                              <tr>
                                 <td>10</td>
                                 
                                 <td>John Doe</td>
								  <td> 5</td>
                                 <td>2018-02-08</td>
                                 <td>John</td>
								 <td>John@gmail.com</td>
                                 <td><span>	2018-02-07 </span> <span class="create_time">15:10:10 </span></td>
								 <td>abc</td>
								 <td>pay</td>
                                 
                              </tr>
                              <tr>
                                 <td>11</td>
                                 
                                 <td>John Doe</td>
								  <td> 5</td>
                                 <td>2018-02-08</td>
                                 <td>John</td>
								 <td>John@gmail.com</td>
                                 <td><span>	2018-02-07 </span> <span class="create_time">15:10:10 </span></td>
                                 
								 <td>abc</td>
								 <td>pay</td>
								 
                              </tr>
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
            </div>
			
    @endsection('pageContent')
	