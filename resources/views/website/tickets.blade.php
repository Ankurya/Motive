@extends('website.common')
@section('title', 'Motive')

@section('content')
<style type="text/css">
.tab-content.tickets {
    width: 100%;
}
.heading-top {
    padding: 77px 0 0 0;
}
.img-section .social-icon {
    margin: 29px 18px 32px 8px;
    overflow: hidden;
    height: 40px;
}
video.create {
    margin-top: 0;
    background: #000;
    border-radius: 14px;
}

</style>
<div class="container">
    <div class="heading-top">
        <h1>tickets</h1>
    </div>
    <div class="tabs">
        <div class="tab-content tickets">
            <div id="tab1" class="tab active">
                @if(!empty($get_events) && $get_events && count($get_events) > 0)
                    <div class="img-section">
                        <div class="row">
                            @foreach($get_events as $events)
                                <div class="col-sm-4">
                                    <div class="img-factor">
                                        <a href="{{url('website/ticket-view',$events->id)}}">
                                            @if($events->main == 1)
                                                    @if($events->event_media_type == 1)
                                                    <figure class="new">
                                                        @if($events->event_image_url)
                                                        <img src="{{$events->event_image_url}}">
                                                        @else 
                                                        <img src="{{url('public/website/images/img-brackets.png')}}">
                                                        @endif
                                                    </figure>
                                                    @else 
                                                     <video class="create" controls="" width="100%" height="286px;" style="margin-top: 0px;">
                                                        <source src="{{$events->event_video_url}}" type="video/mp4">
                                                        <!-- <source src="{{url('public/website/video/movie.ogg')}}" type="video/ogg"> -->
                                                     </video>
                                                     @endif
                                                @else 
                                                    @if($events->event_media_type == 1)
                                                    <figure class="new">
                                                        @if($events->event_image_url2)
                                                        <img src="{{$events->event_image_url2}}">
                                                        @else 
                                                        <img src="{{url('public/website/images/img-brackets.png')}}">
                                                        @endif
                                                    </figure>
                                                    @else 
                                                     <video class="create" controls="" width="100%" height="286px;" style="margin-top: 0px;">
                                                        <source src="{{$events->event_video_url2}}" type="video/mp4">
                                                     </video>
                                                     @endif
                                                @endif
                                            <div class="new-description">
                                                <h3>{{$events->event_name}}</h3>
                                                <p><?php echo date("g:i a", strtotime($events->event_time));?></p>
                                                <div class="new-formula">
                                                    <h2>{{$events->event_date}}</h2>
                                                </div>
                                                <div class="new-formula">
                                                    <h2>{{$events->ticket_quantity}} Tickets</h2>
                                                </div>
                                            </div>
                                        </a>

                                    </div>
                                    </div>
                                    <div class="col-md-12">
                                    <div class="social-icon">
                                        <a href="javascript:void(0);" class="new-result"><img src="{{url('public/website/images/direction-arrow.png')}}"></a>
                                        <a href="javascript:void(0);" class="new-result">{{$events->event_location}}</a>


                                    </div>
                                </div>
                                @endforeach()
                        </div>
                    </div>
                @else
                    <h1 style="font-size:30px; font-weight: 500; color: #fff;text-align:center;margin-bottom: 20px;margin-right:50px">No Ticket Available</h1>
                @endif
            </div>
        </div>

    </div>
</div>
</div>
</div>
</div>
@endsection()