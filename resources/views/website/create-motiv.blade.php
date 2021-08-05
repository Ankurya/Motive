@extends('website.common')
@section('title', 'Motive')

@section('content')

 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.2/jquery.min.js"> </script>

<link href="{{url('public/website/css/motiv.css')}}" rel="stylesheet">
<section class="bg-color main-section">
    <div class="container">
        <div class="heading-top">
            <h1>Create motiv</h1>
        </div>
        @include('website.notifications_message')
        <div class="tabs">
            <div class="tab-content">
                <div id="tab1" class="tab active">
                    <div class="col-sm-8 col-sm-offset-2">
                        <div class="form-reflex create-moive alertsss">
                            <div class="row">
                                <form method="post" class="login_form" enctype="multipart/form-data" onSubmit="return validate_form1()" id="form_validate">
                                    {{csrf_field()}}
                                          <div class="form-group new">
                                            <div class="col-sm-4">
                                                <label>Select File<span style="color:Red;">*</span></label>
                                            </div>
                                            <div class="col-sm-7">
                                                <div class="field new" style="margin-bottom: 12px;">
                                                    <select id="upload_type" class="selectbox form-control" name="event_media_type">
                                                        <option value="1">IMAGE</option>
                                                        <option value="2">VIDEO</option>
                                                    </select>
                                                </div>
                                                <div class="new-check-get">
                                                <input type="checkbox" name="main" class="form-control right home " value="1" id="image_check1"><label class="check home home">Main</label>
                                            </div>
                                        </div>
                                    </div>
                                        <div class="col-md-12 no-padding">
                                            <div class="background-img" id="image">
                                                <div class="col-sm-4">
                                                    <label class="margin-bottom-15">Image</label>
                                                </div>
                                                
                                                <div class="col-sm-7">
                                                    <div class="form-group images-first">
                                                        <img onclick="$('#imgInp').click()" data-toggle="tooltip" title="Choose file" id="oldImg" src="{{url('public/website/images/no-image.png')}}">
                                                        <input type="file" id="imgInp" data-toggle="tooltip" title="Choose file" name="event_image_url">
                                                     <span class="text-danger error msz"></span>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        
                                        <div class="img" id="video"> 
                                               <div class="form-group new">
                                                <div class="col-sm-4 ">
                                                    <label>Add video</label>
                                                </div>
                                                <div class="col-sm-7" style="padding-right:14px">
                                                    <div class="col-sm-12 small padding-right-0 " style="padding-left:0;">
                                                        <div class="field">
                                                            <div class="">
                                                                <div class="upload-btn form-control">
                                                                    <span class="span" id="msg12">NO FILE CHOSEN</span>
                                                                    <label for="file-upload" class="up-file"><i class="fa fa-upload" aria-hidden="true"></i></label>
                                                                    <input type="file" name="event_video_url" id="file-upload5" class="fileinput">
                                                                </div>
                                                                <span class="text-danger error msz2"></span>
                                                            </div>
                                                            <p style="color: #fff; display: none;">( Only mp4 format allowed )</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                         <div class="form-group new">
                                            <div class="col-sm-4">
                                                <label>Select File</span></label>
                                            </div>
                                            <div class="col-sm-7">
                                                <div class="field new" style="margin-bottom: 12px;">
                                                    <select id="upload_type1" class="selectbox form-control" name="event_media_type1">
                                                        <option value="3">IMAGE</option>
                                                        <option value="4">VIDEO</option>
                                                    </select>
                                                </div>
                                                    <div class="new-check-get">
                                                    <input type="checkbox" name="main" class="form-control right home " value="2" id="image_check"><label class="check home home">Main</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 no-padding">
                                            <div class="background-img" id="image1">
                                                <div class="col-sm-4">
                                                    <label class="margin-bottom-15">Image</label>
                                                </div>
                                                
                                                <div class="col-sm-7">
                                                    <div class="form-group images-first">
                                                        <img onclick="$('#imgInp1').click()"  data-toggle="tooltip" title="Choose file" id="oldImg1" src="{{url('public/website/images/no-image.png')}}">
                                                        <input type="file" id="imgInp1"  data-toggle="tooltip" title="Choose file" name="event_image_url2">
                                                     <span class="text-danger error err"></span>
                                                     <span class="text-danger error msz1"></span>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        
                                        <div class="img" id="video1">
                                               <div class="form-group new">
                                                <div class="col-sm-4 ">
                                                    <label>Add video</label>
                                                </div>
                                                <div class="col-sm-7" style="padding-right:14px">
                                                    <div class="col-sm-12 small padding-right-0 " style="padding-left:0;">
                                                        <div class="field">
                                                            <div class="">
                                                                <div class="upload-btn form-control">
                                                                    <span class="span" id="msg12">NO FILE CHOSEN</span>
                                                                    <label for="file-upload" class="up-file"><i class="fa fa-upload" aria-hidden="true"></i></label>
                                                                    <input type="file" name="event_video_url2" id="file-upload6" class="fileinput">
                                                                </div>
                                                                <span class="text-danger error msz3"></span>
                                                                
                                                            </div>
                                                            <p style="color: #fff; display: none;">( Only mp4 format allowed )</p>
                                                        </div>
                                                    </div>
                                                     <span class="text-danger error err"></span>
                                                </div>
                                            </div>
                                        </div>
                                       
                                            <div class="form-group new">
                                                <div class="col-sm-4">
                                                    <label>Event Name<span style="color:Red;">*</span></label>
                                                </div>
                                                <div class="col-sm-7">
                                                    <input type="text" name="event_name" value="{{old('event_name')}}" placeholder="EVENT NAME" class="form-control">
                                                    <span class="text-danger error">{{$errors->first('event_name')}}</span>
                                                </div>
                                            </div>
                                            <div class="form-group new">
                                                <div class="col-sm-4">
                                                    <label>Location<span style="color:Red;">*</span></label>
                                                </div>
                                                <div class="col-sm-7">
                                                    <input type="text" name="location" id="location" value = "{{old('location')}}" placeholder="LOCATION" class="form-control">
                                                    <span class="text-danger error">{{$errors->first('location')}}</span>
                                                    <input type="hidden" id="lat" name="lat" value="{{old('lat')}}">
                                                    <input type="hidden" id="long" name="long" value="{{old('long')}}">
                                                </div>
                                            </div>
                                            <div class="form-group new">
                                                <div class="col-sm-4">
                                                    <label>Start Date & Time<span style="color:Red;">*</span></label>
                                                </div>
                                                <div class="col-sm-7 date">
                                                  <div class="form-group">
                                                <div class='input-group date' id='datetimepicker1'>
                                                  <input  placeholder="START DATE & TIME" value="{{old('start_date')}}" id="start_date" autocomplete="off" name="start_date" class="form-control" />
                                                  <span class="input-group-addon">
                                                  <span class="add-on"><img src="{{url('public/website/images/calender.png')}}"></span>
                                                  </span>
                                                </div>
                                                  <span class="text-danger error" id="start_date-error">{{$errors->first('start_date')}}</span>
                                              </div>
                                                </div>
                                            </div>
                                            <div class="form-group new">
                                                <div class="col-sm-4">
                                                    <label>End Date & Time<span style="color:Red;">*</span></label>
                                                </div>
                                                <div class="col-sm-7 date">
                                                   <div class="form-group">
                                                <div class='input-group date' id='datetimepicker2'>
                                                  <input type='text' autocomplete="off" value="{{old('end_date')}}" placeholder="END DATE & TIME" name="end_date" class="form-control"  />
                                                  <span class="input-group-addon">
                                                  <span class="add-on"><img src="{{url('public/website/images/calender.png')}}"></span>
                                                  </span>
                                                </div>
                                                  <span class="text-danger error enddate">{{$errors->first('end_date')}}</span>
                                              </div>
                                                </div>
                                            </div>
                                            <div class="form-group new">
                                                <div class="col-sm-4">
                                                    <label>Renew Listing<span style="color:Red;">*</span></label>
                                                </div>
                                                <div class="col-sm-7">
                                                    <select class="form-control selectbox" name="repeat_interval">
                                                        <option value="">SELECT RENEW</option>
                                                        <option value="one_day">ONE TIME</option>
                                                        <option value="weekly">WEEKLY</option>
                                                        <option value="2_weekly">FORTNIGHTLY</option>
                                                        <option value="monthly">MONTHLY</option>
                                                    </select>
                                                    <span class="text-danger error">{{$errors->first('repeat_interval')}}</span>
                                                </div>
                                            </div>
                                            <div class="form-group new">
                                                <div class="col-sm-4">
                                                    <label>Website URL</label>
                                                </div>
                                                <div class="col-sm-7">
                                                    <input type="text" placeholder="WEBSITE URL" value="{{old('website_url')}}" name="website_url" class="form-control">
                                                    <span class="text-danger error">{{$errors->first('website_url')}}</span>
                                                </div>
                                            </div>
                                            <div class="form-group new">
                                                <div class="col-sm-4">
                                                    <label>Phone Number</label>
                                                </div>
                                                <div class="col-sm-7">
                                                    <input type="text" placeholder="PHONE NUMBER" value="{{old('phone_number')}}" name="phone_number" class="form-control">  
                                                    <span class="text-danger error">{{$errors->first('phone_number')}}</span>
                                                </div>
                                            </div>
                                            <div class="form-group  new margin-bottom-45">
                                                <div class="col-sm-4">
                                                    <label>Description<span style="color:Red;">*</span></label>
                                                </div>
                                                <div class="col-sm-7">
                                                    <textarea class="form-control" placeholder="DESCRIPTION" maxlength="120" 
                                                    name="descriptions"></textarea>
                                                    <span class="text-danger error">{{$errors->first('descriptions')}}</span>
                                                </div>
                                            </div>
                                            <div class="form-group new margin-bottom-45">
                                                <div class="col-sm-4">
                                                    <label>Interests</label>
                                                </div>
                                                <div class="col-sm-7">
                                                        <a href="javascript:;" data-toggle="modal" data-target="#interest">
                                                            <img src="{{url('public/website/images/first.png')}}"></a>
                                                        </label>
                                                        <span class="text-danger error">{{$errors->first('public')}}</span>
                                                    </div>
                                                </div>
                                                <div class="form-group new margin-bottom-20">
                                                    <div class="col-sm-4">
                                                        <label>Invite users</label>
                                                    </div>
                                                    <div class="col-sm-7">
                                                        <div class="new-jackets" style="width:45px;">
                                                          <a href="javascript:void(0); class="btn btn-primary" data-toggle="modal" data-target="#myModal-new"><img src="{{url('public/website/images/user-circle.png')}}"></a>
                                                      </div>
                                                  </div>
                                              </div>
                                              <div class="col-md-12 margin-top-30">
                                                <div class="col-md-12 col-xs-12 text-center first">
                                                    <button type="submit" class="btn btn-default register" >SUBMIT </button>
                                                </div>
                                            </div>


                                            <div id="interest" class="modal fade" role="dialog">
                                              <div class="modal-dialog">

                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                  <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"><img src="{{url('public/website/images/popup-cross.png')}}"> </button>
                                                    <h4 class="modal-title">Select your interests</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                      <div class="col-sm-12">
                                                        <div class="user-wrap">
                                                          <input type="checkbox" id="CheckAll" name="">
                                                          <label class="check">All</label>
                                                      </div>
                                                  </div>
                                              </div>
                                              <div class="first-content">
                                                  <div class="row">
                                                    <div class="col-sm-4">
                                                      <div class="form-group">
                                                       <a href="javascript:void(0);" data-toggle="modal" data-target="#interest1"><input type="checkbox" name="" class="checkBoxClass music_int1"><label class="check animal new">
                                                        <img src="{{url('public/website/images/jazz.png')}}">music</label></a>
                                                    </div>
                                                </div>
                                                @foreach($public_interest as $public)
                                                @php 
                                                $image = $public->image ? url('/public') . DIRECTORY_SEPARATOR . $public->image : url('/') . DIRECTORY_SEPARATOR . env('ADMIN_IMAGES_PATH') . 'user.png';
                                                @endphp
                                                <div class="col-sm-4">
                                                  <div class="form-group">
                                                   <input type="checkbox" name="public[]" value="{{$public->id}}" class="checkBoxClass public_int"><label class="check Community new"><img src="{{$image}}">{{$public->name}}</label>
                                               </div>
                                           </div>
                                           @endforeach()

                                       </div>

                                   </div>

                                   <div class="new-button_right">
                                    <div class="col-md-12">
                                      <button type="button" class="btn btn-default register yes skip" data-dismiss="modal">Skip</button>
                                  </div>
                              </div>
                          </div>
                      </div>

                  </div>
              </div>
              <div id="interest1" class="modal fade" role="dialog">
                <div class="modal-dialog">

                  <!-- Modal content-->
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal"><img src="{{url('public/website/images/popup-cross.png')}}"> </button>
                      <h4 class="modal-title">Select  music interests</h4>
                  </div>
                  <div class="modal-body">
                      <div class="row">
                        <div class="col-sm-12">
                          <div class="user-wrap">
                            <input type="checkbox" id="CheckAlls" name="">
                            <label class="check">All</label>
                        </div>
                    </div>
                </div>
                <div class="first-content">

                    <div class="row">
                      @foreach($music_interest as $music)
                      @php 
                      $image = $music->image ? url('/public') . DIRECTORY_SEPARATOR . $music->image : url('/') . DIRECTORY_SEPARATOR . env('ADMIN_IMAGES_PATH') . 'user.png';
                      @endphp
                      <div class="col-sm-4">
                        <div class="form-group">
                          <input type="checkbox"  name="music[]" value="{{$music->id}}" class="checkBoxClass music_int1" ><label class="check animal new"><img src="{{$image}}">{{$music->name}}</label>
                      </div>
                  </div>  
                  @endforeach
              </div>



              <div class="new-button_right">

                  <div class="col-md-12">
                      <button type="button" class="btn btn-default register yes skip1" data-dismiss="modal">Skip</button>
                  </div>
              </div>

          </div>
      </div>
  </div>

