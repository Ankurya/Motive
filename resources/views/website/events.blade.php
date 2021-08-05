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
    /* margin-top: 0px; */
    height: 285px;
    width: 100%;
    background: #fff;
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
        <h1>EVENTS</h1>
    </div>
    @include('website.notifications_message')   
    @if($user = auth()->guard('web')->user())
    <div class="tabs events">
        <ul class="tab-links">
            <li id="button1" class="active"><a href="#">Now</a></li>
            <li id="button2"><a href="#">later</a></li>
            <li id="button3"><a href="#">favourites</a></li>
        </ul>

        <div class="tab-content events">
            <div id="now" class="tab active">
                <div class="new-top-heading">
                    <div class="first-icon">
                        <a href="{{url('website/filter')}}"> <img src="{{url('public/website/images/cup.png')}}"></a>
                    </div>

                    <div class="search-box">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Search">
                            <img src="{{url('public/website/images/search.png')}}">
                            <div class="button-circle jift">
                                <a href="{{url('website/create-motive')}}" ; class="form-control motive">CREATE MOTIV
                                <img src="{{url('public/website/images/plus-button.png')}}">
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="button-circles">
                        <a href="{{url('website/tickets')}}" ; class="form-control tickets">TICKETS</a>
                        <img src="{{url('public/website/images/original-new.png')}}">
                    </div>
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
                                        <a href="javascript:void(0);" class="new-result">{{substr($event->event_name,0,10)}}</a><br>
                                        <a href="javascript:void(0);" class="new-result">{{$event->event_time}}</a>
                                        <a href="javascript:void(0);" class="button-new">@if(!empty($event->ticket_amount))FROM &#163;{{$event->ticket_amount}} @else Free @endif</a>


                                    </div>
                                
                            </div>
                            @endforeach         
                        </div>
                        
                        @endif
                        @endforeach
                        @if($i == 0)
                            <h1 style="font-size:30px; font-weight: 500; color: #fff;text-align:center;}">No Event For Today</h1>
                        @endif
                    </div>

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
                                        <a href="javascript:void(0);" class="button-new">@if(!empty($event->ticket_amount))FROM &#163;{{$event->ticket_amount}} @else Free @endif</a>


                                    </div>
                                
                            </div>
                            @endforeach         
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upcomming -->

            <div id="upcomming" class="tab active">
                <div class="new-top-heading">
                    <div class="first-icon">
                        <a href="{{url('website/filter')}}"> <img src="{{url('public/website/images/cup.png')}}"></a>
                    </div>

                    <div class="search-box">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Search">
                            <img src="{{url('public/website/images/search.png')}}">
                            <div class="button-circle jift">
                                <a href="{{url('website/create-motive')}}" ; class="form-control motive">CREATE MOTIV</a>
                                <img src="{{url('public/website/images/plus-button.png')}}">
                            </div>
                        </div>
                    </div>

                    <div class="button-circles">
                        <a href="{{url('website/tickets')}}" ; class="form-control tickets">TICKETS</a>
                        <img src="{{url('public/website/images/original-new.png')}}">
                    </div>
                </div>
                <div class="img-section jack events">
                    <div class="first-element-section">
                        @foreach($upcoming as $key => $interest)
                        @if($interest && !empty($interest) && count($interest) > 0)
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
                                        <a href="javascript:void(0);" class="new-result">{{substr($event->event_name,0,10)}}</a><br>
                                        <a href="javascript:void(0);" class="new-result">{{$event->event_time}}</a>
                                        <a href="javascript:void(0);" class="button-new">@if(!empty($event->ticket_amount))FROM &#163;{{$event->ticket_amount}} @else Free @endif</a>
                                    </div>
                                
                            </div>
                            @endforeach         
                        </div>
                        @endif
                        @endforeach
                    </div>
                    <div class="first-element-section">
                        <h2 class="new-heading-inner">Other Category</h2>
                        <div class="row">
                            @foreach($upcoming_category as $event)
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
                                        <a href="javascript:void(0);" class="button-new">@if(!empty($event->ticket_amount))FROM &#163;{{$event->ticket_amount}} @else Free @endif</a>


                                    </div>
                                
                            </div>
                            @endforeach         
                        </div>
                    </div>
                </div>
            </div>

             <!-- Favorite -->

            <div id="fav" class="tab active">
                <div class="new-top-heading">
                    <div class="first-icon">
                        <a href="{{url('website/filter')}}"> <img src="{{url('public/website/images/cup.png')}}"></a>
                    </div>

                    <div class="search-box">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Search">
                            <img src="{{url('public/website/images/search.png')}}">
                            <div class="button-circle jift">
                                <a href="{{url('website/create-motive')}}" ; class="form-control motive">CREATE MOTIV</a>
                                <img src="{{url('public/website/images/plus-button.png')}}">
                            </div>
                        </div>
                    </div>

                    <div class="button-circles">
                        <a href="{{url('website/tickets')}}" ; class="form-control tickets">TICKETS</a>
                        <img src="{{url('public/website/images/original-new.png')}}">
                    </div>
                </div>
                <div class="img-section jack events">
                    <div class="first-element-section">
                        <?php $i = 0; ?>
                        @if($favorite && !empty($favorite) && count($favorite)>0)
                            @foreach($favorite as $key => $interest)
                            @if($interest && !empty($interest) && count($interest) > 0)
                            <h2 class="new-heading-inner">{{$key}}</h2>
                            <div class="row">
                                @foreach($interest as $event)
                                <?php $i++ ?>
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
                                            <a href="javascript:void(0);" class="new-result">{{substr($event->event_name,0,10)}}</a> <br>
                                            <a href="javascript:void(0);" class="new-result">{{$event->event_time}}</a>
                                            <a href="javascript:void(0);" class="button-new">@if(!empty($event->ticket_amount))FROM &#163;{{$event->ticket_amount}} @else Free @endif</a>


                                        </div>
                                    
                                </div>
                                @endforeach         
                            </div>
                            @endif
                            @endforeach
                            @if($i == 0)
                            <h1 style="font-size:30px; font-weight: 500; color: #fff;text-align:center;}">No Event In Favourites</h1>
                            @else 
                            @endif
                        @else
                        
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
    @else
    <div class="tabs events">
        <ul class="tab-links">
            <li id="button1" class="active"><a href="#">Now</a></li>
            <li id="button2"><a href="#">later</a></li>
            <!-- <li id="button3"><a href="#">favourites</a></li> -->
        </ul>

        <div class="tab-content events">
            <div id="now" class="tab active">
                <div class="new-top-heading">
                    <div class="first-icon">
                        <a href="{{url('website/filter')}}"> <img src="{{url('public/website/images/cup.png')}}"></a>
                    </div>

                    <div class="search-box">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Search">
                            <img src="{{url('public/website/images/search.png')}}">
                            <div class="button-circle jift">
                                <a href="{{url('website/create-motive')}}" ; class="form-control motive">CREATE MOTIV</a>
                                <img src="{{url('public/website/images/plus-button.png')}}">
                            </div>
                        </div>
                    </div>

                    <div class="button-circles">
                        <a href="{{url('website/tickets')}}" ; class="form-control tickets">TICKETS</a>
                        <img src="{{url('public/website/images/original-new.png')}}">
                    </div>
                </div>
                <div class="img-section jack events">
                    <div class="first-element-section">
                        @foreach($data as $key => $interest)
                        @if($interest && !empty($interest) && count($interest) > 0)
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
                                        <a href="javascript:void(0);" class="new-result">{{substr($event->event_name,0,10)}}</a><br>
                                        <a href="javascript:void(0);" class="new-result">{{$event->event_time}}</a>
                                        <a href="javascript:void(0);" class="button-new">@if(!empty($event->ticket_amount))FROM &#163;{{$event->ticket_amount}} @else Free @endif</a>


                                    </div>
                                
                            </div>
                            @endforeach         
                        </div>
                        @endif
                        @endforeach
                    </div>


                </div>
            </div>

            <!-- Upcomming -->

            <div id="upcomming" class="tab active">
                <div class="new-top-heading">
                    <div class="first-icon">
                        <a href="{{url('website/filter')}}"> <img src="{{url('public/website/images/cup.png')}}"></a>
                    </div>

                    <div class="search-box">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Search">
                            <img src="{{url('public/website/images/search.png')}}">
                            <div class="button-circle jift">
                                <a href="{{url('website/create-motive')}}" ; class="form-control motive">CREATE MOTIV</a>
                                <img src="{{url('public/website/images/plus-button.png')}}">
                            </div>
                        </div>
                    </div>

                    <div class="button-circles">
                        <a href="{{url('website/tickets')}}" ; class="form-control tickets">TICKETS</a>
                        <img src="{{url('public/website/images/original-new.png')}}">
                    </div>
                </div>
                <div class="img-section jack events">
                    <div class="first-element-section">
                        @foreach($upcoming as $key => $interest)
                        @if($interest && !empty($interest) && count($interest) > 0)
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
                                        <a href="javascript:void(0);" class="new-result">{{substr($event->event_name,0,10)}}</a><br>
                                        <a href="javascript:void(0);" class="new-result">{{$event->event_time}}</a>
                                        <a href="javascript:void(0);" class="button-new">@if(!empty($event->ticket_amount))FROM &#163;{{$event->ticket_amount}} @else Free @endif</a>


                                    </div>
                                
                            </div>
                            @endforeach         
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
                 <div class="first-element-section">
                        <h2 class="new-heading-inner">Other Category</h2>
                        <div class="row">
                            @foreach($upcoming_category as $event)
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
                                        <a href="javascript:void(0);" class="button-new">@if(!empty($event->ticket_amount))FROM &#163;{{$event->ticket_amount}} @else Free @endif</a>


                                    </div>
                                
                            </div>
                            @endforeach         
                        </div>
                    </div>
            </div>

            

        </div>
    </div>
    @endif
</div>
@endsection()

@section('js')

<script>
$(document).on('click','#button1',function(){
// alert('hii');
$("#now").show();
$("#upcomming").hide();
$("#fav").hide();

$( "#button2" ).removeClass( "active" );
$( "#button3" ).removeClass( "active" );
$( "#button1" ).addClass( "active" );
});
$( document ).ready(function() {
    $("#upcomming").hide();
    $("#fav").hide();
});
$(document).on('click','#button2',function(){
// alert('hii');
$("#upcomming").show();
$("#now").hide();
$("#fav").hide();

$( "#button2" ).addClass( "active" );
$( "#button1" ).removeClass( "active" );
$( "#button3" ).removeClass( "active" );

});

$(document).on('click','#button3',function(){
// alert('hii');
$("#fav").show();
$("#upcomming").hide();
$("#now").hide();

$( "#button3" ).addClass( "active" );
$( "#button2" ).removeClass( "active" );
$( "#button1" ).removeClass( "active" );

});
</script>
@endsection
