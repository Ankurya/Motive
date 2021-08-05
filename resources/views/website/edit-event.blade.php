@extends('website.common')
@section('title', 'Motive')

@section('content')
<style>
.create-moive form video.create {
    height: 178px;
    width: 100%;
}
</style>
<link href="{{url('public/website/css/motiv.css')}}" rel="stylesheet">
<section class="bg-color main-section motive-page-one">
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
                                        <?php 
                                            if($event->main == 1) {
                                                if($event->event_media_type == 1) {
                                                    $selected = 'selected';
                                                    $select = '';
                                                    $image = '';
                                                    $video = '';
                                                } else {
                                                    $selected = '';
                                                    $select = 'selected';
                                                    $image = '';
                                                    $video = '';
                                                }

                                                if($event->event_image_url2 == '') {
                                                    $image = '';
                                                    $video = 'selected';
                                                    $selected = '';
                                                    $select = '';
                                                } 
                                            } else {
                                                if($event->event_media_type == 1) {
                                                    $image = 'selected';
                                                    $video = '';
                                                    $selected = '';
                                                    $select = '';
                                                } else {
                                                    $image = '';
                                                    $video = 'selected';
                                                    $selected = '';
                                                    $select = '';
                                                }

                                                if($event->event_image_url == '') {
                                                    $image = '';
                                                    $video = '';
                                                    $selected = '';
                                                    $select = 'selected';
                                                }  
                                                
                                            }

                                            

                                        ?>
                                        <div class="col-sm-7">
                                            <div class="field new" style="margin-bottom: 12px;">
                                                <select id="upload_type" class="selectbox form-control" name="event_media_type">
                                                    <option {{$selected}} value="1">IMAGE</option>
                                                    <option {{$select}} value="2">VIDEO</option>
                                                </select>
                                            </div>
                                            <?php 
                                                if($event->main == 1) {
                                                    $check = 'checked';
                                                    $checked = '';
                                                } else {
                                                    $check = '';
                                                    $checked = 'checked';
                                                }
                                            ?>
                                            <div class="new-check-get">
                                                <input type="checkbox" name="main" class="form-control right home" value="1" id="image_check1" {{$check}}><label class="check home home">Main</label>
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
                                                    <img onclick="$('#imgInp').click()" data-toggle="tooltip" title="Choose file" id="oldImg" src="@if($event->event_image_url){{$event->event_image_url}} @else {{url('public/website/images/no-image.png')}} @endif">
                                                    <input type="file" id="imgInp" data-toggle="tooltip" title="Choose file" name="event_image_url" value="{{$event->event_image_url}}">
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
                                                @if($event->event_video_url)
                                                <video class="create" controls="" width="100%" height="200px;" style="margin-top: 12px;">
                                                    <source src="{{$event->event_video_url}}" type="video/mp4">
                                                </video>
                                                @else 
                                                @endif
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
                                                <option {{$image}} value="3">IMAGE</option>
                                                <option {{$video}} value="4">VIDEO</option>
                                            </select>
                                        </div>
                                        <div class="new-check-get">
                                            <input type="checkbox" name="main" class="form-control right home " value="2" id="image_check" {{$checked}}><label class="check home home">Main</label>
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
                                                <img onclick="$('#imgInp1').click()"  data-toggle="tooltip" title="Choose file" id="oldImg1" src="@if($event->event_image_url2){{$event->event_image_url2}} @else {{url('public/website/images/no-image.png')}} @endif">
                                                <input type="file" id="imgInp1"  data-toggle="tooltip" title="Choose file" name="event_image_url2" value="{{$event->event_image_url2}}">
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
                                        @if($event->event_video_url2)
                                        <video class="create" controls="" width="100%" height="200px;" style="margin-top: 12px;">
                                            <source src="{{$event->event_video_url2}}" type="video/mp4">
                                        </video>
                                        @else 
                                        @endif
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
                                    <label>Background image</label>
                                </div>
                                <div class="col-sm-7">
                                    <div class="form-group images-first">
                                        <img onclick="$('#imgInp3').click()"  data-toggle="tooltip" title="Choose file" id="oldImg3" src="@if($event->primary_image){{$event->primary_image}} @else {{url('public/website/images/no-image.png')}} @endif">
                                        <input type="file" id="imgInp3"  data-toggle="tooltip" title="Choose file" value="{{$event->primary_image}}" name="primary_image">
                                        <span class="text-danger error msz4"></span>
                                    </div>
                                </div>

                            </div>

                            <div class="form-group new">
                                <div class="col-sm-4">
                                    <label>Event Name<span style="color:Red;">*</span></label>
                                </div>
                                <div class="col-sm-7">
                                    <input type="text" name="event_name" value="{{$event->event_name}}" placeholder="EVENT NAME" class="form-control">
                                    <span class="text-danger error">{{$errors->first('event_name')}}</span>
                                </div>
                            </div>
                            <div class="form-group new">
                                <div class="col-sm-4">
                                    <label>Location<span style="color:Red;">*</span></label>
                                </div>
                                <div class="col-sm-7">
                                    <input type="text" name="location" id="location" value = "{{$event->event_location}}" placeholder="LOCATION" class="form-control">
                                    <span class="text-danger error">{{$errors->first('location')}}</span>
                                    <input type="hidden" id="lat" name="lat" value="{{$event->event_lat}}">
                                    <input type="hidden" id="lon" name="lon" value="{{$event->event_long}}">
                                </div>
                            </div>
                            <div class="form-group new">
                                <div class="col-sm-4">
                                    <label>Start Date & Time<span style="color:Red;">*</span></label>
                                </div>
                                <div class="col-sm-7 date">
                                  <div class="form-group">
                                    <div class='input-group date' id='datetimepicker1'>
                                      <input  placeholder="START DATE & TIME" value="{{$sub_event->event_start_date_time}}" autocomplete="off" name="start_date" class="form-control start_date" />
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
                              <input type='text' autocomplete="off" value="{{$sub_event->event_end_date_time}}" placeholder="END DATE & TIME" name="end_date" class="form-control end_date"  />
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
                        <option value="one_day" @if($event->repeat_interval == 'one_day') selected @else @endif>ONE TIME</option>
                        <option value="weekly" @if($event->repeat_interval == 'weekly') selected @else @endif>WEEKLY</option>
                        <option value="2_weekly" @if($event->repeat_interval == '2_weekly') selected @else @endif>FORTNIGHTLY</option>
                        <option value="monthly" @if($event->repeat_interval == 'monthly') selected @else @endif>MONTHLY</option>
                    </select>
                    <span class="text-danger error">{{$errors->first('repeat_interval')}}</span>
                </div>
            </div>
            <div class="form-group new">
                <div class="col-sm-4">
                    <label>DRESS CODE<span style="color:Red;">*</span></label>
                </div>
                <div class="col-sm-7">
                    <select class="form-control selectbox" name="dress_code">
                        <option value="">SELECT DRESS CODE</option>
                        <option value="formal" @if($event->dress_code == 'formal') selected @else @endif>FORMAL</option>
                        <option value="casual" @if($event->dress_code == 'casual') selected @else @endif>CASUAL</option>
                    </select>
                    <span class="text-danger error">{{$errors->first('dress_code')}}</span>
                </div>
            </div>

            <div class="form-group new">
                <div class="col-sm-4">
                    <label>Id Required<span style="color:Red;">*</span></label>
                </div>
                <div class="col-sm-7">
                    <select class="form-control selectbox" name="id">
                        <option value="">SELECT ID REQUIRED</option>
                        <option value="1" @if($event->id_Required == '1') selected @else @endif>YES</option>
                        <option value="2" @if($event->id_Required == '2') selected @else @endif>NO</option>
                    </select>
                    <span class="text-danger error">{{$errors->first('id')}}</span>
                </div>
            </div>
            <div class="form-group new">
                <div class="col-sm-4">
                    <label>Age Restriction</label>
                </div>
                <div class="col-sm-7">
                    <input type="text" placeholder="AGE RESTRICTION" maxlength="2" value="{{$event->age_restrictions}}" name="age" class="form-control">
                    <span class="text-danger error">{{$errors->first('age')}}</span>
                </div>
            </div>
            <div class="form-group new">
                <div class="col-sm-4">
                    <label>Website URL</label>
                </div>
                <div class="col-sm-7">
                    <input type="text" placeholder="WEBSITE URL" value="{{$event->website}}" name="website_url" class="form-control">
                    <span class="text-danger error">{{$errors->first('website_url')}}</span>
                </div>
            </div>
            <div class="form-group new">
                <div class="col-sm-4">
                    <label>Phone Number</label>
                </div>
                <div class="col-sm-7">
                    <input type="text" placeholder="PHONE NUMBER" value="{{$event->contact_number}}" name="phone_number" class="form-control">  
                    <span class="text-danger error">{{$errors->first('phone_number')}}</span>
                </div>
            </div>
            <div class="form-group  new margin-bottom-45">
                <div class="col-sm-4">
                    <label>Description<span style="color:Red;">*</span></label>
                </div>
                <div class="col-sm-7">
                    <textarea class="form-control" placeholder="DESCRIPTION" name="descriptions">{{$event->description}}</textarea>
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
                    <span class="text-danger error">{{$errors->first('public')}}</span>
                </div>
            </div>
            <!-- <div class="form-group new margin-bottom-20">
                <div class="col-sm-4">
                    <label>Invite users</label>
                </div>
                <div class="col-sm-7">
                    <div class="new-jackets" style="width:45px;">
                      <a href="javascript:void(0);" class="" data-toggle="modal" data-target="#myModal-new"><img src="{{url('public/website/images/user-circle.png')}}"></a>
                  </div>
              </div>
          </div> -->
          <div class="col-md-12 margin-top-30">
            <div class="col-sm-12 text-center first">
                <button type="submit" class="btn btn-default register" >SUBMIT</button>
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
                            <?php 
                               if(count($event_music_interest) > 0) {
                                $check = 'checked';
                               } else {
                                    $check = '';
                               }
                            ?>
                           <a href="javascript:void(0);" data-toggle="modal" data-target="#interest1"><input type="checkbox" name="" class="checkBoxClass music_int1" {{$check}}><label class="check animal new">
                            <img src="{{url('public/website/images/jazz.png')}}">music</label></a>
                        </div>
                    </div>
                    @foreach($public_interest as $public)
                    @php 
                        $image = $public->image ? url('/public') . DIRECTORY_SEPARATOR . $public->image : url('/') . DIRECTORY_SEPARATOR . env('ADMIN_IMAGES_PATH') . 'user.png';
                    @endphp
                    <div class="col-sm-4">
                      <div class="form-group">
                       <input type="checkbox" name="public[]" value="{{$public->id}}" class="checkBoxClass public_int" @foreach($event_public_interest as $user_public)@if($public->id == $user_public->public_interest_id) checked @endif @endforeach><label class="check Community new"><img src="{{$image}}">{{$public->name}}</label>
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
              <input type="checkbox"  name="music[]" value="{{$music->id}}" class="checkBoxClass music_int1"@foreach($event_music_interest as $user_music)
                @if($music->id == $user_music->music_interest_id) checked @endif
                @endforeach><label class="check animal new"><img src="{{$image}}">{{$music->name}}</label>
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
            <div class="form-reflex form-info-settings adit ">
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