</div>
</div>
<!-- Button to Open the Modal -->


<!-- The Modal -->
<div class="modal" id="myModal-new">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><img src="{{url('public/website/images/popup-cross.png')}}"> </button>
          <h4 class="modal-title">Select Users</h4>
      </div>

      <!-- Modal body -->
      <div class="modal-body ">
                        <div id="tab1" class="tab active">
                          <div class="col-md-10 col-md-offset-1">
                            <div class="form-reflex form-info-settings adit">
                                <div class="row">
                                <div class="col-md-12">
                                   
                                </div>
                                @if($users && !empty($users) && count($users) > 0)
                                    @foreach($users as $user)
                                     <div class="col-md-12">
                                        <div class="form-group">
                                          <div class="user-wrap images-new img-icon">
                                            @if($user->image_url) 
                                              <img src="{{$user->image_url}}">
                                              @else 
                                                 <img src="{{url('public/website/images/licon-yellow.png')}}" style="width: 40px;height: 40px;">
                                                @endif()
                                                    <label>{{$user->name}}</label>
                                            <input type="checkbox" value="{{$user->id}}" name="users[]" class="checkBoxClass">
                                        <label class="check"></label>
                                    </div>
                                        </div>
                                    </div>
                                    @endforeach
                                @else 
                                <h1 style="font-size:30px; font-weight: 500; color: #fff;text-align:center; position: absolute;top: 15%;left: 50%;transform: translate(-50% ,-50%);}">No Friend in list</h1>
                                @endif 
                            </div>
                        </div>
                    </div>
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
</section>


