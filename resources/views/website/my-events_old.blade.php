@extends('website.common')
@section('title', 'Motive')

@section('content')
<style type="text/css">
.img-section.jack .social-icon a.button-new{
        cursor: default;
    }
.social-icon a.new-result{
        cursor: default;
    }
video.create{
    height: 285px;
    width: 100%;
    background: #000;
    border-radius: 15px 14px 11px 8px;
}

.img-section.jack .social-icon a.button-new{
    width: unset;
}
.img-section .col-sm-4 {
    min-height: 423px;
}
</style>
<div class="container">
    <div class="heading-top">
        <h1>MY EVENTS</h1>
    </div>
    @include('website.notifications_message')   
    <div class="tabs events">
        

        <div class="tab-content events">
            <div id="now" class="tab active">
                <div class="new-top-heading">
                  
                </div>
                <div class="img-section jack events">
                    <div class="first-element-section">
                        <?php $i = 0; ?>
                        @foreach($data as $key => $interest)
                        @if($interest && !empty($interest) && count($interest) > 0)
                        <?php $i++; ?>
                        <h2 class="new-heading-inner">{{$key}}</h2>
                        <div class="row">
                            @foreach($interest as $event)
                            <div class="col-sm-4">
                                <a href="{{url('website/website-view',$event->id)}}">
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
                                                 <video class="create" controls="" width=""  style="margin-top: 0px; height:285px;">
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
                                                 <video class="create" controls="" width=""  style="margin-top: 0px; height:285px;">
                                                    <source src="{{$event->event_video_url2}}" type="video/mp4">
                                                 </video>
                                            @endif
                                        @endif



                                    </div>
                                </a>
                                    <div class="social-icon">
                                        <a href="javascript:void(0);" class="new-result">{{$event->event_name}}</a><br>
                                       <!--  <a href="javascript:void(0);" class="new-result">{{$event->event_time}}</a>
                                        <a href="javascript:void(0);" class="button-new">@if(!empty($event->ticket_amount))FROM &#163;{{$event->ticket_amount}} @else Free @endif</a> -->


                                    </div>
                                
                            </div>
                            @endforeach         
                        </div>
                        
                        @endif
                        @endforeach
                        
                    </div>

                    <div class="first-element-section">
                        <?php $j = 0; ?>
                        @foreach($music_interest as $key => $music)
                        @if($music && !empty($music) && count($music) > 0)
                        <?php $j++; ?>
                        <h2 class="new-heading-inner">{{$key}}</h2>
                        <div class="row">
                            @foreach($music as $event)
                            <div class="col-sm-4">
                                <a href="{{url('website/website-view',$event->id)}}">
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
                                                 <video class="create" controls="" width=""  style="margin-top: 0px; height:285px;">
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
                                                 <video class="create" controls="" width=""  style="margin-top: 0px; height:285px;">
                                                    <source src="{{$event->event_video_url2}}" type="video/mp4">
                                                 </video>
                                            @endif
                                        @endif



                                    </div>
                                </a>
                                    <div class="social-icon">
                                        <a href="javascript:void(0);" class="new-result">{{substr($event->event_name,0,10)}}</a><br>
                                        <!-- <a href="javascript:void(0);" class="new-result">{{$event->event_time}}</a>
                                        <a href="javascript:void(0);" class="button-new">@if(!empty($event->ticket_amount))FROM &#163;{{$event->ticket_amount}} @else Free @endif</a> -->


                                    </div>
                                
                            </div>
                            @endforeach         
                        </div>
                        
                        @endif
                        @endforeach

                         @if(!empty($now_category) && $now_category && count($now_category) > 0)
                    <div class="first-element-section">
                        <h2 class="new-heading-inner">Other Category</h2>
                        <div class="row">
                            @foreach($now_category as $event)
                            <div class="col-sm-4">
                                <a href="{{url('website/website-view',$event->id)}}">
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
                                                 <video class="create" controls="" width=""  style="margin-top: 0px; height:285px;">
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
                                                 <video class="create" controls="" width=""  style="margin-top: 0px; height:285px;">
                                                    <source src="{{$event->event_video_url2}}" type="video/mp4">
                                                 </video>
                                            @endif
                                        @endif



                                    </div>
                                </a>
                                    <div class="social-icon">
                                        <a href="javascript:void(0);" class="new-result">{{substr($event->event_name,0,10)}}</a><br>
                                        <a href="javascript:void(0);" class="new-result">{{$event->event_time}}</a>
                                        <a href="javascript:void(0);" class="button-new">@if(!empty($event->ticket_amount))FROM &#163;{{$event->ticket_amount}} @else @endif</a>


                                    </div>
                                
                            </div>
                            @endforeach         
                        </div>
                    </div>
                    @else
                    @endif
                        @if($i == 0 && $j == 0 && count($now_category) == 0)
                            <h1 style="font-size:30px; font-weight: 500; color: #fff;text-align:center;}">No Event Available</h1>
                        @endif
                        
                    </div>

                   


                </div>
            </div>

           
        </div>
    </div>
</div>
@endsection()

@section('js')
<script type="text/javascript">
    $(document).on('click','.search',function(){
        var search =  $(".search_input").val();
        if(search != '') {
            window.location = '{{url("website/search-current")}}/'+search;
        } else {
            // window.location = '{{url("website/current-events")}}';
        }
    });
</script>


@endsection
