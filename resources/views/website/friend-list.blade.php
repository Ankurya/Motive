@extends('website.common')
@section('title', 'Motive')

@section('content')
<style type="text/css">
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
a.btn_primary,button#modal-btn-si {
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
button#modal-btn-si {
    color: #f5cf5f;
    border-color: #f5cf5f;
    background-color: #2a2a2a;
    width: auto;
    border: 0;
    text-transform: uppercase;
    font-weight: 600;
}
.modal-header {
    text-align: center;
}

.friend-right.img-new img {
    border-radius: 100%;
}
.tab-content {
    width: 100%;
}
.heading-top {
    padding: 77px 0 0 0;
}
.alert-success {
    color: #3c763d;
    margin-left: 316px;
    background-color: #dff0d8;
    border-color: #d6e9c6;
    width: 504px;
}
</style>

<section class="bg-color main-section"> 
    <div class="container">
        <div class="heading-top">
            <h1>friend list</h1>
        </div>
        @include('website.notifications_message')
        <div class="tabs">
            <div class="tab-content">
                <div id="tab1" class="tab active">
                  <div class="col-md-6 col-md-offset-3">
                     <div class="form-reflex form-info-settings">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="add-friend">
                                    <a href="{{url('website/add-friends')}}"><label>Add Friend</label><img src="{{url('public/website/images/user3.png')}}"></a> 
                                    <a class="plusicon" href="{{url('website/add-friends')}}"> <input type="text-center" style="cursor: pointer;" name=""><img src="{{url('public/website/images/plus-icon.png')}}"> </a>
                                </div>
                            </div>
                        </div>
                        @if($friendList && !empty($friendList) && count($friendList) > 0)
                        <div class="row">
                            @foreach($friendList as $friend)
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="user-wrap pop add" friend_id="{{$friend->senderDetail->id}}" data-toggle="modal" data-target="#myModal">
                                      <input type="hidden" id="u_id" value="{{$friend->senderDetail->id}}">
                                        @if($friend->senderDetail->image_url)
                                        <img src="{{$friend->senderDetail->image_url}}" class="new-reds">
                                        @else 
                                        <img src="{{url('public/website/images/licon-yellow.png')}}"  class="new-reds">
                                        @endif
                                        <label class="lowercase">@if($friend->senderDetail->name){{$friend->senderDetail->name}} @else N/A @endif</label>
                                        <img class="dropdown-icon" src="{{url('public/website/images/dropdown.png')}}">
                                    </div>
                                </div>
                            </div>
                            @endforeach()
                            <div id="myModal" class="modal fade" role="dialog">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <!-- <a id="add" href="{{url('website/view-friend')}}" class="btn_primary width-220">View Profile</a> -->
                                    <!-- <input id = "friend_id" name = "friend_id" type="hidden"> -->
                                    <button type="button" class="btn_secondary width-220 btn_primary add view" id="modal-btn-si">View Profile</button>
                                </div>
                                <div class="modal-body">
                                <form id="add" action="{{url('website/block-user')}}" method="post">
                                    {{ csrf_field() }}
                                    <input id ="friend_id" name ="friend_id" type="hidden">
                                 <div class="">
                                    <div class="col-sm-6 text-center padding-left-0">
                                      <button type="button" onclick="$('#add').submit()" class="btn_secondary width-220" id="modal-btn-si">Block</button>
                                   </div>
                                   <div class="col-sm-6 text-center">
                                       <a href="javascript:;" class="btn_secondary width-220 btn_primary" data-dismiss="modal">Cancel</a>
                                   </div>
                               </div>
                             </form>
                           </div>

                       </div>

                   </div>
               </div>

               




           </div>
           @else 
           <h1 style="font-size:30px; font-weight: 500; color: #fff;text-align:center; position: absolute;top: 69%;left: 50%;transform: translate(-50% ,-50%);}">No Friend in list</h1>
           <!-- <h1>No Friend in list</h1> -->
           @endif
           
       </div>
   </div>
</div>


</div>
</div>
</div>
</section>
</div>
</div>


@endsection()
@section('js')
<!-- <script>
    $(document).ready(function(){
      $(".").click(function(){
        var i = $(this).attr('id');
        // alert(i);
        window.location.href = url; 

      });
  });
</script> -->

<script type="text/javascript">
$('.add').bind('click', function() {
  var i = $(this).attr('friend_id');
  // alert(i);
  $('#friend_id').val(i);
  $(".view").click(function(){
        var id = i;
        window.location = '{{url("website/view-friend")}}/'+id;

      });

});
</script>
@endsection()
