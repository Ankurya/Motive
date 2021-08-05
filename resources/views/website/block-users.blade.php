@extends('website.common')
@section('title', 'Motive')

@section('content')
<style type="text/css">

table#myTable tr td {
    width: 100%;
}
.user-wrap img {
    width: 46px;
    height: 46px!important;
    border-radius: 100%;
}
.alert-success {
    color: #3c763d;
    background-color: #dff0d8;
    width: 666px;
    margin-left: 236px;
    border-color: #d6e9c6;
}
</style>
<section class="bg-color main-section"> 
    <div class="container">
        <div class="heading-top">
            <h1>BLOCKED USERS</h1>
        </div>
        @include('website.notifications_message')
        <div class="tabs">
            <!-- <ul class="tab-links">
                <li class="active"><a href="#tab1">Now</a></li>
                <li><a href="#tab2">future</a></li>
                <li><a href="#tab3">favourites</a></li>
            </ul> -->

            <div class="">

              <div class="col-md-8 col-md-offset-2">
                 <div class="form-reflex form-info-settings block-user">
                    <div class="row">
                       <div class="col-md-12">
                        <div class="search-box add_friend_search">
                            <div class="form-group">
                                <input type="text" class="form-control" id="myInput" onkeyup="myFunction(this)"  placeholder="Search">
                                <img src="{{url('public/website/images/search.png')}}">
                            </div>
                        </div>  
                    </div> 
                    <table id="myTable">

                      <tr class="header">
                       <th style="width:60%;"></th>
                       <th style="width:40%;"></th>
                   </tr>
                   @if($blocked_users && !empty($blocked_users) && count($blocked_users) > 0)
                   @foreach($blocked_users as $block_user)
                   <tr>
                    <td>
                        <div class="col-md-12" id="myDIV">

                            <div class="form-group">
                                
                                <div class="user-wrap">
                                    @if($block_user->user_image)
                                    <img src="{{$block_user->user_image}}">
                                    @else 

                                    <img src="{{url('public/website/images/licon-yellow.png')}}">
                                    @endif
                                    <label class="lowercase">@if($block_user->user_name){{$block_user->user_name}}@else Jhon @endif</label>
                                    <a href="{{url('website/unblock-user',$block_user->friend_id)}}" class="btn_primary">unblock</a>
                                </div>
                                

                            </div>
                        </div>
                    </td>

                </tr>
                @endforeach()

                @else 
                  <h1 style="font-size:30px; font-weight: 500; color: #fff;text-align:center;}">No Friend in blocklist</h1>
                @endif

            </table>
            <div id="not_found" style="display: none;color:white;text-align:center;font-weight: 500;">No record found</div>
        </div>
    </div>
</div>


</div>
</div>
</div>
</section>


@endsection()
@section('js')
<script>
    function myFunction(event) {
  // Declare variables 

  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");

$("#not_found").hide();
  // Loop through all table rows, and hide those who don't match the search query
  let all_tr = tr.length;
  let not_display = 1;
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
      if (td) {
        txtValue = td.textContent || td.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
          tr[i].style.display = "";

      } else {
          tr[i].style.display = "none";
          not_display++;
      }
  } 
}

// console.log(all_tr,not_display)
if(all_tr == not_display){
  $("#not_found").show();

}else {
  $("#not_found").hide();
}
not_found = 1;

if(event.value.length == 0){
    $("#not_found").hide();
}
} 
</script>
@endsection()