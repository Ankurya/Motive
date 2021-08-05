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
.new-left{
    display: inline-block;
}
.alert.alert-success.alert-dismissible.text-center.alertz {
    max-width: 471px;
    margin: 0 auto;
    width: 100%;
}
.new-format-again-website-view.text-center.view-profile img {
    width: 200px;
    border-radius: 100%;
    height: 200px;
}
</Style>
<div class="container">
    <div class="heading-top">
        <h1>VIEW PROFILE</h1>
    </div>
    <div class="tabs">
        <!-- <div class="new-left">
         <a href="javascript:void(0)"><figure><img src="{{url('public/website/images/bulb.png')}}"></figure></a> 
        </div> -->

     <div class="col-md-8 col-md-offset-2">
        <div id="tab1" class="tab active">


            <div class="new-format-again-website-view text-center view-profile">

                <figure>
                    @if($user->image_url)
                    <img src="{{$user->image_url}}">
                    @else 
                    <img src="{{url('public/website/images/black.png')}}">
                    @endif
                </figure>
                <h3>{{$user->name}}</h3>
                <!-- <P> Next event detail location name and view detail</P> -->

            </div>
            <div class="about-us">
               <h3>INTERESTS</h3>
               <div class="col-sm-12">
                
                @if(!empty($public_interest_id) && $public_interest_id && count($public_interest_id) > 0 || !empty($music_interest_id) && $music_interest_id && count($music_interest_id) > 0)
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
                @else 
                <h1 style="font-size:25px; font-weight: 400; color: #fff;text-align:center; }">No Interest found</h1>
                @endif
                                </div>

               
                <div class="new-performance newser">
                    <h3>About</h3>

                    <p style="word-break:break-all;">{{$user->about_me}}</p>
                    <div class="new-formula-home">
                        <a href="{{url('website/sent-request',$user->id)}}" class="new-homes">ADD FRIEND</a>
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
        $('.nonloop').owlCarousel({
            center: true,
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