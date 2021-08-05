@extends('website.common')
@section('title', 'Motive')

@section('content')
<style type="text/css">
    .tab-content {
    padding: 0;
    margin: 0;
}
.heading-top {
    padding: 77px 0 1px 0;
}
</style>

@php
    if($user->age_status == 0) {
        $checked = "";
    } else {
        $checked = "checked";
    }

    if($user->notification_status == 2) {
        $check = "";
    } else {
        $check = "checked";
    }
@endphp


<div class="container">
    <div class="heading-top">
        <h1>settings</h1>
    </div>
    <?php $user = auth()->guard('web')->user();?> 
    @if($user->role == 2)
    <div class="tabs">
        <div class="col-md-6 col-md-offset-3">
            <div id="tab1" class="tab active">
                <div class="new-rest-again">
                        <span>ENABLE NOTIFICATIONS</span>
                        <span class="new-again"><label class="switch">
                           <input type="checkbox" class="on" {{$check}}>
                            <span class="slider round"></span>
                        </label></span>
                </div>
                <div class="new-rest-again">
                    <a href="{{url('website/block-users')}}">
                        <span>BLOCKED USERS</span>
                        <span class="new-again"><img src="{{url('public/website/images/pencil.png')}}"></span>
                    </a>
                </div>
                <div class="new-rest-again">
                    <a href="{{url('website/contact-us')}}">
                        <span>CONTACT US</span>
                        <span class="new-again"><img src="{{url('public/website/images/pencil.png')}}"></span>
                    </a>
                </div>
                <div class="new-rest-again">
                    <a href="{{url('website/change-password')}}">
                        <span>CHANGE PASSWORD</span>
                        <span class="new-again"><img src="{{url('public/website/images/pencil.png')}}"></span>
                    </a>
                </div>
                <div class="new-rest-again">
                    <a href="{{url('website/logout')}}">
                        <span>LOGOUT</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="tabs"> 
        <div class="col-md-6 col-md-offset-3">
            <div id="tab1" class="tab active">
                <div class="new-rest-again">
                    <a href="{{url('website/contact-us')}}">
                        <span>CONTACT US</span>
                        <span class="new-again"><img src="{{url('public/website/images/pencil.png')}}"></span>
                    </a>
                </div>
                <div class="new-rest-again">
                    <a href="{{url('website/change-password')}}">
                        <span>CHANGE PASSWORD</span>
                        <span class="new-again"><img src="{{url('public/website/images/pencil.png')}}"></span>
                    </a>
                </div>
                <div class="new-rest-again">
                    <a href="{{url('website/logout')}}">
                        <span>LOGOUT</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif
</div>
@endsection()
@section('js')

<!-- Notification On And Off --> 
<script type="text/javascript">
$('.on').on('change', function(){ 
    var on = ($(this).prop('checked'));
    if(on == false) {
        $.ajax({
            type: 'post',
            url: '{{url("website/notificationOff")}}',
            data: {
                '_token':  '{{csrf_token()}}',
                'notification_status': 2
            },

            success:function(data) {
            }
        });
    }
});

$('.on').on('change', function(){ 
    var on = ($(this).prop('checked'));
    if(on == true) {
        $.ajax({
            type: 'post',
            url: '{{url("website/notificationOff")}}',
            data: {
                '_token':  '{{csrf_token()}}',
                'notification_status': 1
            },

            success:function(data) {
            }
        });
    }
});

</script>

<!-- Age On And Off -->
<script type="text/javascript">
 $('.show').on('change', function(){ 
    var show = ($(this).prop('checked'));
    if(show == false) {
        // alert('false');
        $.ajax({
            type: 'post',
            url: '{{url("website/show-age")}}',
            data: {
                '_token':  '{{csrf_token()}}',
                'age_status': 0
            },

            success:function(data) {
            }
        });
    }
});

$('.show').on('change', function(){ 
    var show = ($(this).prop('checked'));
    if(show == true) {
        $.ajax({
            type: 'post',
            url: '{{url("website/show-age")}}',
            data: {
                '_token':  '{{csrf_token()}}',
                'age_status': 1
            },

            success:function(data) {
            }
        });
    }
});

</script>

@endsection()
