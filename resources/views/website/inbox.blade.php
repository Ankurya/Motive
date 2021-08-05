@extends('website.common')
@section('title', 'Motive')

@section('content')
<div class="container">
    <div class="heading-top">
        <h1>USER NAME</h1>
    </div>
                <div class="tabs">
                    <ul class="tab-links">
                        <li class="active"><a href="#tab1">Now</a></li>
                        <li><a href="#tab2">future</a></li>
                        <li><a href="#tab3">favourites</a></li>
                    </ul>

                    <div class="tab-content inbox">
                        <div class="Message-left">
                        <div class="scroll scroll4">
                <div class="content">
                        <div id="tab1" class="tab active">
                            <div class="inbox-Content">
                                <div class="content-pro">
                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard .</p>
                            </div>
                             <div class="content-pro right">
                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard .</p>
                            </div>
                             <div class="content-pro">
                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard .</p>
                            </div>
                             <div class="content-pro right">
                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard .</p>
                            </div>
                        </div>
                         
                    </div>
                </div>
                </div>
                <div class="admin-message-send">
                          <textarea class="form-control" placeholder="Enter Message Here" style="height: 63px;"></textarea>
                         <a href="javascript:void(0);" class="new-resultss"><img src="{{url('public/website/images/inbox-event.png')}}"></a>
                        </div>
                    </div>
                </div>

                      
                
                        <div id="tab4" class="tab">
                            <p>Tab #2 content goes here!</p>
                            <p>Donec pulvinar neque sed semper lacinia. Curabitur lacinia ullamcorper nibh; quis imperdiet velit eleifend ac. Donec blandit mauris eget aliquet lacinia! Donec pulvinar massa interdum risus ornare mollis. In hac habitasse platea dictumst. Ut euismod tempus hendrerit. Morbi ut adipiscing nisi. Etiam rutrum sodales gravida! Aliquam tellus orci, iaculis vel.</p>
                        </div>

                        <div id="tab5" class="tab">
                            <p>Tab #3 content goes here!</p>
                            <p>Donec pulvinar neque sed semper lacinia. Curabitur lacinia ullamcorper nibh; quis imperdiet velit eleifend ac. Donec blandit mauris eget aliquet lacinia! Donec pulvinar massa interdum ri.</p>
                        </div>

                        <div id="tab6" class="tab">
                            <p>Tab #4 content goes here!</p>
                            <p>Donec pulvinar neque sed semper lacinia. Curabitur lacinia ullamcorper nibh; quis imperdiet velit eleifend ac. Donec blandit mauris eget aliquet lacinia! Donec pulvinar massa interdum risus ornare mollis. In hac habitasse platea dictumst. Ut euismod tempus hendrerit. Morbi ut adipiscing nisi. Etiam rutrum sodales gravida! Aliquam tellus orci, iaculis vel.</p>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
@endsection()