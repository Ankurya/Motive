@extends('website.common')
@section('title', 'Motive')

@section('content')
<style type="text/css">
  
.post-img.img-news img {
    width: 100%;
    height: 273px;
    border-radius: 12px;
        object-fit: contain;
        border: 1px solid #fff;

}
img.two {
    display: none;
}
span.like-icon.highlight img.two{
  display: inline-block;
}
span.like-icon.highlight img.one{
  display: none;
}
span.like-icons {
    float: right;
}
span.like-icons {
    display: inline-flex;
}
input#CheckAll {
    width: 31px;
    height: 36px;
    position: absolute;
    right: 0;
    z-index: -1;
    opacity: 0;
}
span.like-icons h3 {
    vertical-align: top;
    margin-top: 4px;
}
span.like-icon img {
    margin-left: 20px;
    width: 36px;
}
.user-comments ul li img {
    width: 40px;
    height: 40px;
    border-radius: 100%;
}
p.comment-content {
    padding-bottom: 23px;
}
/*input[type="checkbox"] {
    position: absolute;
    width: 37px;
    opacity: 0;
    height: 37px;
    right: 0;
}*/

figure.new {
    border: 1px solid #fff;
    border-radius: 12px;
}


</style>
<section class="bg-color main-section padding-top-100"> 
  <div class="container">
    <div class="tabs">
      <!-- <ul class="tab-links">
        <li class="active"><a href="#tab1">Now</a></li>
        <li><a href="#tab2">future</a></li>
        <li><a href="#tab3">favourites</a></li>
      </ul> -->
      <div class="tab-content">
        <div id="tab1" class="tab active">
          <div class="col-md-8 col-md-offset-2">
           <div class="form-reflex form-info-settings book-now no-padding commment-edit">
            <div class="row">
              <!-- <div class="add-post">
                <label style="text-transform: capitalize;     font-family: 'CaviarDreams-Bold';">Add post</label>
                <a href="add-post.html">
                  <img src="{{url('public/website/images/plus.png')}}">
                </a>
              </div> -->

              <div class="post-img img-news">
                @if($post->post_media_type == 1)
                  @if($post->post_image_url)
                    <img src="{{$post->post_image_url}}">
                  @else
                    <img src="{{url('public/website/images/add-post.png')}}">
                  @endif
                @else 
                  <video class="create" controls="" width="100%" height="200px;" style="margin-top: 12px;">
                    <source src="{{$post->post_video_url}}" type="video/mp4">
                    <!-- <source src="{{url('public/website/video/movie.ogg')}}" type="video/ogg"> -->
                  </video>
                @endif
              </div>
              
                <div class="comment-icons">
                  <span class="coment-icon"><img src="{{url('public/website/images/comment.png')}}">@if($comment > 0 && $comment > 1){{$comment}} Comments @else {{$comment}} Comment @endif </span>
                  <span class="like-icons"><h3>@if($likes > 0 && $likes > 1){{$likes}} Likes @else {{$likes}} Like @endif </h3><span class="like-icon">@if(!empty($like) && $like->like_status == 1)<img src="{{url('public/website/images/heart-icon.png')}}" id="show_red" onclick="return like();">@else<img  id="show_green" src="{{url('public/website/images/heart.png')}}" onclick="return like();" >@endif

                  </span>
                </div>
             

              <div class="col-sm-12 no-padding">
                <p class="comment-content">{{$post->text}}</p>
              </div>
              <div class="col-sm-12 no-padding">
                <div class="user-comments">
                  <ul>
                    @foreach($comments as $commentss)
                    <li>
                     @if(!empty($commentss->users->image_url)) <img src="{{$commentss->users->image_url}}"> @else <img src="{{url('public/website/images/user.png')}}">@endif<label>@if(!empty($commentss->users->name)) {{$commentss->users->name}} @else User @endif </label>
                      <p>
                        {{$commentss->comment}}
                      </p>
                    </li>
                    @endforeach()
                  </ul>
                  <form method="post"> 
                    {{csrf_field()}}
                      <div class="comment-text">
                        <label>Comment</label>
                        <textarea placeholder="COMMENT" maxlength="300" name="comment" class="form-control height-115"></textarea>
                        <span class="text-danger error">{{$errors->first('comment')}}</span>
                      </div>
                      <div class="col-sm-12 text-center margin-top-30">
                      <button type="submit" class="btn btn-default register">COMMENT</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- <div id="" class="tab">
        <p>Tab #2 content goes here!</p>
        <p>Donec pulvinar neque sed semper lacinia. Curabitur lacinia ullamcorper nibh; quis imperdiet velit eleifend ac. Donec blandit mauris eget aliquet lacinia! Donec pulvinar massa interdum risus ornare mollis. In hac habitasse platea dictumst. Ut euismod tempus hendrerit. Morbi ut adipiscing nisi. Etiam rutrum sodales gravida! Aliquam tellus orci, iaculis vel.</p>
      </div>

      <div id="" class="tab">
        <p>Tab #3 content goes here!</p>
        <p>Donec pulvinar neque sed semper lacinia. Curabitur lacinia ullamcorper nibh; quis imperdiet velit eleifend ac. Donec blandit mauris eget aliquet lacinia! Donec pulvinar massa interdum ri.</p>
      </div>

      <div id="" class="tab">
        <p>Tab #4 content goes here!</p>
        <p>Donec pulvinar neque sed semper lacinia. Curabitur lacinia ullamcorper nibh; quis imperdiet velit eleifend ac. Donec blandit mauris eget aliquet lacinia! Donec pulvinar massa interdum risus ornare mollis. In hac habitasse platea dictumst. Ut euismod tempus hendrerit. Morbi ut adipiscing nisi. Etiam rutrum sodales gravida! Aliquam tellus orci, iaculis vel.</p>
      </div> -->
    </div>
  </div>
</div>
</section>
</div>
</div>


@endsection()
@section('js')
<!-- <script type="text/javascript">
$(".check").click(function(){
  alert("The paragraph was clicked.");
});
</script> -->
<script type="text/javascript">
$(document).ready(function(){
    $(".like-icon").click(function(){
        $(this).toggleClass("highlight");
    });
});
</script>

<script type="text/javascript">
  function like(){
    $('#show_red_hidden').hide();
    $('#show_green_hidden').hide();
    var post_id = <?php echo $post->id ?>;
    
    // alert('qq');
    
    var data = {
      '_token': "{{csrf_token()}}",
      'post_id': post_id,
      
      };
     // console.log(data);
      $.ajax({
        url: "{{url('website/like-post',$post->id)}}",
        type: 'POST',
        data: data,
        success:function(res){
          // console.log(res);
         if(res == 'success'){
          location.reload();
          // $('#show_green').hide();
          // $('#show_red_hidden').show();
          // $('#show_red').hide();
          // $('#show_green_hidden').hide();
         }else{
          location.reload();
          // $('#show_green').hide();
          // $('#show_red_hidden').hide();
          // $('#show_red').hide();
          // $('#show_green_hidden').show();
         }
                 
          
        }
      });
  }
</script>
@endsection()