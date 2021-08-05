@extends('website.common')
@section('title', 'Motive')

@section('content')
<style type="text/css">
.new-format-again-website-view figure.new img {
    width: 100%;
    height: 320px;
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

</style>
        <div class="container">
            <div class="tabs">
                <div class="col-md-8 col-md-offset-2">
                    <div id="tab1" class="tab active">


                        <div class="new-format-again-website-view ">
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
                                    <!-- <source src="{{url('public/website/video/movie.ogg')}}" type="video/ogg"> -->
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
                                    <!-- <source src="{{url('public/website/video/movie.ogg')}}" type="video/ogg"> -->
                                 </video>
                                 @endif
                            @endif
                            <div class="new-life">
                                <!-- <figure><img src="{{url('public/website/images/user-icon-1.png')}}"></figure> -->
                                <figure><img src="{{url('public/website/images/connect.png')}}"></figure>
                                <span class="like-icon">
                                <img id="show_red_hidden" src="{{url('public/website/images/heart.png')}}" class="red" onclick="return ad_favorite();" hidden>
                                <img id="show_green_hidden" class="green-heart" onclick="return ad_favorite();" src="{{url('public/website/images/heart-outline.png')}}" hidden>
                                @if($FavouriteEvent)
                                    <img id="show_red" src="{{url('public/website/images/heart.png')}}" onclick="return ad_favorite();" class="fav"> 
                                @else
                                    <img id="show_green" src="{{url('public/website/images/heart-outline.png')}}" onclick="return ad_favorite();" class="fav">
                                @endif
                                </span>


                            </div>
                            <div class="new-event-name">
                                <h2>{{$eachEvent->event_date2}}</h2>
                                <p>{{$eachEvent->event_name}}</p>
                            </div>
                            <div class="date-div">
                                <figure><img src="{{url('public/website/images/clock-new.png')}}"></figure>
                                <span>{{$eachEvent->event_time}}</span>
                                <!-- <h4>From &#163;2.50</h4> -->
                            </div>

                        </div>

                        <div class="new-location-box">
                            <a href="https://maps.google.com/maps?q={{$eachEvent->event_lat}},{{$eachEvent->event_long}}&hl=es;z=14&amp;output=embed" style="color:#0000FF;text-align:left" target="_blank;">
                            <div class="new-right">
                                <figure><img src="{{url('public/website/images/location-white.png')}}"></figure>
                                <h3>{{$eachEvent->event_location}}</h3>
                                </a>
                            </div>
                            <!-- <div class="new-right">
                                <figure><img src="{{url('public/website/images/eye.png')}}"></figure>
                                <h3>265 Views</h3>
                            </div> -->
                            
                             <a  href="{{url('website/posts',$eachEvent->event_id)}}">
                            <div class="new-right">
                                <figure><img src="{{url('public/website/images/comment-first.png')}}"></figure>
                                <h3>View Posts</h3>
                            </div>
                            </a>
                            
                            




                            </div>
                          
                          
                        </div>
                        <div class="about-us">
                            <h3>About</h3>
                              <div class="col-sm-12">
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
                                        
                                        
                                    </div>
                                </div>
                            <div class="new-performance">
                              <p style="width: 50%; word-break:break-all; text-align: center;margin: 0 auto;">{{$eachEvent->description}}</p>
                            <?php $user = auth()->guard('web')->user();?>
                            @if($user->role == 3 || $eachEvent->user_id == $user->id || $tickets == 0)

                            @else 
                                <div class="new-formula-home">
                                    <a href="{{url('website/book-now',$eachEvent->event_id)}}" class="new-homes">BOOK NOW</a>
                                </div> 
                            @endif
                            @if($user->role == 3 && $eachEvent->user_id == $user->id)
                            <div class="new-formula-home">
                                <a href="{{url('website/edit-event',$eachEvent->event_id)}}" class="new-homes">edit details</a>
                            </div>
                            @else
                            @endif
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
$(document).ready(function(){
    $(".like-icon").click(function(){
        $(this).toggleClass("highlight");
    });
});
</script>

<script type="text/javascript">
  $('#show_red_hidden').hide();
  $('#show_green_hidden').hide();
  function ad_favorite(){
    // alert('dd');
    var sub_event_id = <?php echo $eachEvent->id ?>;
    var event_id = <?php echo $eachEvent->event_id ?>;
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
            items: 4,
            loop: false,

            navText: ["<img src='{{url("public/website/images/original1.png")}}' alt=''/>", "<img src='{{url("public/website/images/original2.png")}}' alt=''/>"],

            responsiveClass: true,
            responsive: {
                0: {
                    items: 1,
                    nav: true
                },
                600: {
                    items: 4,
                    nav: false
                },

            }
        });
        $('.loop2').owlCarousel({
            items: 3,
            loop: false,

        });
    });

</script>
@endsection()