@endsection()
@section('js')
<script type="text/javascript">
        $(function () {
        var dt = new Date();
        dt.setMinutes( dt.getMinutes() + 10 );
        $('#datetimepicker1').datetimepicker({
            format: 'DD-MM-YYYY LT',
            minDate: dt,

        });

        $('#datetimepicker2').datetimepicker({
            format: 'DD-MM-YYYY LT',
            minDate: dt,
        });

        $('#datetimepicker3').datetimepicker({
            format: 'LT'
        });
        $('#datetimepicker3').datetimepicker({
            format: 'LT'
        });


    });
      
</script>
<script>
    $(document).ready(function(){
        $('#datetimepicker1').on('keydown', function(e){
            e.preventDefault();
        });
    });

    $(document).ready(function(){
        $('#datetimepicker2').on('keydown', function(e){
            e.preventDefault();
        });
    });



//     $(".register").click(function() {
//         var start = $('#datetimepicker1 input').val();
//         var end = $('#datetimepicker2 input').val();

//         if(end <= start) {
//             $('.enddate').text('End date should not be less than start date or equal.');
//             // return false;
//         } else{
//             $('.enddate').text('');
//         }
// });

</script>


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
  function readURL1(input) {
    if (input.files && input.files[0]) {

      var type = input.files[0].type;

      if(type == 'image/jpeg' || type == 'image/png'){

        var reader = new FileReader();

        reader.onload = function (e) {
          $('#oldImg1').attr('src', e.target.result);
      }

      reader.readAsDataURL(input.files[0]);

  } else {
    $('.msz1').text('Only Jpeg and Png format is allowed.');
}
}
}

