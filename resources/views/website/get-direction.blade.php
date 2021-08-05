@extends('website.common')
@section('title', 'Motive')

@section('content')
<div class="container">
    <div class="heading-top">
        <h1>get direction</h1>
    </div>
    <div class="tabs">
        <div class="new-tabs">
            <ul class="tab-links">
                <li class="active"><a href="#tab1">Now</a></li>
                <li><a href="#tab2">future</a></li>
                <li><a href="#tab3">favourites</a></li>
            </ul>
        </div>

        <div class="col-md-8 col-md-offset-2">
            <div id="tab1" class="tab active">
                <div class="new-format-again-website-view text-center">
                    <div class="i-frame">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3431.7875575098938!2d76.78128141445976!3d30.66811019579664!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390feb793d27ddbd%3A0x3edec029a5d774f5!2sMohali+International+Airport!5e0!3m2!1sen!2sin!4v1554993607584!5m2!1sen!2sin" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
                        <a href="javascript:void(0)" class="new-homes"><img src="{{url('public/website/images/direction-button.png')}}">get direction</a>

                    </div>


                </div>
                <div id="tab2" class="tab">
                    <div class="new-format-again-website-view text-center">
                      <div class="i-frame">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3431.7875575098938!2d76.78128141445976!3d30.66811019579664!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390feb793d27ddbd%3A0x3edec029a5d774f5!2sMohali+International+Airport!5e0!3m2!1sen!2sin!4v1554993607584!5m2!1sen!2sin" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
                        <a href="javascript:void(0)" class="new-homes"><img src="{{url('public/website/images/direction-button.png')}}">get direction</a>
                    </div>


                </div>
                <div id="tab3" class="tab">
                    <div class="new-format-again-website-view text-center">
                      <div class="i-frame">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3431.7875575098938!2d76.78128141445976!3d30.66811019579664!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390feb793d27ddbd%3A0x3edec029a5d774f5!2sMohali+International+Airport!5e0!3m2!1sen!2sin!4v1554993607584!5m2!1sen!2sin" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
                        <a href="javascript:void(0)" class="new-homes"><img src="{{url('public/website/images/direction-button.png')}}">get direction</a>
                    </div>


                </div>
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