@extends('website.common')
@section('title', 'Motive')

@section('content')
<style type="text/css">
.new-home {
    display: inline-block;
}

.time-pern {
    display: inline-block;
    float: right;
    margin-top: 21px;
    text-align: right;


}
.new-location-box.new {
    border: 1px solid #fff;
    padding: 0px 18px;
        margin-bottom: 28px;


}
.heading-top {
    padding: 59px 0 14px 0;
}
.new-event-name h2 {

    margin-bottom: 7px;
}
.new-event-name {
    padding: 0;
}
.new-format-again-website-view figure img {
    width: 100%;
    height: 309px;
        object-fit: contain;

}
.new-format-again-website-view figure {
    border: 1px solid #fff;
    border-radius: 8px;
}
.time-pern.quantity {
    margin-top: 27px;
}

.new-format-again-website-view figure img{
        border-radius: 17px;

}
.new-home {
    display: inline-block;
    width: 41%;
}
</style>
<div class="container">
  <div class="heading-top">
    <h1>tickets</h1>
</div>
<div class="tabs">
    <div class="col-md-8 col-md-offset-2">
        <div id="tab1" class="tab active">

            <div class="new-format-again-website-view">
                @if($get_events->main == 1)
                    @if($get_events->event_media_type == 1)
                    <figure class="new">
                        @if($get_events->event_image_url)
                        <img src="{{$get_events->event_image_url}}">
                        @else 
                        <img src="{{url('public/website/images/img-brackets.png')}}">
                        @endif
                    </figure>
                    @else 
                     <video class="create" controls="" width="100%" height="200px;" style="margin-top: 12px;">
                        <source src="{{$get_events->event_video_url}}" type="video/mp4">
                        <!-- <source src="{{url('public/website/video/movie.ogg')}}" type="video/ogg"> -->
                     </video>
                     @endif
                @else 
                    @if($get_events->event_media_type == 1)
                    <figure class="new">
                        @if($get_events->event_image_url2)
                        <img src="{{$get_events->event_image_url2}}">
                        @else 
                        <img src="{{url('public/website/images/img-brackets.png')}}">
                        @endif
                    </figure>
                    @else 
                     <video class="create" controls="" width="100%" height="200px;" style="margin-top: 12px;">
                        <source src="{{$get_events->event_video_url2}}" type="video/mp4">
                     </video>
                     @endif
                @endif
                <div class="new-event-name">
                    <div class="new-home">
                        <h2>Event name: {{$get_events->event_name}}</h2>
                        <h2>@if($get_events->total_tickets)
                        @elseif($get_events->total_tickets>1)
                        {{$get_events->total_tickets}} 
                        tickets 
                        @elseif($get_events->total_tickets==1)
                        {{$get_events->total_tickets}} 
                        ticket
                        @elseif($get_events->total_tickets==0)
                        0 ticket 
                        
                        @endif</h2>
                        <h2>Event location: {{$get_events->event_location}}</h2>
                    </div>
                    <div class="time-pern">
                        <h2>Event start day: <?php echo date('d F, Y', strtotime($get_events->event_start_date_time));?></h2>
                        <h2>Event end day: <?php echo date('d F, Y', strtotime($get_events->event_end_date_time));?></h2>
                        <h2>Event start time: <?php echo date("g:i a", strtotime($get_events->event_time));?></h2>
                        <h2>Event end time: <?php echo date("g:i a", strtotime($get_events->end_time));?></h2>
                    </div>
                </div>

            </div>
            @foreach($tickets as $ticket)
            <a href="{{url('website/ticket-qr',$ticket->id)}}">
                <div class="new-location-box new">
                    <div class="new-event-name">
                        <div class="new-home">
                            <h2>TICKET: {{$ticket->ticket_title}}</h2>
                            <h2>{{$ticket->ticket_description}}</h2>
                            <!-- <h2>{{$ticket->ticket_quantity}}</h2> -->
                        </div>
                        <div class="time-pern quantity">
                            <h2>AMOUNT :  {{$ticket->amount}}</h2>
                            <h2>TICKET PURCHASED : &#163;{{$ticket->quantity}}</h2>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach()




    </div>
</div>
</div>     
</div>       

@endsection()