$("#imgInp1").change(function(){
    readURL1(this);
});
</script>






<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAbgWTyuXJbZtehcat3VvAsHE3FyapBVDs&libraries=places&callback=initialize" async defer></script>

<script>
function initialize() {
  var autocomplete = new google.maps.places.Autocomplete(
  (document.getElementById('location')), {
    types: ['geocode']
  });
  google.maps.event.addListener(autocomplete, 'place_changed', function () {
    var place = autocomplete.getPlace();
    var lat = place.geometry.location.lat();
    var long = place.geometry.location.lng();
    $('#lat').val(lat);
    $('#long').val(long);
    // console.log(lat + ", " + long);
  });
}
</script>


<script type="text/javascript">
  $(document).ready(function() {
    $("ul.nav.navbar-nav li").click(function () {
      $("ul.nav.navbar-nav li").removeClass("active");
      $(this).addClass("active");   
      
  });
    $("#CheckAll").click(function () {

      $(".public_int").prop('checked', $(this).prop('checked'));
      $(".music_int").prop('checked', $(this).prop('checked'));
      $(".music_int1").prop('checked', $(this).prop('checked'));
      $("#CheckAlls").prop('checked', $(this).prop('checked'));

  });

});
</script>
<script type="text/javascript">
  $(document).ready(function() {
    $("ul.nav.navbar-nav li").click(function () {
      $("ul.nav.navbar-nav li").removeClass("active");
      $(this).addClass("active");   
      
  });
    $("#CheckAlls").click(function () {
      $(".music_int").prop('checked', $(this).prop('checked'));
      $(".music_int1").prop('checked', $(this).prop('checked'));

  });

});


