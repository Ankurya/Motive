@extends('website.common')
@section('title', 'Motive')

@section('content')
<style type="text/css">
.new-format-again-website-view figure.new img {
    width: 100%;
    height: 320px;
    border-radius: 15px 15px 15px 15px;
    object-fit: contain;
}
.new-format-again-website-view .new {
    border: 1px solid #fff;
    border-radius: 10px;
}
.new-right {
    border-radius: 38px;
    background: transparent;
}
.new-right.first {
    border: 0!important;
}
.new-right.first .new-homes {
    border-radius: 40px;
    padding: 8px 46px;

}
.new-right.newsr {
    display: inline-block;
    width: 25%;
}
.new-right.first {
    text-align: center;
}

.alert.alert-success.alert-dismissible.text-center.alertz {
    width: 733px;
    margin-left: 203px;
}
.new-right h3.good{
        color: #000;

}


</style>
<div class="container">
   <div class="heading-top">
        <h1>{{$eachEvent->event_name}}</h1>
    </div>
    @include('website.notifications_message')
    <div class="tabs">
        <div class="col-md-8 col-md-offset-2">
            <div id="tab1" class="tab active">
                <div class="new-format-again-website-view">
                    @if($eachEvent->main == 1)
                                @if($eachEvent->event_media_type == 1)
                                <figure class="new">
                                    @if($eachEvent->event_image_url)
                                    <img src="{{$eachEvent->event_image_url}}">
                                    @else 
                                    <img src="{{url('public/website/images/img-brackets.png')}}">
                                    @endif
                                </figure>
                                @else 
                                 <video class="create" controls="" width="100%" height="200px;" style="margin-top: 12px;">
                                    <source src="{{$eachEvent->event_video_url}}" type="video/mp4">
                                 </video>
                                 @endif
                            @else 
                                @if($eachEvent->event_media_type == 1)
                                <figure class="new">
                                    @if($eachEvent->event_image_url2)
                                    <img src="{{$eachEvent->event_image_url2}}">
                                    @else 
                                    <img src="{{url('public/website/images/img-brackets.png')}}">
                                    @endif
                                </figure>
                                @else 
                                 <video class="create" controls="" width="100%" height="200px;" style="margin-top: 12px;">
                                    <source src="{{$eachEvent->event_video_url2}}" type="video/mp4">
                                 </video>
                                 @endif
                            @endif
                    <div class="new-event-name">
                        <!-- <p>{{$eachEvent->event_name}}</p> -->
                    </div>                           
                </div>
                <div class="new-location-box">
                    <div class="new-right first">
                        <a href="{{url('website/dashboard',$eachEvent->id)}}" class="new-homes">
                            <h3 class="good">DASHBOARD</h3>
                        </a>
                    </div>
                    <div class="text-center">
                        <a href="{{url('website/bought-tickets',$eachEvent->id)}}">
                            <div class="new-right newsr">
                                <h3>CHECK IN</h3>
                            </div>
                        </a>
                        <a href="{{url('website/check-in-guest',$eachEvent->id)}}">
                            <div class="new-right newsr">
                                <h3>GUEST LIST</h3>
                            </div>
                        </a>
                    </div>
                    @if($ticket->ticket_status != 0)
                    <div class="text-center" style=" margin: 42px 0; display: block;">
                       <a href=""  data-toggle="modal" data-target="#myModal" class="new-homes">Close Sale</a>
                    </div>
                    @else 
                    @endif
                    
               </div>
            </div>
        </div>
    </div>
</div>

<div id="myModal" class="modal fade pipes" role="dialog">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h2 style="text-transform: uppercase;">you will be unable to re-open once closed! Are you sure you would like to proceed ?</h2>
                </div>
                <div class="modal-body">
                 <div class="">
                    <div class="col-sm-6 text-center padding-left-0">
                      <a href="{{url('website/close-sales',$eachEvent->id)}}" class="btn_primary" id="modal-btn-si">YES</a>
                   </div>
                   <div class="col-sm-6 text-center">
                       <a href="javascript:;" class="btn_primary" data-dismiss="modal">NO</a>
                   </div>
               </div>
           </div>

       </div>

   </div>
</div>
@endsection
