@extends('website.common')
@section('title', 'Motive')

@section('content')
<section class="bg-color main-section"> 
    <div class="container">
        <div class="heading-top">
            <h1>attending</h1>
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
                                        <img src="images/licon-yellow.png.">
                                        <label class="lowercase">johndeo</label>
                                        <img class="dropdown-icon" src="{{url('public/website/images/dropdown.png')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                  <div class="user-wrap">
                                    <img src="images/licon-yellow.png.">
                                    <label class="lowercase">johndeo</label>
                                    <img class="dropdown-icon" src="{{url('public/website/images/dropdown.png')}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                              <div class="user-wrap">
                                <img src="images/licon-yellow.png.">
                                <label class="lowercase">johndeo</label>
                                <img class="dropdown-icon" src="{{url('public/website/images/dropdown.png')}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="user-wrap">
                                <img src="images/licon-yellow.png.">
                                <label class="lowercase">johndeo</label>
                                <img class="dropdown-icon" src="{{url('public/website/images/dropdown.png')}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                         <div class="user-wrap">
                            <img src="images/licon-yellow.png.">
                            <label class="lowercase">johndeo</label>
                            <img class="dropdown-icon" src="{{url('public/website/images/add-post-image.png')}}images/dropdown.png">
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="user-wrap">
                            <img src="images/licon-yellow.png.">
                            <label class="lowercase">johndeo</label>
                            <img class="dropdown-icon" src="{{url('public/website/images/images/dropdown.png')}}">
                        </div>
                    </div>
                </div> 
                <div class="col-md-12 margin-top-60">
                    <div class="col-sm-6 text-center padding-left-0">
                       <a href="view-profile.html" class="btn_primary width-220">View PRofile</a>
                   </div>
                   <div class="col-sm-6 text-center">
                       <a href="javascript:;" class="btn_secondary width-220 btn_primary">block</a>
                   </div>
               </div>
               <div class="col-md-12 margin-top-30">
                <div class="col-sm-12 text-center">
                   <a href="javascript:;" class="btn_primary">Make co-admin</a>
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

</div>
</div>
</div>
</section>
</div>
</div>

@endsection()