</script>

<script>
  $(document).ready(function() {

    $("#file-upload5").change(function(event){
        if($(this).val() != ''){

            $(this).prev().prev().text(event.target.files[0].name)
        }
        // console.log(event.target.files[0].type)
        var type = event.target.files[0].type;
        if(type != 'video/mp4'){
           $('.msz2').text('Only mp4 format allowed.'); 
           $("#file-upload5").val('');
        }else {
            $(".msz2").text('')
        }
    });

    $("#file-upload6").change(function(event){
        if($(this).val() != ''){
            $(this).prev().prev().text(event.target.files[0].name)
        }
        // console.log(event.target.files[0].type)
        var type = event.target.files[0].type;
        if(type != 'video/mp4'){
           $('.msz3').text('Only mp4 format allowed.'); 
           $("#file-upload5").val('');
        }else {
            $(".msz3").text('')
        }
    });

    $(".public_int").click(function(){
      if(!$(this).prop("checked")){
        $("#CheckAll").prop("checked",false);
        }
    });

    $(".music_int").click(function(){
      if(!$(this).prop("checked")){
        $("#CheckAlls").prop("checked",false);
       }
    });

    $(".music_int").click(function(){

        let status = false;
        $(".music_int").each(function(){
          if($(this).prop("checked")){
            status = true;
        }

    });

    if(status) {
        $(".music_int1").prop("checked",true);
    }else {
        $(".music_int1").prop("checked",false);
    }

    });

    $(".skip").click(function(){
        // alert('weew');
        $("#CheckAll").prop("checked",false);
        $(".public_int").prop("checked",false);
        $(".music_int").prop("checked",false);
        $(".music_int1").prop("checked",false);
        $("#CheckAlls").prop("checked",false);
        
    });

    $(".skip1").click(function(){
        // alert('weew');
        $("#CheckAll").prop("checked",false);
        // $(".public_int").prop("checked",false);
        $(".music_int").prop("checked",false);
        $(".music_int1").prop("checked",false);
        $("#CheckAlls").prop("checked",false);
        
    });
});
</script>

