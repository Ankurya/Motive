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
                            <h2>ADD GUEST</h2>
                        </div>
                     @include('website.notifications_message')
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Full Name</label>
                                <input type="text" name="name" maxlength="30" autocomplete="off" class="form-control" placeholder="FULL NAME">
                                <span class="text-danger error">{{$errors->first('name')}}</span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>EMAIL ADDRESS</label>
                                <input type="text" name="email" maxlength="30" autocomplete="off" class="form-control" placeholder="EMAIL ADDRESS ">
                                <span class="text-danger error">{{$errors->first('email')}}</span>
                              
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>ORGANISATION (OPTIONAL)</label>
                                <input type="text" name="organisation" maxlength="30" autocomplete="off" class="form-control" placeholder="ORGANISATION">
                                <span class="text-danger error">{{$errors->first('organisation')}}</span>
                            </div>
                        </div>
                        </div>
                        
                        <div class="new-formula-home-sd">
                            <button type="submit" class="new-homes">SAVE</button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
</div>
@endsection()