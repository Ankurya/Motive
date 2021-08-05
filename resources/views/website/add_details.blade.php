@extends('website.common')
@section('title', 'Add Card')

@section('content')
<style type="text/css">
    .forget-new {
    float: right;
    color: #F9D367;
}

.forget-new a {
    color: #F9D367;
}
.text-danger{
    line-height:34px;
}

 .profile {
    text-align: center;
    margin: 0 auto 0px;
    width: 148px;
    position: relative;
        display: inline-block;

}
.profile span.pro-img {
    border-radius: 50%;
    display: inline-block;
    overflow-x: hidden;
        vertical-align: middle;

}

.profile span.pro-img img {
      top: 0;
    left: 0;
    right: 0;
    margin: auto;
    height:51px;
    width: 80px;
}

.profile span.camera {
       display: block;
    height: 65px;
    width: 65px;
    border-radius: 50%;
    position: absolute;
    bottom: -21px;
    right: 27px;
}
.profile span.camera a {
    position: relative;
    top: 15px;
    left: 0;
    right: 0;
    border: 0px solid;
    margin: auto;
    display: block;
}
.profile span.camera a input {
    opacity: 0;
    position: absolute;
    height: 100%;
   width:100%;
    cursor: pointer;
}
</style>


        <div class="row new-blue">
            <div class="col-md-6 col-md-offset-3">
                <div class="form-content register less">
                    <div class="form-long_content">


                        
                        <div class="form-info-settings lvert">

                        <form method="post" action="{{url('website/add-details-insert')}}" enctype="multipart/form-data">
                            {{csrf_field()}}
                            <div class="top-build-info">
                               
                                <div class="new-small-logo">
                            <img src="{{url('public/website/images/logo-inner.png')}}">
                             <h2>Add Bank Details</h2>

                            @if(Session::has('message'))
					            <p class="alert alert-success">{{ Session::get('message') }}</p>
				            @endif
                            @if(Session::has('danger'))
                                <p class="alert alert-danger">{{ Session::get('danger') }}</p>
                            @endif
                            </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Card Holder Name</label>
                                        <input class="form-control" name="card_name" id="card_name" type="text" autocomplete="off" >
                                        <span class="text-danger error">{{$errors->first('card_name')}}</span>
                                      
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="ccnumber">Card Number</label>
                                        <input class="form-control" name="card_number" id="card_number" type="text" maxlength="16" autocomplete="email">
                                        <span class="text-danger error">{{$errors->first('card_number')}}</span>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="ccnumber">Expiry/Validity Date</label>
                                        <input class="form-control" maxlength="5" name="expiry_date" id="expiry_date" type="text">
                                        <span class="text-danger error">{{$errors->first('expiry_date')}}</span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="ccnumber">ID</label>
                                        <div class="profile">
                                        <span class="pro-img">
                                           <img onclick="$('#imgInp').click()" id="oldImg" src="{{url('public/img/id.png')}}" style="cursor: pointer;" accept="image/*">
                                            </span>
                                          <span class="camera">
                                            <a href="javascript:;">
                                              <input type="file" id="imgInp" name="image">                                             
                                            </a>
                                          </span>
                                        </div> 
                                    </div>
                                        <span class="text-danger error msz">{{$errors->first('imgInp')}}</span>
                                </div>
                                <input  class="form-control" type="hidden" name="user_id" id="user_id"  type="text" value="{{$user_id }}"/>   

                                
                                </div>
                                <div class="new-formula-home-sd">
                                    <input type="submit" class="new-homes" value="Submit">
                                </div>
                            </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        

  @endsection()
@section('js')
<script type="text/javascript">
  function readURL(input) {
    if (input.files && input.files[0]) {

      var type = input.files[0].type;

      if(type == 'image/jpeg' || type == 'image/png'){

        var reader = new FileReader();

        reader.onload = function (e) {
          $('#oldImg').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);

      } else {
        $('.msz').text('Only Jpeg and Png format is allowed.');
      }
    }
  }

  $("#imgInp").change(function(){
    readURL(this);
  });


</script>

<script type="text/javascript">
    $(function() {
      setTimeout(function(){
          $(".alert").hide();
          $(".error").hide();
      }, 5000);
  });
</script>
@endsection