<script type="text/javascript">
    
    if($('#upload_type').val()==2){
        $('#video').css("display","");
        $('#image').css("display","none");
    }else if($('#upload_type').val()==1){
        
        $('#image').css("display","");
        $('#video').css("display","none");
        
    }
    $('#upload_type').change(function(){
        // alert('dsd');
        if($(this).val()==1){
            $('#image').css("display","");
            $('#video').css("display","none");
        }else{
            $('#video').css("display","");
            $('#image').css("display","none");
        }
    });

    if($('#upload_type1').val()==4){
        $('#video').css("display","");
        $('#image').css("display","none");
    }else if($('#upload_type1').val()==3){
        
        $('#image1').css("display","");
        $('#video1').css("display","none");
        
    }

    $('#upload_type1').change(function(){
        // alert('dd')
        if($(this).val()==3){
            $('#image1').css("display","");
            $('#video1').css("display","none");
        }else{
            $('#video1').css("display","");
            $('#image1').css("display","none");
        }
    });



function validate_form1(){
    
    let img1 =  $("#imgInp").val()
    // alert(img1);
    let img2 =  $("#imgInp1").val()
    let video1 =  $("#file-upload5").val()
    let video2 =  $("#file-upload6").val()

    if(img1.length == 0 && img2.length == 0 && video1.length == 0 && video2.length == 0 ){
        // alert('dd')
        $('.err').text('Please select atleast image or video.');
        return false;
    }else if(!$("#image_check").prop("checked") && !$("#image_check1").prop("checked") ){
        $('.err').text('Please select main.');
        return false

    }
}

$("#image_check").click(function(){
    if($(this).prop("checked")){
        $("#image_check1").attr("checked",false)
    }
})
$("#image_check1").click(function(){
    if($(this).prop("checked")){
        $("#image_check").attr("checked",false)
    }
})

$("#imgInp,#imgInp1,#file-upload5,#file-upload6").change(function() {
    if($(this).val().length > 0) {
        $('.err').text('');
    } else{
        $('.err').text('Please select atleast image or video.');
    }
});

$("#image_check,#image_check1").change(function() {
    if($(this).val().length > 0) {
        $('.err').text('');
    } else{
        $('.err').text('Please select main.');
    }
});


</script>

<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
<script>
    $(document).ready(function () {
        jQuery.validator.addMethod("greaterThan", 
            function(value, element, params) {
                if (!/Invalid|NaN/.test(new Date(value))) {
                    return new Date(value) > new Date($(params).val());
                }
                return isNaN(value) && isNaN($(params).val()) 
                    || (Number(value) > Number($(params).val())); 
            },'End date should not be less than start date or equal.');
    $('#form_validate').validate({ // initialize the plugin
        rules: {
            event_name: {
                required: true
            },
            location: {
                required: true
            },
            start_date: {
                required: true
            },
            end_date: {
                required: true,
                greaterThan: "#start_date" 
            },
            descriptions: {
                required: true
            },
            repeat_interval: {
                required: true
            },
            website_url: {
                url: true
            },
        },

        messages:{
            event_name:{  
              required:'Please enter event name.'
            },
            location:{  
              required:'Please enter location.'
            },
            start_date:{  
              required:'Please select start date.'
            },
            end_date:{  
              required:'Please select end date.'
            },
            descriptions:{  
              required:'Please enter description.'
            },
            repeat_interval:{  
              required:'Please select renew listing.'
            },

            website_url:{  
              url:'Please enter a valid website url.'
            },
            
          }
    });
});
</script>



</body>

</html>

@endsection()