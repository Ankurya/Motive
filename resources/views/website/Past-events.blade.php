@extends('website.common')
@section('title', 'Motive')

@section('content')
<style type="text/css">
    <style type="text/css">
.img-section.jack .social-icon a.button-new{
        cursor: default;
    }
    .social-icon a.new-result{
                cursor: default;


    }

    figure.new {
    border: 1px solid #fff;
    border-radius: 12px;
}
    video.create{
    /* margin-top: 0px; */
    height: 285px;
    width: 100%;
    background: #fff;
    border-radius: 15px 14px 11px 8px;
}

</style>
</style>
<div class="container">
    <div class="heading-top">
        <h1>PAST EVENTS</h1>
    </div>
    <div class="tabs events">
        <div class="tab-content events">
            <div id="now" class="tab active">
                <div class="img-section jack events">
                    <div class="first-element-section">
                        @foreach($data as $key => $interest)
                        @if($interest && !empty($interest) && count($interest) > 0)
                        <h2 class="new-heading-inner">{{$key}}</h2>
                        <div class="row">
                            @foreach($interest as $event)
                            <div class="col-sm-4">
                                <a href="{{url('website/past-event',$event->id)}}">
                                    <div class="img-factor">
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
                                             <video class="create" controls="" width=""  style="margin-top: 0px; height:285px;">
                                                <source src="{{$event->event_video_url}}" type="video/mp4">
                                             </video>
                                        @endif


                                    </div>
                                      <div class="new-description">
                                                <h3>{{$event->event_name}}</h3>
                                                <div class="new-formula">
                                                    <h2>{{$event->event_time}}</h2>
                                                </div>
                                            </div>
                               <!--  <div class="social-icon">
                                            <a href="javascript:void(0);" class="new-result"><img src="{{url('public/website/images/heart.png')}}"></a>
                                            <a href="javascript:void(0);" class="new-result"><img src="{{url('public/website/images/share.png')}}"></a>
                                            <a href="javascript:void(0);" class="button-new"> @if(!empty($event->ticket_amount))FROM &#163;{{$event->ticket_amount}} @else @endif</a>


                                        </div> -->
                                </a>
                            </div>
                            @endforeach         
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>

@endsection
