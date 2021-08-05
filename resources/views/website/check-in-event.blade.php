@extends('website.common')
@section('title', 'Motive')

@section('content')
<style type="text/css">

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
.new-line {
    display: inline-block;
}

.img-factor figure img {
    border-radius: 12px 12px 12px 12px;
    height: 286px;
    width: 100%;
        border: 1px solid #fff;

}
video.create {
    height: 285px;
    width: 100%;
    background: #000;
    border-radius: 15px 14px 11px 8px;
    border: 1px solid #fff;
}
</style>
<div class="container">
    <div class="heading-top">
        <h1>CHECK IN</h1>
    </div>
    <div class="tabs events">
        <div class="img-section jack events">
            <div class="row">
            @if($get_event && !empty($get_event) && count($get_event) > 0)
            @foreach($get_event as $event)
                <div class="col-sm-4">
                    <a href="{{url('website/check-in-detail',$event->id)}}">
                        <div class="img-factor">
                            @if($event->main == 1)
                                @if($event->event_media_type == 1)
                                    @if($event->event_image_url)
                                    <figure>
                                        <img src="{{$event->event_image_url}}">
                                    </figure>
                                    @else
                                    <figure>
                                        <img src="{{url('public/website/images/img-brackets.png')}}">
                                    </figure> 
                                    @endif
                                @else 
                                     <video class="create" controls="" width=""  style="margin-top: 0px; height:292px;">
                                        <source src="{{$event->event_video_url}}" type="video/mp4">
                                     </video>
                                @endif
                            @else 
                                @if($event->event_media_type == 1)
                                    @if($event->event_image_url2)
                                    <figure>
                                        <img src="{{$event->event_image_url2}}">
                                    </figure>
                                    @else
                                    <figure>
                                        <img src="{{url('public/website/images/img-brackets.png')}}">
                                    </figure> 
                                    @endif
                                @else 
                                     <video class="create" controls="" width=""  style="margin-top: 0px; height:292px;">
                                        <source src="{{$event->event_video_url2}}" type="video/mp4">
                                     </video>
                                @endif
                            @endif
                        </div>
                         <div class="new-description">
                                            <h3>{{$event->event_name}}</h3>
                                                <div class="new-formula">
                                                    <h2>{{date( 'g:i A', strtotime($event->event_time ) )}}</h2>
                                                </div>
                                </div>
                                        <div class="social-icon">
                                            <a href="javascript:void(0);" class="button-new">TICKET SOLD: {{$event->bought_tickets}}</a>
                                        </div>
                        <!-- <div class="social-icon">
                            <div class="new-line">
                            <div class="newd-icon">
                            <a href="javascript:void(0);" class="new-result">{{$event->event_name}}</a>
                             </div>
                             <div class="new-icon">
                            <a href="javascript:void(0);" class="new-result"><?php echo date("g:i a",strtotime($event->event_time));?></a>

                        </div>
                    </div>
                            <a href="javascript:void(0);" class="button-new">TICKET SOLD: {{$event->bought_tickets}}</a>
                        </div> -->
                    </a>
                </div>
                
            @endforeach
            </div>
            @else
                <h1 style="font-size:30px; font-weight: 500; color: #fff;text-align:center;margin-bottom: 20px;}">No Event Available</h1>
            @endif

        </div>
    </div>
    @endsection
