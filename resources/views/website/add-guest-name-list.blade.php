@extends('website.common')
@section('title', 'Motive')

@section('content')
<div class="row new-blue">
    <div class="col-md-6 col-md-offset-3">
        <div class="form-content register less">
            <div class="form-long_content">
                
                <div class="form-info-settings lvert">
                    <form method="post" class="login_form">
                    {{csrf_field()}}
                    <div class="top-build-info">
                        <div class="new-small-logo">
                            <h2>CREATE GUEST LIST</h2>
                        </div>
                     @include('website.notifications_message')
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <form method="post">
                                {{csrf_field()}}
                            <div class="form-group">
                                <label>Guestlist Name</label>
                                <input type="text" name="name" autocomplete="off" class="form-control" placeholder="GUESTLIST NAME">
                                <span class="text-danger error">{{$errors->first('name')}}</span>
                            </div>
                        </div>
                        
                    </div>
                        
                    <div class="new-formula-home-sd">
                        <button type="submit" class="new-homes">CREATE</button>
                    </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
</div>
@endsection()