@extends('website.common')
@section('title', 'Motive')

@section('content')
<style type="text/css">
    .tab-content {
    padding: 0;
    margin: 0;
}
</style>
<div class="new-change change">
    <div class="container">
        <div class="heading-top">
            @include('website.notifications_message')
            <h1>change password</h1>
        </div>
        <div class="tabs">
            <!-- <ul class="tab-links">
                <li class="active"><a href="#tab1">Now</a></li>
                <li><a href="#tab2">future</a></li>
                <li><a href="#tab3">favourites</a></li>
            </ul> -->

            <div class="tab-content">
                <div id="tab1" class="tab active">
                  <div class="col-md-10 col-md-offset-1">
                     <div class="form-reflex form-info-settings">
                        <form method="post">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Old Password</label>
                                    <input type="password" name="old_password" class="form-control" placeholder="OLD PASSWORD">
                                    <span class="text-danger error">{{$errors->first('old_password')}}</span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>New Password</label>
                                    <input type="password" name="new_password" class="form-control" placeholder="NEW PASSWORD">
                                    <span class="text-danger error">{{$errors->first('new_password')}}</span>

                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Confirm Password</label>
                                    <input type="password" name="confirm_password" class="form-control" placeholder="CONFIRM PASSWORD">
                                    <span class="text-danger error">{{$errors->first('confirm_password')}}</span>

                                </div>
                            </div>
                            <div class="col-md-12">
                               <div class="new-formula-home-sd">
                                <button type="submit" class="new-homes">UPDATE</button>
                            </div>
                        </div>
                    </div>
                   </form> 
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
</div>
@endsection()