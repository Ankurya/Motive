@extends('website.common')
@section('title', 'Motive')

@section('content')
<div class="container">
    <div class="heading-top">
        <h1>EVENTS</h1>
    </div>
    <div class="tabs events">
        <ul class="tab-links">
            <li class="active"><a href="#tab1">Now</a></li>
            <li><a href="#tab2">future</a></li>
            <li><a href="#tab3">favourites</a></li>
        </ul>

        <div class="tab-content events">
            <div id="tab1" class="tab active">
                <div class="new-top-heading">
                    <div class="first-icon">
                        <a href="filter.html"><img src="{{url('public/website/images/cup.png')}}"></a>                                </div>

                        <div class="search-box">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Search">
                                <img src="{{url('public/website/images/search.png')}}">
                                <div class="button-circle jift">
                                    <a href="create-motiv.html" ; class="form-control motive">CREATE MOTIV</a>
                                    <img src="{{url('public/website/images/plus-button.png')}}">
                                </div>
                            </div>
                        </div>

                        <div class="button-circles">
                            <a href="tickets.html" ; class="form-control tickets">TICKETS</a>
                            <img src="{{url('public/website/images/original-new.png')}}">
                        </div>
                    </div>
                    <div class="img-section jack">
                        <h2 class="new-heading-inner"></h2>
                        <div class="row">
                            <div class="col-sm-4">
                                <a href="website-view.html">
                                    <div class="img-factor">
                                        <figure><img src="{{url('public/website/images/img1.png')}}"></figure>
                                        <div class="new-description">
                                            <h3>Event Name</h3>
                                            <p>10:00 Am</p>
                                        </div>
                                    </div>
                                    <div class="social-icon">
                                        <a href="javascript:void(0);" class="new-result"><img src="{{url('public/website/images/h1.png')}}"></a>
                                        <a href="javascript:void(0);" class="new-result"><img src="{{url('public/website/images/ho.png')}}"></a>
                                        <a href="javascript:void(0);" class="button-new">FROM &#163;2.00</a>


                                    </div>
                                </a>
                            </div>
                            <div class="col-sm-4">
                              <a href="website-view.html">
                                <div class="img-factor">
                                    <figure><img src="{{url('public/website/images/img2.png')}}"></figure>
                                    <div class="new-description">
                                        <h3>Event Name</h3>
                                        <p>10:00 Am</p>
                                    </div>
                                </div>
                                <div class="social-icon">
                                    <a href="javascript:void(0);" class="new-result"><img src="{{url('public/website/images/h1.png')}}"></a>
                                    <a href="javascript:void(0);" class="new-result"><img src="{{url('public/website/images/ho.png')}}"></a>
                                    <a href="javascript:void(0);" class="button-new">FROM &#163;2.00</a>


                                </div>
                            </a>
                        </div>
                        <div class="col-sm-4">
                            <div class="img-factor">
                                <figure><img src="{{url('public/website/images/img3.png')}}"></figure>
                                <div class="new-description">
                                    <h3>Event Name</h3>
                                    <p>10:00 Am</p>
                                </div>
                            </div>
                            <div class="social-icon">
                                <a href="javascript:void(0);" class="new-result"><img src="{{url('public/website/images/h1.png')}}"></a>
                                <a href="javascript:void(0);" class="new-result"><img src="{{url('public/website/images/ho.png')}}"></a>
                                <a href="javascript:void(0);" class="button-new">FROM &#163;2.00</a>


                            </div>
                        </div>
                    </div>
                    <div class="row">
                     <h2 class="new-heading-inner"></h2>
                     <div class="col-sm-4">
                      <a href="website-view.html">
                        <div class="img-factor">
                            <figure><img src="{{url('public/website/images/img3.png')}}"></figure>
                            <div class="new-description">
                                <h3>Event Name</h3>
                                <p>10:00 Am</p>
                            </div>
                        </div>
                        <div class="social-icon">
                            <a href="javascript:void(0);" class="new-result"><img src="{{url('public/website/images/h1.png')}}"></a>
                            <a href="javascript:void(0);" class="new-result"><img src="{{url('public/website/images/ho.png')}}"></a>
                            <a href="javascript:void(0);" class="button-new">FROM &#163;2.00</a>


                        </div>
                    </a>
                </div>
                <div class="col-sm-4">
                    <div class="img-factor">
                      <a href="website-view.html">
                        <figure><img src="{{url('public/website/images/img1.png')}}"></figure>
                        <div class="new-description">
                            <h3>Event Name</h3>
                            <p>10:00 Am</p>
                        </div>
                    </div>
                    <div class="social-icon">
                        <a href="javascript:void(0);" class="new-result"><img src="images/h1.png"></a>
                        <a href="javascript:void(0);" class="new-result"><img src="images/ho.png"></a>
                        <a href="javascript:void(0);" class="button-new">FROM &#163;2.00</a>


                    </div>
                </a>
            </div>
            <div class="col-sm-4">
              <a href="website-view.html">
                <div class="img-factor">
                    <figure><img src="{{url('public/website/images/img2.png')}}"></figure>
                    <div class="new-description">
                        <h3>Event Name</h3>
                        <p>10:00 Am</p>
                    </div>
                </div>
                <div class="social-icon">
                    <a href="javascript:void(0);" class="new-result"><img src="images/h1.png"></a>
                    <a href="javascript:void(0);" class="new-result"><img src="images/ho.png"></a>
                    <a href="javascript:void(0);" class="button-new">FROM &#163;2.00</a>


                </div>
            </div>
        </a>
    </div>
</div>

</div>
</div>
</div>
<div id="tab2" class="tab">
    <p>Tab #2 content goes here!</p>
    <p>Donec pulvinar neque sed semper lacinia. Curabitur lacinia ullamcorper nibh; quis imperdiet velit eleifend ac. Donec blandit mauris eget aliquet lacinia! Donec pulvinar massa interdum risus ornare mollis. In hac habitasse platea dictumst. Ut euismod tempus hendrerit. Morbi ut adipiscing nisi. Etiam rutrum sodales gravida! Aliquam tellus orci, iaculis vel.</p>
</div>

<div id="tab3" class="tab">
    <p>Tab #3 content goes here!</p>
    <p>Donec pulvinar neque sed semper lacinia. Curabitur lacinia ullamcorper nibh; quis imperdiet velit eleifend ac. Donec blandit mauris eget aliquet lacinia! Donec pulvinar massa interdum ri.</p>
</div>

<div id="tab4" class="tab">
    <p>Tab #4 content goes here!</p>
    <p>Donec pulvinar neque sed semper lacinia. Curabitur lacinia ullamcorper nibh; quis imperdiet velit eleifend ac. Donec blandit mauris eget aliquet lacinia! Donec pulvinar massa interdum risus ornare mollis. In hac habitasse platea dictumst. Ut euismod tempus hendrerit. Morbi ut adipiscing nisi. Etiam rutrum sodales gravida! Aliquam tellus orci, iaculis vel.</p>
</div>
</div>
</div>
</div>
</div>
</div>
@endsection()