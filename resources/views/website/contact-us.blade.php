@extends('website.common')
@section('title', 'Motive')

@section('content')
<style type="text/css">
    button.new-homes.new {
    display: inline-block;
}
.form-group label {
    color: #a94442;
    font-size: 14px;
    text-transform: capitalize;
}
.alert.alert-success.alert-dismissible.text-center.alertz {
    width: 687px;
    margin-left: 227px;
}

.text-danger {
    line-height: 37px;
}
</style>
<div class="container">
    <div class="heading-top">
        <h1>contact us</h1>
    </div>
    @include('website.notifications_message')
    <div class="listen-music">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div id="tab1" class="tab active">
                    <form method="post" id="form_validate">
                    {{csrf_field()}}
                    <div class="form-reflex form-info-settings save-card contact-us">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="text" maxlength="30" name="name" class="form-control" placeholder="NAME">
                                    <span class="text-danger">{{$errors->first('name')}}</span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="text" maxlength="50" name="email" class="form-control" placeholder="EMAIL">
                                    <span class="text-danger">{{$errors->first('email')}}</span>

                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="text" maxlength="15" name="phone_number" class="form-control" placeholder="PHONE NUMBER">
                                    <span class="text-danger">{{$errors->first('phone_number')}}</span>

                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <!-- <label class="new-style">Comment</label> -->
                                    <textarea class="form-control" maxlength="200" name="comment" placeholder="COMMENT"></textarea>
                                    <span class="text-danger">{{$errors->first('comment')}}</span>

                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="text-center">
                                <button type="submit" class="new-homes new">SAVE</button>
                            </div>
                            
                               
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
@endsection()
@section('js')
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
<script>
    $(document).ready(function () {
    $('#form_validate')[0].reset();
});
</script>
@endsection()