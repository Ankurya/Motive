@extends('website.common')
@section('title', 'Motive')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
<style type="text/css">
.new-format-again-website-view figure.new img {
    width: 100%;
    height: 320px;
    object-fit: contain;

}
figure.new {
    border: 1px solid #fff;
    border-radius: 12px;
}
.new-right figure{
        width: 5%;

        }
.new-right h3{
        width: calc(100% - 43px);
        vertical-align: middle;

}
.new-performance p{
        padding: 0px 25px 0px;

}
img.two {
    display: none;
}
span.like-icon.highlight img.two{
  display: inline-block;
}
span.like-icon.highlight img.one{
  display: none;
}
span.like-icons {
    float: right;
}
span.like-icons {
    display: inline-flex;
}
input#CheckAll {
    width: 31px;
    height: 36px;
    position: absolute;
    right: 0;
    z-index: -1;
    opacity: 0;
}
span.like-icons h3 {
    vertical-align: top;
    margin-top: 4px;
}
span.like-icon img {
    margin-left: 20px;
    width: 36px;
}
.new-format-again-website-view .new img {
    border-radius: 33px;
}
   .form-group img {
    position: unset;
}
.about-us .owl-carousel .owl-item img {
    display: block;
    width: 50px;
    margin: 0 auto;
    min-height: 51px;
    text-align: center;
}
#interest .form-group label {
    margin-top: 26px;
    }
.new-format-again-website-view figure.img img {
    height: 301px;
    width: 100%;
}

.new-event-name {
    display: inline-block;
}
.dropdown-menu {
    min-width: 253px;
}

.new-performance {
    display: inline-block;
    width: 100%;
}

</style>
        <div class="container ">
            <div class="tabs">
                <div class="col-md-8 col-md-offset-2">
                    <div id="tab1" class="tab active">


                        <div class="new-format-again-website-view fi ">
                            @if($get_event[0]->main == 1)
                                @if($get_event[0]->event_media_type == 1)
                                <figure class="new">
                                    @if($get_event[0]->event_image_url)
                                    <img src="{{$get_event[0]->event_image_url}}">
                                    @else 
                                    <img src="{{url('public/website/images/img-brackets.png')}}">
                                    @endif
                                </figure>
                                @else 
                                 <video class="create" controls="" width="100%" height="200px;" style="margin-top: 12px;">
                                    <source src="{{$get_event[0]->event_video_url}}" type="video/mp4">
                                    <!-- <source src="{{url('public/website/video/movie.ogg')}}" type="video/ogg"> -->
                                 </video>
                                 @endif
                            @else 
                                @if($get_event[0]->event_media_type == 1)
                                <figure class="new">
                                    @if($get_event[0]->event_image_url2)
                                    <img src="{{$get_event[0]->event_image_url2}}">
                                    @else 
                                    <img src="{{url('public/website/images/img-brackets.png')}}">
                                    @endif
                                </figure>
                                @else 
                                 <video class="create" controls="" width="100%" height="200px;" style="margin-top: 12px;">
                                    <source src="{{$get_event[0]->event_video_url2}}" type="video/mp4">
                                 </video>
                                 @endif
                            @endif

                            <div class="new-event-name">
                                <h2><?php echo date('j M, Y',strtotime($get_event[0]->event_date));?></h2>
                                <p>{{$get_event[0]->event_name}}</p>
                                <p>{{date( 'g:i A', strtotime($get_event[0]->event_time) )}}</p>
                                <p>{{date( 'g:i A', strtotime($get_event[0]->end_time) )}}</p>
                            </div>
                        </div>

                        <div class="new-location-box">
                            <a href="https://maps.google.com/maps?q={{$get_event[0]->event_lat}},{{$get_event[0]->event_long}}&hl=es;z=14&amp;output=embed" style="color:#0000FF;text-align:left" target="_blank;">
                            <div class="new-right">
                                <figure><img src="{{url('public/website/images/location-white.png')}}"></figure>
                                <h3>{{$get_event[0]->event_location}}</h3>
                                </a>
                            </div>
                            <!-- <div class="new-right">
                                <figure><img src="{{url('public/website/images/eye.png')}}"></figure>
                                <h3>265 Views</h3>
                            </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>





@endsection()




