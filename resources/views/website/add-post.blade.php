@extends('website.common')
@section('title', 'Motive')

@section('content')
<style type="text/css">
.user-wrap.images-new img{
    border-radius: 18px;
}
.field input[type="file"] {
    opacity: 0;
    width: 100%;
    position: absolute;
    top: 0;
    bottom: 0;
    display: block;
    left: 0;
    cursor: pointer;
}
.upload-btn label {
    position: absolute;
    right: 0;
    padding: 0px 15px;
    top: 12px;
    right: 0;
    
}
.upload-btn span {
    padding: 0px 0;
    display: inline-block;
    vertical-align: middle;
    float: left;
    width: auto;
    background: transparent;
}
.upload-btn span {
    padding: 0px 0;
    display: inline-block;
    vertical-align: middle;
    float: left;
    width: auto;
    background: transparent;
}
.upload-btn.form-control {
    position: relative;
    left: -4px;
    width: 101% !important;
}
.col-sm-4.video {
    padding-left: 0;
}
input#imgInp, input#imgInp1 {
 opacity: 0;
 position: absolute;
 top: -44px;
 left: 0;
 height: 100%;
 right: 0;
 margin: 0 auto;
 width: 100%;
}
.tab-content
{
    width: 100%;
}

textarea.form-control, .form-control
{
padding: 6px 18px;
}
.form-group.images-first img {
    width: 100%;
    height: 270px;
    object-fit: cover;
}
input#imgInp, input#imgInp1
{
    top: -4px;
}

    .upload-btn.form-control {
    position: relative;
    overflow: hidden;
    white-space: nowrap;
    width: 100%;
    text-overflow: ellipsis;
    padding-right: 35px;
}
span#msg12 {
    margin-top: 5px;
    width: 100%;
    display: block;
    padding-right: 10px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.form-group.new .field p {
    margin-top: 6px;
}
.form-group.new .field {
    margin-bottom: 13px;
}

.heading-top {
    padding: 77px 0 0;
}

.alert-danger {
    color: #a94442;
    background-color: #f2dede;
    width: 664px;
    margin-left: 182px;
    border-color: #ebccd1;
}

.error {
    color:red;
    font-size:16px;
    /*text-transform: capitalize; !important;*/
    margin-top: 5px !important;
}
label#description-error {
    text-transform: capitalize;
}
</style>
<section class="bg-color main-section">
    <div class="container">
        <div class="heading-top">
            <h1>add post</h1>
        </div>
        @include('website.notifications_message')
        <div class="tabs">
                
                <div class="tab-content">
                    <div id="tab1" class="tab active">
                        <div class="col-md-7 col-md-offset-2">
                            <div class="form-reflex addd">
                                <div class="row">
                                    <form method="post" enctype="multipart/form-data" id="form_validate" onsubmit="return validate_form1()">
                                        {{csrf_field()}}
                                        <div class="form-group new">
                                            <div class="col-sm-4">
                                                <label>Select File<span style="color:Red;">*</span></label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="field" style="margin-bottom: 12px;">
                                                    <select id="upload_type" class="selectbox form-control" name="upload_type">
                                                        <option value="1">IMAGE</option>
                                                        <option value="2">VIDEO</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 no-padding">
                                            <div class="background-img" id="image">
                                                <div class="col-sm-4">
                                                    <label class="margin-bottom-15">Image</label>
                                                </div>
                                                
                                                <div class="col-sm-8">
                                                    <div class="form-group images-first">
                                                        <img onclick="$('#imgInp').click()" id="oldImg" src="{{url('public/website/images/no-image.png')}}">
                                                        <input type="file" id="imgInp" name="post_image_url1">
                                                     <span class="text-danger error image">{{$errors->first('post_image_url1')}}</span>
                                                     <span class="text-danger error msz"></span>
                                                     <span class="text-danger error err"></span>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        
                                        <div class="img" id="video">
                                            <div class="col-md-12">
                                             
                                               <div class="form-group new">
                                                <div class="col-sm-4 video">
                                                    <label>Add video</label>
                                                </div>
                                                <div class="col-sm-8" style="padding: 0;">
                                                    <div class="col-sm-12 small padding-right-0">
                                                        <div class="field">
                                                            <div class="">
                                                                <div class="upload-btn form-control">
                                                                    <span class="span" id="msg12">NO FILE CHOSEN</span>
                                                                    <label for="file-upload" class="up-file"><i class="fa fa-upload" aria-hidden="true"></i></label>
                                                                    <input type="file" name="post_video_url" id="file-upload5" class="fileinput">
                                                                </div>

                                                                <span class="text-danger error msz2"></span>
                                                                <span class="text-danger error err"></span>
                                                            </div>
                                                            <p style="color: #fff; display: none;">( Only mp4 format allowed )</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="margin-bottom-15 margin-top-25">Description</label>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <textarea placeholder="DESCRIPTION" maxlenth="500" class="height-115 form-control" name="description"></textarea>
                                            <span class="text-danger error">{{$errors->first('description')}}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group margin-top-70 text-center">
                                            <button class="btn btn-default register add">ADD</button>
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
    
    if($('#upload_type').val()==2){
        $('#video').css("display","");
        $('#image').css("display","none");
    }else if($('#upload_type').val()==1){
        
        $('#image').css("display","");
        $('#video').css("display","none");
        
    }
    $('#upload_type').change(function(){
        
        if($(this).val()==1){
            $('#image').css("display","");
            $('#video').css("display","none");
        }else{
            $('#video').css("display","");
            $('#image').css("display","none");
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
      $('.msz').hide();
      $('.image').hide();

  } else {
    // $('#imgInp').val("");

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
    $('.err').hide();
    // $("#imgInp1").val();

}
}
}

$("#imgInp1").change(function(){
    readURL1(this);
});


$("#file-upload5").change(function(event){
    if($(this).val() != ''){
        $(this).prev().prev().text(event.target.files[0].name)
    }
        // console.log(event.target.files[0].type)
    var type = event.target.files[0].type;
        if(type != 'video/mp4'){
         $('.msz2').text('Only mp4 format allowed.'); 
         $('.err').hide();
         $("#file-upload5").val('');

     }else {
        $(".msz2").text('')
    }


});

// $('.add').click(function(){
//     // alert('aa');
// var name = $("#file-upload5").prev().prev().text(event.target.files[0].name);
// alert(name)   
// if(name == ''){
//     alert('qq');
// };
// });

</script>




<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
   <script>
    $(document).ready(function () {
    $('#form_validate').validate({ // initialize the plugin
        rules: {
            description: {
                required: true
            },
            
        },

        messages:{
            description:{  
              required:'Please enter description.'
            },
            
            
          }
    });
});
</script>

<script type="text/javascript">
    function validate_form1(){
    
    let img1 =  $("#imgInp").val()
    let video1 =  $("#file-upload5").val()

    if(img1.length == 0 && video1.length == 0){
        $('.err').text('Please select atleast image or video.');
        return false;
    }else {
        $('.err').hide();
        
    }

    
}
</script>
<script type="text/javascript">
$("#imgInp,#file-upload5").change(function() {
    if($(this).val().length > 0) {
        $('.err').text('');
    } else{
        $('.err').text('Please select atleast image or video.');
    }
});
    
</script>


@endsection()