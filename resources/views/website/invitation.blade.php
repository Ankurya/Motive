@extends('website.common')
@section('title', 'Motive')

@section('content')
<style type="text/css">
    .img-section .col-sm-4 {
    min-height: 331px;
}
.tab-content.tickets{
        width: 100%;

}
.img-section .col-sm-4 {
    min-height: 476px;
}

video.create{
    height: 285px;
    width: 100%;
    background: #000;
    border: 1px solid #fff;
    border-radius: 12px;
}
</style>
<div class="container">
    <div class="heading-top">
        <h1>invitations</h1>
    </div>
    @include('website.notifications_message')
    <div class="tabs">
        <div class="tab-content tickets">
            <div id="tab1" class="tab active">

                <div class="img-section">
                    @if($invitations && !empty($invitations) && count($invitations) > 0)
                    <div class="row">
                        <?php $notEmpty = 0;?>
                        @foreach($invitations as $invitation)
                        @if(!empty($invitation->event) && $invitation->event)

                        <div class="col-sm-4">
                            <div class="img-factor">
                                <a href="{{url('website/website-first',$invitation->id)}}">
                                    @if($invitation->event->main == 1)
                                        @if($invitation->event->event_media_type == 1)
                                            <figure>
                                                @if($invitation->event->event_image_url)
                                                    <img src="{{$invitation->event->event_image_url}}">
                                                @else 
                                                    <img src="{{url('public/website/images/img2.png')}}">
                                                @endif
                                            </figure>
                                        @else
                                        <video class="create" controls="" width="100%" height="280px;"  style="border-radius:27px; margin-top: -27px; margin-top:3px; background: #000;">
                                            <source src="{{$invitation->event->event_video_url}}" type="video/mp4">
                                        </video>
                                        @endif

                                    @else 
                                        @if($invitation->event->event_media_type == 1)
                                            <figure>
                                                @if($invitation->event->event_image_url2)
                                                    <img src="{{$invitation->event->event_image_url2}}">
                                                @else 
                                                    <img src="{{url('public/website/images/img2.png')}}">
                                                @endif
                                            </figure>
                                        @else
                                        <video class="create" controls="" width="100%" height="274px;" style="border-radius:27px; margin-top: -27px; margin-top:3px; background: #000;">
                                            <source src="{{$invitation->event->event_video_url2}}" type="video/mp4">
                                        </video>
                                        @endif

                                    @endif
                                    <div class="new-description">
                                        <h3>{{$invitation->event->event_name}}</h3>
                                        <div class="new-formula">
                                            <h2><?php echo date('d-m-Y',strtotime($invitation->event->event_date));?></h2>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="social-icon">
                                <a href="javascript:void(0);" class="new-result first">invited by:</a>
                                <a href="javascript:void(0);" class="new-result few">@if($invitation->user_detail->name){{$invitation->user_detail->name}} @else User @endif</a>
                            </div>
                        </div>
                        @else 
                        <?php $notEmpty++; ?>
                        <!-- <h1 style="font-size:30px; font-weight: 500; color: #fff;text-align:center;}">No Invitation</h1> -->
                        @endif
                        @endforeach

                        <!-- @if($notEmpty > 0)
                            <h1 style="font-size:30px; font-weight: 500; color: #fff;text-align:center;margin-bottom:40px; margin-left:50px; margin-right:100px; }">No Invitation Available</h1>
                        @endif -->

                    </div>
                    @else 
                    <h1 style="font-size:30px; font-weight: 500; color: #fff;text-align:center;margin-bottom: 40px; margin-right:50px; }">No Invitation Available</h1>
                    @endif
                </div>

            </div>
        </div>
    </div>

</div>

@endsection()
