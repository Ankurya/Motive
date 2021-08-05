@extends('website.common')
@section('title', 'Motive')

@section('content')
    <section class="bg-color main-section"> 
<div class="container">
    <div class="heading-top">
        <h1>INVITE friends</h1>
    </div>
                <div class="tabs">
                    <ul class="tab-links">
                        <li class="active"><a href="#tab1">Now</a></li>
                        <li><a href="#tab2">future</a></li>
                        <li><a href="#tab3">favourites</a></li>
                    </ul>

                    <div class="tab-content">
                        <div id="tab1" class="tab active">
                          <div class="col-md-6 col-md-offset-3">
                          	<div class="form-reflex form-info-settings">
                          		<div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="user-wrap">
                                    <input type="checkbox" id="ckbCheckAll" name="">
                                    <label class="check">All</label>
                                </div>
                                    </div>
                                </div>
                                 <div class="col-md-12">
                                    <div class="form-group">
                                      <div class="user-wrap ">
                                          <img src="{{url('public/website/images/licon-yellow.png')}}">
                                                <label>johndeo</label>
                                        <input type="checkbox" class="checkBoxClass" name="">
                                    <label class="check"></label>
                                </div>
                                    </div>
                                </div>
                                 <div class="col-md-12">
                                    <div class="form-group">
                                      <div class="user-wrap ">
                                          <img src="{{url('public/website/images/licon-yellow.png')}}">
                                                <label>johndeo</label>
                                        <input type="checkbox" name="" class="checkBoxClass">
                                    <label class="check"></label>
                                </div>
                                    </div>
                                </div>
                                 <div class="col-md-12">
                                    <div class="form-group">
                                    <div class="user-wrap ">
                                                <img src="{{url('public/website/images/licon-yellow.png')}}">
                                                <label>johndeo</label>
                                        <input type="checkbox" name="" class="checkBoxClass">
                                    <label class="check"></label>
                                    </div>
                                    </div>
                                </div>
                                 <div class="col-md-12">
                                    <div class="form-group">
                                       <div class="user-wrap ">
                                          <img src="{{url('public/website/images/licon-yellow.png')}}">
                                                <label>johndeo</label>
                                        <input type="checkbox" name="" class="checkBoxClass">
                                    <label class="check"></label>
                                    </div>
                                    </div>
                                </div>
                                 <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="user-wrap ">
                                              <img src="{{url('public/website/images/licon-yellow.png')}}">
                                                <label>johndeo</label>
                                            <input type="checkbox" name="" class="checkBoxClass">
                                    <label class="check"></label>
                                    </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="user-wrap ">
                                              <img src="{{url('public/website/images/licon-yellow.png')}}">
                                                <label>johndeo</label>
                                            <input type="checkbox" name="" class="checkBoxClass">
                                    <label class="check"></label>
                                    </div>
                                    </div>
                                </div>
                                 <div class="col-md-12">
                                    <div class="form-group margin-top-70 text-center">
                                          <a href="{{url('website/events')}}" class="btn_primary">INVITE 4 FRIENDS</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                        <div id="" class="tab">
                            <p>Tab #2 content goes here!</p>
                            <p>Donec pulvinar neque sed semper lacinia. Curabitur lacinia ullamcorper nibh; quis imperdiet velit eleifend ac. Donec blandit mauris eget aliquet lacinia! Donec pulvinar massa interdum risus ornare mollis. In hac habitasse platea dictumst. Ut euismod tempus hendrerit. Morbi ut adipiscing nisi. Etiam rutrum sodales gravida! Aliquam tellus orci, iaculis vel.</p>
                        </div>

                        <div id="" class="tab">
                            <p>Tab #3 content goes here!</p>
                            <p>Donec pulvinar neque sed semper lacinia. Curabitur lacinia ullamcorper nibh; quis imperdiet velit eleifend ac. Donec blandit mauris eget aliquet lacinia! Donec pulvinar massa interdum ri.</p>
                        </div>

                        <div id="" class="tab">
                            <p>Tab #4 content goes here!</p>
                            <p>Donec pulvinar neque sed semper lacinia. Curabitur lacinia ullamcorper nibh; quis imperdiet velit eleifend ac. Donec blandit mauris eget aliquet lacinia! Donec pulvinar massa interdum risus ornare mollis. In hac habitasse platea dictumst. Ut euismod tempus hendrerit. Morbi ut adipiscing nisi. Etiam rutrum sodales gravida! Aliquam tellus orci, iaculis vel.</p>
                        </div>
                    </div>
                </div>
                </div>
                </section>
        </div>
    </div>
     
@endsection()