@extends('website.common')
@section('title', 'Motive')

@section('content')
<style type="text/css">
table#myTable tr td {
  width: 100%;
}
.user-wrap {
  margin-bottom: 21px;
}
.user-wrap img {
  border-radius: 100%;
  width: 39px;
}

.modal-content {
  max-width: 363px;
  margin: 133px auto;
  height: 173px;
  width: 100%;
  background: #0f0f0f;


}
.tab-content {
  padding: 43px 0 0 0;
  width: 100%;
}
a.btn_primary{
  min-width: 133px;
  padding: 7px 22px;
  font-size: 17px;
  margin-top: 13px;
}
.modal-body,.modal-header{
  background: transparent;
}
.user-wrap.pop img.new-reds{
  border-radius: 100%;
  width: 40px;
}
.tab-content {
  padding: 0;
}

.heading-top {
  padding: 77px 0 0;
}

.alert-success {
  color: #3c763d;
  background-color: #dff0d8;
  width: 493px;
  margin-left: 320px;
  border-color: #d6e9c6;
}
.tabs {
    margin-top: 31px;
}
.gry.margin-left-20 {
    margin-left: 20px;
    text-align: right;
    margin-bottom: 19px;
}
.gry.margin-left-20 p {
    font-size: 20px;
    color: #fff;
        margin-top: 5px;

}
</style>
<section class="bg-color main-section">
  <div class="container">
    <div class="heading-top">
      <h1>Guest List</h1>
    </div>
    <div class="tabs">
      <!-- <ul class="tab-links">
          <li><a href="{{url('website/bought-tickets',$event_id)}}">Tickets</a></li>
          <li class="active"><a href="javascript:void(0)">Guest List</a></li>
      </ul> -->
      <div class="tab-content">
        <div id="tab1" class="tab active">
          <div class="col-md-6 col-md-offset-3">
            <div class="form-reflex form-info-settings">
               <div class="gry margin-left-20">
                    <a href="{{url('website/add-guest-name',$event_id)}}" >
                      <p style="display: inline-block;">Add Guest List</p>
                      <img src="{{url('public/website/images/first.png')}}"></a>
                </div>
              <div class="row">
                <div class="col-md-12">
                 
                  <div class="search-box">
                    <div class="form-group">
                      <input type="text" class="form-control" id="myInput" onkeyup="myFunction(this)"  placeholder="Search" style="width: 439px;">
                      <img src="{{url('public/website/images/search.png')}}">
                    </div>
                  </div>
                </div>
                <table id="myTable">
                  <tr class="header">
                   <th style="width:60%;"></th>
                   <th style="width:40%;"></th>
                 </tr>
                 @foreach($guest_list_name as $guest)
                 <tr>
                  <td>
                    <div class="col-md-12" id="myDIV">
                      <a href="{{url('website/guest-list',$guest->id)}}">
                        <div class="form-group">  
                          <div class="user-wrap add" id="" data-toggle="modal" data-target="#myModal">
                            <label class="lowercase">{{$guest->guest_list_name}}</label>

                          </div>
                        </div>
                      </a>
                    </div>
                  </td>
                </tr>
                @endforeach
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

<script type="text/javascript">

  $('.add').bind('click', function() {
    var i = $(this).attr('id');
    $('#friend_id').val(i);

  });
</script>
@endsection()

