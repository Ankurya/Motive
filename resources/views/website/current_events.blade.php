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
   border:1px solid #fff;
   }
   .img-section.jack .social-icon a.button-new{
   /*width: unset;*/
   margin-top: 2px;
   }
   .img-section .col-sm-4 {
   min-height: 500px;
   }
   .new-top-heading{
   margin-left: 16px;
   }
</style>
<div class="container">
   <div class="heading-top">
      <h1>EVENTS</h1>
   </div>
   <!-- @include('website.notifications_message')    -->
   <div class="tabs events">
      @if($user = auth()->guard('web')->user())
      <ul class="tab-links">
         <li id="button1" class="active"><a href="javascript:void(0);">Now</a></li>
         <li id="button2"><a href="{{url('website/upcoming-events')}}">later</a></li>
         @if($user->role == 2)
         <li id="button3"><a href="{{url('website/favorite-events')}}">favourites</a></li>
         @else
         @endif
      </ul>
      @else
      <ul class="tab-links">
         <li id="button1" class="active"><a href="javascript:void(0);">Now</a></li>
         <li id="button2"><a href="{{url('website/upcoming-events')}}">later</a></li>
      </ul>
      @endif
      <div class="tab-content events">
         <div id="now" class="tab active">
            <div class="new-top-heading">
               @if($user = auth()->guard('web')->user())
               @if($user->role == 2)
               <div class="first-icon">
                  <a href="{{url('website/filter')}}"> <img src="{{url('public/website/images/search-icon.png')}}"></a>
               </div>
               <div class="search-box">
                  <div class="form-group">
                     <img style="cursor: pointer;" class="search" src="{{url('public/website/images/search.png')}}">
                     <input type="text" name="search" class="form-control search_input" placeholder="Search">
                  </div>
               </div>
               <div class="button-circle jift">
                  <a href="{{url('website/create-motive')}}" ; class="form-control motive">CREATE MOTIV
                  <img src="{{url('public/website/images/plus-button.png')}}">
                  </a>
               </div>
               <div class="button-circles">
                  <a href="{{url('website/tickets')}}" ; class="form-control tickets">TICKETS</a>
                  <img src="{{url('public/website/images/original-new.png')}}">
               </div>
               @else 
               <div class="search-box">
                  <div class="form-group">
                     <img style="cursor: pointer;" class="search" src="{{url('public/website/images/search.png')}}">
                     <input type="text" name="search" class="form-control search_input" placeholder="Search">
                  </div>
               </div>
               <div class="button-circle jift">
                  <a href="{{url('website/create-event')}}" ; class="form-control motive">CREATE MOTIV
                  <img src="{{url('public/website/images/plus-button.png')}}">
                  </a>
               </div>
               @endif  
               @else
               <div class="first-icon">
                  <a href="{{url('website/filter')}}"> <img src="{{url('public/website/images/search-icon.png')}}"></a>
               </div>
               <div class="search-box">
                  <div class="form-group">
                     <img style="cursor: pointer;" class="search" src="{{url('public/website/images/search.png')}}">
                     <input type="text" name="search" class="form-control search_input" placeholder="Search">
                  </div>
               </div>
               <div class="button-circle jift">
                  <a href="{{url('website/create-motive')}}" ; class="form-control motive">CREATE MOTIV
                  <img src="{{url('public/website/images/plus-button.png')}}">
                  </a>
               </div>
               <div class="button-circles">
                  <a href="{{url('website/tickets')}}" ; class="form-control tickets">TICKETS</a>
                  <img src="{{url('public/website/images/original-new.png')}}">
               </div>
               @endif
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
                              <video class="create" controls="" width=""  style="margin-top: 0px; height:293px;">
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
                              <video class="create" controls="" width=""  style="margin-top: 0px; height:293px;">
                                 <source src="{{$event->event_video_url2}}" type="video/mp4">
                              </video>
                              @endif
                              @endif
                           </div>
                        </a>
                        <div class="new-description">
                           <h3>{{$event->event_name}}</h3>
                           <div class="new-formula">
                              <h2>{{$event->event_time}}</h2>
                           </div>
                        </div>
                        <div class="social-icon">
                           @if($user = auth()->guard('web')->user())
                           @if($user->role == 2)
                           @if($event->is_favorite == '')
                           <a href="javascript:void(0);" onclick="return ad_favorite({{$event->event_id}},{{$event->id}});" class="new-result fav"><img src="{{url('public/website/images/heart.png')}}"></a>
                           @else
                           <a href="javascript:void(0);" data-id="{{$event->event_id}}" onclick="return ad_favorite({{$event->event_id}},{{$event->id}});" class="new-result fav"><img src="{{url('public/website/images/heart-icon.png')}}"></a>
                           @endif
                           <a href="https://www.facebook.com" target="_blank" class="new-result"><img src="{{url('public/website/images/share.png')}}"></a>
                           @else
                           @endif
                           @endif
                           @if(!empty($event->ticket_amount))
                           <a href="javascript:void(0);" class="button-new"> FROM &#163;{{$event->ticket_amount}}</a>
                           @else @endif
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
                        <div class="new-description">
                           <h3>{{$event->event_name}}</h3>
                           <div class="new-formula">
                              <h2>{{$event->event_time}}</h2>
                           </div>
                        </div>
                        <div class="social-icon">
                           @if($user = auth()->guard('web')->user())
                           @if($user->role == 2)
                           @if($event->is_favorite == '')
                           <a href="javascript:void(0);" onclick="return ad_favorite({{$event->event_id}},{{$event->id}});" class="new-result fav"><img src="{{url('public/website/images/heart.png')}}"></a>
                           @else
                           <a href="javascript:void(0);" data-id="{{$event->event_id}}" onclick="return ad_favorite({{$event->event_id}},{{$event->id}});" class="new-result fav"><img src="{{url('public/website/images/heart-icon.png')}}"></a>
                           @endif
                           <a href="https://www.facebook.com"       target="_blank" class="new-result"><img src="{{url('public/website/images/share.png')}}"></a>
                           @else
                           @endif
                           @endif
                           @if(!empty($event->ticket_amount))
                           <a href="javascript:void(0);" class="button-new"> FROM &#163;{{$event->ticket_amount}}</a>
                           @else @endif
                        </div>
                     </div>
                     @endforeach         
                  </div>
                  @endif
                  @endforeach
               </div>
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
                        <div class="new-description">
                           <h3>{{$event->event_name}}</h3>
                           <div class="new-formula">
                              <h2>{{$event->event_time}}</h2>
                           </div>
                        </div>
                        <div class="social-icon">
                           @if($user = auth()->guard('web')->user())
                           @if($user->role == 2)
                           @if($event->is_favorite == '')
                           <a href="javascript:void(0);" onclick="return ad_favorite({{$event->event_id}},{{$event->id}});" class="new-result fav"><img src="{{url('public/website/images/heart.png')}}"></a>
                           @else
                           <a href="javascript:void(0);" data-id="{{$event->event_id}}" onclick="return ad_favorite({{$event->event_id}},{{$event->id}});" class="new-result fav"><img src="{{url('public/website/images/heart-icon.png')}}"></a>
                           @endif
                           <a href="https://www.facebook.com"       target="_blank" class="new-result"><img src="{{url('public/website/images/share.png')}}"></a>
                           @else
                           @endif
                           @endif
                           @if(!empty($event->ticket_amount))
                           <a href="javascript:void(0);" class="button-new"> FROM &#163;{{$event->ticket_amount}}</a>
                           @else @endif
                        </div>
                     </div>
                     @endforeach         
                  </div>
               </div>
               @else
               @endif
               @if($i == 0 && $j == 0 && count($now_category) == 0)
               <h1 style="font-size:30px; font-weight: 500; color: #fff;text-align:center;}">No Events Available</h1>
               @endif
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
<script type="text/javascript">
   function ad_favorite(event,sub_event_id){
     var sub_event_id = sub_event_id;
     var event_id = event;
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
           console.log(res);
          if(res == 'success'){
             location.reload();
          }else{
             location.reload();
          }
           
         }
       });
   }
</script>
@endsection