@extends('website.common')
@section('title', 'Motive')

@section('content')
<style type="text/css">
    .friend-right.img-new img {
    border-radius: 100%;
}
.tab-content {
    width: 100%;
}
.heading-top {
    padding: 77px 0 0 0;
}

.form-reflex .form-group .user-wrap img {
    position: relative;
    top: 0;
    left: 0;
    height: 37px;
    width: 35px;
    margin-right: 20px;
}

.alert-success {
    color: #3c763d;
    background-color: #dff0d8;
    border-color: #d6e9c6;
    width: 504px;
    margin-left: 316px;
}

.search-box input.form-control {
    background: #fff;
    font-family: 'CaviarDreams-Bold';
    padding-right: 0;
}
</style>
<section class="bg-color main-section"> 
    <div class="container">
        <div class="heading-top">
            <h1>Friend request</h1>
        </div>
        @include('website.notifications_message')
        <div class="tabs">
            <div class="tab-content">
                <div id="tab1" class="tab active">
                  <div class="col-md-6 col-md-offset-3">
                   <div class="form-reflex form-info-settings block-user friend-requst">
                       <div class="row">
                        <div class="col-sm-12">
                            <div class="add-friend">
                                <a href="{{url('website/add-friends')}}">
                                    <label>Add Friend</label><img src="{{url('public/website/images/user3.png')}}"> 
                                    <a class="plusicon" href="{{url('website/add-friends')}}"> <input type="text" name="" style="cursor: pointer;" ><img src="{{url('public/website/images/plus-icon.png')}}" > </a>
                                </a>
                            </div>
                        </div>
                    </div>
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
                        @if(!empty($get_friend) && $get_friend && count($get_friend) > 0)
                        @foreach($get_friend as $friend)
                        <tr>
                            <td style="width: 100%;">                         
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="user-wrap coo">
                                            <a href="{{url('website/view-request',$friend->request_id)}}">
                                                <div class="friend-right img-new">
                                                    @if($friend->sender->image_url)
                                                        <img src="{{$friend->sender->image_url}}">
                                                    @else
                                                        <img src="{{url('public/website/images/licon-yellow.png')}}">
                                                    @endif
                                                    <label class="lowercase">{{$friend->sender->name}}</label>
                                                </div>
                                            </a>
                                                <div class="friend-left">
                                                    <a href="{{url('website/accept-request',$friend->request_id)}}" class="btn_primary">Accept</a><br><br>
                                                    <a href="{{url('website/reject-request',$friend->request_id)}}" class="btn_primary">Reject</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach()
                            @else 
                            <h1 style="font-size:30px; font-weight: 500; color: #fff;text-align:center; margin-right:60px; }">No friend request available</h1>
                            @endif
                        </table>
                        <div id="not_found" style="display: none;color:white;text-align:center;font-weight: 500;">No record found</div>
                    </div>
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
        // console.log(event.value);
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

if(event.value.length ==0){
    $("#not_found").hide();
}
not_found = 1;


} 
</script>
@endsection()