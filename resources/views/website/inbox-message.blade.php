@extends('website.common')
@section('title', 'Motive')

@section('content')
<div class="container">
    <div class="heading-top">
        <h1>inbox</h1>
    </div>
    <div class="tabs inbox">
        <div class="new-tabs">
            <ul class="tab-links">
                <li class="active"><a href="#tab1">Now</a></li>
                <li><a href="#tab2">future</a></li>
                <li><a href="#tab3">favourites</a></li>
            </ul>
        </div>
        <div id="tab1" class="tab active">
          <div class="new-homesd">
            <span>New Message</span>
            <figure><img src="{{url('public/website/images/new-foram.png')}}"></figure>
        </div>

        <div class="new-file">
            <a href="inbox.html">

                <figure><img src="{{url('public/website/images/user-new.png')}}"></figure>
                <div class="new-format-review">
                    <p>johndeo</p>
                    <p class="jack">Lorem Ipsum is simply dummy text of the printing 
                        and typesetting industry. Lorem Ipsum has been 
                    the industry's standard dummy text ever since. </p>

                </div>
            </a>
        </div>


        <div class="new-file">
           <a href="inbox.html">
            <figure><img src="{{url('public/website/images/user-new.png')}}"></figure>
            <div class="new-format-review">
                <p>johndeo</p>
                <p class="jack">Lorem Ipsum is simply dummy text of the printing 
                    and typesetting industry. Lorem Ipsum has been 
                the industry's standard dummy text ever since. </p>

            </div>
        </a>
    </div>

    <div class="new-file">
       <a href="inbox.html">
        <figure><img src="{{url('public/website/images/user-new.png')}}"></figure>
        <div class="new-format-review">
            <p>johndeo</p>
            <p class="jack">Lorem Ipsum is simply dummy text of the printing 
                and typesetting industry. Lorem Ipsum has been 
            the industry's standard dummy text ever since. </p>

        </div>
    </a>
</div>
<div class="new-file">
   <a href="inbox.html">
    <figure><img src="{{url('public/website/images/user-new.png')}}"></figure>
    <div class="new-format-review">
        <p>johndeo</p>
        <p class="jack">Lorem Ipsum is simply dummy text of the printing 
            and typesetting industry. Lorem Ipsum has been 
        the industry's standard dummy text ever since. </p>

    </div>
</a>
</div>
</div>


<div id="tab8" class="tab">
    <div class="new-file">
        <figure><img src="{{url('public/website/images/user-new.png')}}"></figure>
        <div class="new-format-review">
            <p>johndeo</p>
            <p class="jack"> Lorem Ipsum is simply dummy text of the printing 
                and typesetting industry. Lorem Ipsum has been 
            the industry's standard dummy text ever since. </p>

        </div>
    </div>
    <div class="new-file">
        <figure><img src="{{url('public/website/images/user-new.png')}}"></figure>
        <div class="new-format-review">
            <p>johndeo</p>
            <p class="jack"> Lorem Ipsum is simply dummy text of the printing 
                and typesetting industry. Lorem Ipsum has been 
            the industry's standard dummy text ever since. </p>

        </div>
    </div>
    <div class="new-file">
        <figure><img src="{{url('public/website/images/user-new.png')}}"></figure>
        <div class="new-format-review">
            <p>johndeo</p>
            <p class="jack"> Lorem Ipsum is simply dummy text of the printing 
                and typesetting industry. Lorem Ipsum has been 
            the industry's standard dummy text ever since. </p>

        </div>
    </div>
    <div class="new-file">
        <figure><img src="{{url('public/website/images/user-new.png')}}"></figure>
        <div class="new-format-review">
            <p>johndeo</p>
            <p class="jack"> Lorem Ipsum is simply dummy text of the printing 
                and typesetting industry. Lorem Ipsum has been 
            the industry's standard dummy text ever since. </p>

        </div>
    </div>
</div>

<div id="tab6" class="tab">
    <div class="new-file">
        <figure><img src="{{url('public/website/images/user-new.png')}}"></figure>
        <div class="new-format-review">
            <p>johndeo</p>
            <p class="jack"> Lorem Ipsum is simply dummy text of the printing 
                and typesetting industry. Lorem Ipsum has been 
            the industry's standard dummy text ever since. </p>

        </div>
    </div>
    <div class="new-file">
        <figure><img src="{{url('public/website/images/user-new.png')}}"></figure>
        <div class="new-format-review">
            <p>johndeo</p>
            <p class="jack"> Lorem Ipsum is simply dummy text of the printing 
                and typesetting industry. Lorem Ipsum has been 
            the industry's standard dummy text ever since. </p>

        </div>
    </div>
    <div class="new-file">
        <figure><img src="{{url('public/website/images/user-new.png')}}"></figure>
        <div class="new-format-review">
            <p>johndeo</p>
            <p class="jack"> Lorem Ipsum is simply dummy text of the printing 
                and typesetting industry. Lorem Ipsum has been 
            the industry's standard dummy text ever since. </p>

        </div>
    </div>
    <div class="new-file">
        <figure><img src="{{url('public/website/images/user-new.png')}}"></figure>
        <div class="new-format-review">
            <p>johndeo</p>
            <p class="jack"> Lorem Ipsum is simply dummy text of the printing 
                and typesetting industry. Lorem Ipsum has been 
            the industry's standard dummy text ever since.</p>

        </div>
    </div>
</div>

<div id="tab7" class="tab">
    <p>Tab #4 content goes here!</p>
    <p>Donec pulvinar neque sed semper lacinia. Curabitur lacinia ullamcorper nibh; quis imperdiet velit eleifend ac. Donec blandit mauris eget aliquet lacinia! Donec pulvinar massa interdum risus ornare mollis. In hac habitasse platea dictumst. Ut euismod tempus hendrerit. Morbi ut adipiscing nisi. Etiam rutrum sodales gravida! Aliquam tellus orci, iaculis vel.</p>
</div>
</div>
</div>
</div>



@endsection()