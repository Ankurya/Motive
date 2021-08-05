@extends('website.common')
@section('title', 'Motive')

@section('content')
<style type="text/css">
 .new-files figure img {
    width: 200px;
    height: 200px;
    border-radius: 100%;
}

span.label.label-warning {
    position: absolute;
    top: -14px;
    z-index: 999;
    color: #1c1c1c;
    border-radius: 110px;
    width: 30px;
    height: 30px;
    text-align: center;
    /* vertical-align: middle; */
    left: 55px;
    /* align-items: center; */
    padding: 4px 0px 0 0;
    /* justify-content: center; */
    /* align-self: center; */
    right: 0;
    margin: auto;
    font-size: 20px;
}
.new-files {
    padding: 0px 0 18px 0;
}
.new-profile-content span a {
    text-transform: uppercase;
}
.new-profile-content{
        width: 31%;
        position: relative;

}
.form-reflex.form-info-settings.myprofile {
    padding: 0;
}

.alert.alert-success.alert-dismissible.text-center.alertz {
    max-width: 702px;
    margin: 0 auto;
    width: 100%;
    margin-bottom: 10px;

}

.new-files span {
    font-size: 22px;
    }

</style>
<div class="container">
    <div class="heading-top">
        <h1>my profile</h1>
    </div>
    @include('website.notifications_message')
    <div class="tabs">

        <div class="col-md-8 col-md-offset-2">
            <div id="tab1" class="tab active">

                <div class="form-reflex form-info-settings myprofile ">
                    <div class="new-files">
                        <figure>
                            @if($user->image_url)
                                <img src="{{$user->image_url}}">
                            @else
                                <img src="{{url('public/website/images/user-friendly.png')}}">
                            @endif
                        </figure>
                        <span style="font-weight: bolder; margin: 12px 0; display: block;">{{$user->name}}</span>
                        @if($user->role == 2)
                        @else
                        <span>{{$user->email}}</span><br>
                        <span>{{$user->phone_number}}</span>
                        @endif
                    </div>
                    @if($user->role == 2)
                    <div class="new-my-profile">
                        <div class="new-profile-content">
                            <figure><a href="{{url('website/friend-list')}}"><img src="{{url('public/website/images/user-white.png')}}"></a></figure>
                            <span><a href="{{url('website/friend-list')}}">Friend List</a></span>
                        </div>
                        <div class="new-profile-content">
                            <figure><a href="{{url('website/friend-request')}}"><img src="{{url('public/website/images/disc2.png')}}"></a></figure>
                            <span><a href="{{url('website/friend-request')}}">Friend Request</a> <span class="label label-warning">{{$friend_count}}</span></span>
                        </div>
                        <div class="new-profile-content">
                            <figure><a href="{{url('website/referall-code')}}"><img src="{{url('public/website/images/disc3.png')}}"></a></figure>
                            <span><a href="{{url('website/referall-code')}}">Referral code</a></span>
                        </div>

                        
                        
                    </div>
                    <div class="new-my-profile right">
                        <div class="new-profile-content">
                            <figure><a href="{{url('website/public-interest')}}"><img src="{{url('public/website/images/disc4.png')}}"></a></figure>
                            <span><a href="{{url('website/public-interest')}}">Public interest</a></span>
                        </div>
                        <div class="new-profile-content">
                            <figure><a href="{{url('website/music-interest')}}"><img src="{{url('public/website/images/disc5.png')}}"></a></figure>
                            <span><a href="{{url('website/music-interest')}}">Music Interest</a></span>
                        </div>
                        

                        
                        
                    </div>
                    <div class="new-my-profile last">
                        <div class="new-profile-content">
                            <figure><a href="{{url('website/tickets')}}"><img src="{{url('public/website/images/tickets.png')}}"></a></figure>
                            <span><a href="{{url('website/tickets')}}">Tickets</a></span>
                        </div>
                        <div class="new-profile-content">
                            <figure><a href="{{url('website/past-event')}}"><img src="{{url('public/website/images/disc6.png')}}"></a></figure>
                            <span><a href="{{url('website/past-event')}}">Past Events</a></span>
                        </div>
                        <div class="new-profile-content">
                            <figure><a href="{{url('website/invitation')}}"><img src="{{url('public/website/images/disc7.png')}}"></a></figure>
                            <span><a href="{{url('website/invitation')}}">Invitations</a></span>
                        </div>      
                    </div>
                    @else 
                    <div class="new-my-profile last">
                        <div class="new-profile-content">
                            <figure><a href="{{url('website/my-event')}}"><img src="{{url('public/website/images/event.png')}}" style="height: 50px;"></a></figure>
                            <span><a href="{{url('website/my-event')}}">My Events</a></span>
                        </div>
                    </div>
                    @endif
                    <div class="new-formula-home">
                        <a href="{{url('website/edit-profile',$user->id)}}" class="new-homes">edit details</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection()