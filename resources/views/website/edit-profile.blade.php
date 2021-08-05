@extends('website.common')
@section('title', 'Motive')

@section('content')
<style type="text/css">
  .profile {
    text-align: center;
    margin: 0 auto 0px;
    width: 170px;
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
    height:80px;
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
span.text-danger.error {
    display: inline-block;
    text-align: center;
}
.top-build-info .profile-pic h3{
      margin-right: 57px;

}
.home-error {
    text-align: center;
    margin-top: 18px;
}
.new-select-info p:hover{
  color: #ffdc73;
  
}

.heading-top {
    padding: 76px 0 0;
}

span.text-danger.error.msz {
    float: right;
}
</style>
<section class="bg-color main-section"> 
    <div class="container">
        <div class="heading-top">
            <h1>edit profile</h1>
        </div>
        <div class="tabs">
          <form method="post" enctype="multipart/form-data">
            {{csrf_field()}} 
            <div class="tab-content">
                <div id="tab1" class="tab active">
                  <div class="col-md-6 col-md-offset-3">
                     <div class=" edit-profile">
                        <div class="profile-pic new">
                          <h3>profile picture</h3>
                          <div class="profile">
                            <span class="pro-img">
                              @if($user->image_url)
                               <img onclick="$('#imgInp').click()" id="oldImg" src="{{$user->image_url}}" accept="image/*">
                               @else 
                               <img onclick="$('#imgInp').click()" id="oldImg" src="{{url('public/website/images/user-news.png')}}" accept="image/*">
                               @endif
                                </span>

                              <span class="camera">
                                <a href="javascript:;">
                                  <input type="file" id="imgInp" name="image">
                                    <img src="{{url('public/website/images/new.png')}}">
                                 
                                </a>
                              </span>
                              @if($user->image_url)
                              <div class="remove_pic">
                              <a href="{{url('website/remove-profile',$user->id)}}">
                                <figure>
                                  <i class="fa fa-times" aria-hidden="true"></i>
                                </figure>
                              </a>     
                              </div>
                              @else
                              @endif
                            </div> 

                          <div class="home-error">           
                            <span class="text-danger error msz">{{$errors->first('image')}}</span>
                          </div>
                        </div>
                        <div class="row">
                            <form>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="margin-bottom-15 margin-top-25">Name</label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                      <input type="text" name="name" class="form-control" value="{{$user->name}}">
                                      <span class="text-danger error">{{$errors->first('name')}}</span>
                                  </div>
                              </div>
                              <div class="col-md-12">
                                <div class="form-group">
                                    <label class="margin-bottom-15 margin-top-25" >Email Address</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                  <input type="text" name="email" class="form-control" value="{{$user->email}}" readonly="readonly">
                                    <span class="text-danger error">{{$errors->first('email')}}</span>
                              </div>
                          </div>
                          <div class="col-md-12">
                            <div class="form-group">
                                <label class="margin-bottom-15 margin-top-25">Phone Number</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                              <input type="text" name="phone_number" class="form-control" value="{{$user->phone_number}}">
                              <span class="text-danger error">{{$errors->first('phone_number')}}</span>
                          </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group">
                            <label class="margin-bottom-15 margin-top-25">About</label>
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="form-group">
                         <textarea class="height-115 form-control" name="about">{{$user->about_me}}</textarea>
                         <span class="text-danger error">{{$errors->first('about')}}</span>
                     </div>
                 </div>
                 
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group margin-top-70 text-center">
                  <button type="submit" class="btn btn-default register">SAVE</button>
              </div>
          </div>
      </form>
  </div>
</div>
</section>
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
@endsection()