</div>
</form>
</div>
</div>
</section>


@endsection()
@section('js')
<script type="text/javascript">
    $(function () {
        var start_date = $(".start_date").val();
        // var dt = new Date();
        // dt.setMinutes( dt.getMinutes() + 10 );
        var end_date = $(".end_date").val();
        $('#datetimepicker1').datetimepicker({
            format: 'YYYY-MM-DD LT',
            minDate: start_date,
        });

        $('#datetimepicker2').datetimepicker({
            format: 'YYYY-MM-DD LT',
            minDate: end_date,
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
    // $(document).ready(function(){
    //     $('#datetimepicker1').on('keydown', function(e){
    //         e.preventDefault();
    //     });
    // });

    // $(document).ready(function(){
    //     $('#datetimepicker2').on('keydown', function(e){
    //         e.preventDefault();
    //     });
    // });



    $(".register").click(function() {
        var start = $('#datetimepicker1 input').val();
        var end = $('#datetimepicker2 input').val();
        if(end == start) {
            $('.enddate').text('End date should not be less than start date or equal.');
            return false;
        } else{
            $('.enddate').text('');
        }
    });

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

<script type="text/javascript">
  function readURL3(input) {
    if (input.files && input.files[0]) {

      var type = input.files[0].type;

      if(type == 'image/jpeg' || type == 'image/png'){

        var reader = new FileReader();

        reader.onload = function (e) {
          $('#oldImg3').attr('src', e.target.result);
      }

      reader.readAsDataURL(input.files[0]);

  } else {
    $('.msz4').text('Only Jpeg and Png format is allowed.');
}
}
}

$("#imgInp3").change(function(){
    readURL3(this);
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
        $('#lon').val(long);
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
        // alert(media_type); return false;
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

    var type = "<?php echo $event->main; ?>";
    var media_type = "<?php echo $event->event_media_type; ?>";
    
    var event_video = "<?php echo $event->event_video_url; ?>";
    var event_video1 = "<?php echo $event->event_video_url2; ?>";
    var event_image = "<?php echo $event->event_image_url; ?>";
    var event_image1 = "<?php echo $event->event_image_url2; ?>";
   
    // alert(media_type);
    if(type == 1 && media_type == 2) {
        // alert('aa')
       $('#video').css("display","");
       $('#image').css("display","none");
       if(event_image1 == '') {
           $('#video1').css("display","");
           $('#image1').css("display","none"); 
       } else {
           $('#video1').css("display","none");
           $('#image1').css("display",""); 
       }
            
    }
    if(type == 2 && media_type == 2) {
        // alert(media_type); return false;
            $('#video1').css("display","");
            $('#image1').css("display","none");
            if(event_image == '') {
                $('#video').css("display","");
                $('#image').css("display","none"); 
            } else {
                $('#video').css("display","");
                $('#image').css("display","none");
            }
            
    }

    if(event_image == '') {
        $('#video').css("display","");
        $('#image').css("display","none"); 
    } else {
        $('#video').css("display","none");
        $('#image').css("display","");
    }

    if(event_image1 == '') {
           $('#video1').css("display","");
           $('#image1').css("display","none"); 
       } else {
        $('#video1').css("display","none");
        $('#image1').css("display","");
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
                required: true
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

<script>
    $(document).ready(function () {
    $('#form_ticket').validate({ // initialize the plugin
        rules: {
            ticket_title: {
                required: true
            },
            ticket_description: {
                required: true
            },
            ticket_amount: {
                required: true,
                number: true
            },
            ticket_quantity: {
                required: true,
                number: true
            }
        },

        messages:{
            ticket_title:{  
              required:'Please enter event name.'
          },
          ticket_description:{  
              required:'Please enter location.'
          },
          ticket_amount:{  
              required:'Please enter ticket amount.',
              number:'Ticket amount should be a number.'
          },
          ticket_quantity:{  
              required:'Please enter ticket quantity.',
              number:'Ticket quantity should be a number.'
          }

      }
  });
});
</script>

<script type="text/javascript">

  function submit_form1(){
    // e.preventDefault();
    
    var ticket_title = $('#ticket_title').val();
    var ticket_description = $('#ticket_description').val();
    var ticket_amount = $('#ticket_amount').val();
    var ticket_quantity = $('#ticket_quantity').val();
    var data = {
      '_token': "{{csrf_token()}}",
      
      'ticket_title': ticket_title,
      'ticket_description': ticket_description,
      'ticket_amount': ticket_amount,
      'ticket_quantity': ticket_quantity,

  };

  $.ajax({
      url:"{{url('website/add-ticket')}}",
      type:'POST',
      data:data,
      success: function(res){
          console.log(res);
          $('#form_ticket')[0].reset();
          // return false;
      // if(res.message){
      //  $('.submit_form').attr('disabled', true);
      //   window.location = '{{url("admin/user-management")}}'
      // }
  },
});


}
</script>

</body>

</html>

@endsection()