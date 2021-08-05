@extends('website.common')
@section('title', 'Motive')

@section('content')
<Style>
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
.new-format-again-website-view .new img {
    border-radius: 33px;
}

span.like-icon img {
    margin-left: 20px;
    width: 38px;
}

.new-format-again-website-view figure.img img {
    border-radius: 19px 19px 19px 19px;
    object-fit: contain;
    border : 1px solid #fff;
}

.new-right {
    border: 1px solid #fff;
    margin-bottom: 24px;
    background: #2a2a2a;
    padding: 12px 18px;
    display: inline-block;
        width: 100%;

}

.new-right figure {
    display: inline-block;
    vertical-align: middle;
    width: 29px;
}

.new-right h3 {
    display: inline-block;
    font-size: 18px;
    margin-left: 0px;
    color: #fff;
    font-family: 'CaviarDreams-Bold';
    width: calc(100% - 46px);
    float: right;
    line-height: 26px;
}



</Style>
<div class="container">
    <!-- <div class="heading-top">
    </div> -->
    <div class="tabs">
        <div class="col-md-8 col-md-offset-2">
            <div id="tab1" class="tab active">
                <div class="new-format-again-website-view">
                    @if($invitations->event->main == 1)
                        @if($invitations->event->event_media_type == 1)
                            <figure class="img">
                                @if($invitations->event->event_image_url)
                                    <img src="{{$invitations->event->event_image_url}}">
                                @else 
                                    <img src="{{url('public/website/images/img2.png')}}">
                                @endif
                            </figure>
                        @else
                            <video class="create" controls="" width="100%" height="200px;" style="margin-top: 12px;">
                                <source src="{{$invitations->event->event_video_url}}" type="video/mp4">
                            </video>
                        @endif
                    @else
                        @if($invitations->event->event_media_type == 1)
                        <figure class="img">
                            @if($invitations->event->event_image_url2)
                                <img src="{{$invitations->event->event_image_url2}}">
                            @else 
                                <img src="{{url('public/website/images/img2.png')}}">
                            @endif
                        </figure>
                        @else
                        <video class="create" controls="" width="100%" height="200px;" style="margin-top: 12px;">
                            <source src="{{$invitations->event->event_video_url2}}" type="video/mp4">
                        </video>
                        @endif
                    @endif
                    <div class="new-life new-event-name first">
                        <h3><?php echo date('j M, Y',strtotime($invitations->event->event_date));?></h3>
                        <figure class="new-life">
                            <span class="like-icon">
                                <img id="show_red_hidden" src="{{url('public/website/images/heart-icon.png')}}" class="red" onclick="return ad_favorite();" hidden>
                                <img id="show_green_hidden" class="green-heart" onclick="return ad_favorite();" src="{{url('public/website/images/heart.png')}}" hidden>
                                @if($FavouriteEvent)
                                    <img id="show_red" src="{{url('public/website/images/heart-icon.png')}}" onclick="return ad_favorite();" class="fav"> 
                                @else
                                    <img id="show_green" src="{{url('public/website/images/heart.png')}}" onclick="return ad_favorite();" class="fav">
                                @endif
                            </span>
                        </figure>



                    </div>
                    <div class="new-event-name">
                        <p>{{$invitations->event->event_name}}</p>
                    </div>
                    <div class="date-div">
                        <figure><img src="{{url('public/website/images/clock-new.png')}}"></figure>
                        <span>{{$invitations->event->event_time}}</span>
                       <!--  <h4>From &#163;2.50</h4> -->
                    </div>

                </div>

                <div class="new-location-box">
                    <div class="new-location-box">
                        <a href="https://maps.google.com/maps?q={{$invitations->event->event_lat}},{{$invitations->event->event_long}}&hl=es;z=14&amp;output=embed" style="color:#0000FF;text-align:left" target="_blank;">
                        <div class="new-right">
                            <figure><img src="{{url('public/website/images/location-white.png')}}"></figure>
                            <h3>{{$invitations->event->event_location}}</h3>
                            </a>
                    </div>
                </div>
                <div class="about-us">
                    <h3>About</h3>
                    <div class="col-sm-12">
                        @if(count($public_interest_id) > 0 || count($music_interest_id) > 0)
                        <div class="loop owl-carousel owl-theme" id="interest">
                            @foreach($public_interest_id as $public_interest)
                                @php 
                                    $image = $public_interest->image ? url('/public') .'/'. $public_interest->image : url('/') .'public/website/images/user.png';
                                @endphp
                            <div class="item">
                                <div class="form-group">
                                   <img src="{{$image}}"><label class="check Animal">{{$public_interest->name}}</label>
                                </div>
                            </div>
                            @endforeach
                            @foreach($music_interest_id as $music_interest)
                                @php 
                                    $image = $music_interest->image ? url('/public') .'/'. $music_interest->image : url('/') .'public/website/images/user.png';
                                @endphp
                            <div class="item">
                                <div class="form-group">
                                   <img src="{{$image}}"><label class="check Animal">{{$music_interest->name}}</label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else 
                        <h1 style="color:#fff; text-align: center">No Interest Available</h1>
                        @endif
                        
                        
                    </div>
                    <div class="new-performance">
                        <p style="word-break:break-all;">{{$invitations->event->description}}</p>
                        <div class="new-formula-home first">

                            <div class="col-sm-6">
                                <a href="{{url('website/accept-invitation',$invitations->id)}}" class="new-homes new">ACCEPT</a> </div>
                                <div class="col-sm-6">
                                    <a href="{{url('website/reject-invitation',$invitations->id)}}" class="new-homes new color">REJECT</a>
                                </div>
                            </div>


                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>

@endsection()
@section('js')
<script type="text/javascript">
  $('#show_red_hidden').hide();
  $('#show_green_hidden').hide();
  function ad_favorite(){
    // alert('dd');
    var sub_event_id = <?php echo $invitations->event->id ?>;
    var event_id = <?php echo $invitations->event->event_id ?>;
    // var user_id = $('#user_id').val();
    var data = {
      '_token': "{{csrf_token()}}",
      'sub_event_id': sub_event_id,
      'event_id': event_id,
      
      };
     // console.log(data);
      $.ajax({
        url: "{{url('website/favorite')}}",
        type: 'POST',
        data: data,
        success:function(res){
          // console.log(res);
         if(res == 'success'){
          $('#show_green').hide();
          $('#show_red_hidden').show();
          $('#show_red').hide();
          $('#show_green_hidden').hide();
         }else{
          $('#show_green').hide();
          $('#show_red_hidden').hide();
          $('#show_red').hide();
          $('#show_green_hidden').show();
         }
          
        }
      });
  }
</script>
 <script>
    jQuery(document).ready(function($) {
        $('.loop').owlCarousel({
            center: false,
            items: 3,
            loop: false,
            margin: 20,
            autoplay: true,
            autoPlaySpeed: 1000,
            slideSpeed: 3000,
            smartSpeed: 2000,
            fluidSpeed: 500,

            navText: ["<img src='{{url("public/website/images/original1.png")}}' alt=''/>", "<img src='{{url("public/website/images/original2.png")}}' alt=''/>"],

            responsiveClass: true,
            responsive: {
                0: {
                    items: 1,
                    nav: true
                },
                600: {
                    items: 3,
                    nav: false
                },

            }
        });
        $('.nonloop').owlCarousel({
            center: false,
            items: 2,
            loop: false,
            margin: 10,
            responsive: {
                600: {
                    items: 4
                }
            }
        });
    });
    </script>
@endsection()