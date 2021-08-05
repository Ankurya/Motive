@extends('website.common')
@section('title', 'Motive')

@section('content')
<style type="text/css">
  img.newr {
    width: 100%;
    height: 300px;
    border-radius: 16px;
    object-fit: contain;
    border: 1px solid #fff;



}

input[type="submit"] {
    position: absolute;
    right: 0;
    z-index: -1;
}
span.like-icon h3 {
    display: inline-block;
}
.tab-content {
    width: 100%;
}
.heading-top {
    padding: 77px 0 0 0;
}

span.like-icon img {
    margin-left: 15px;
    height: 29px;
}

.alert-success {
    color: #3c763d;
    width: 768px;
    margin-left: 188px;
    background-color: #dff0d8;
    border-color: #d6e9c6;
}

figure.new {
    border: 1px solid #fff;
    border-radius: 12px;
}

</style>

<section class="bg-color main-section"> 
   
  <div class="container">
    <div class="heading-top">
        <h1>Posts</h1>
    </div>
    @include('website.notifications_message')
  
      <div class="tab-content">
        <div id="tab1" class="tab active">
          <div class="col-md-8 col-md-offset-2">
           <div class="form-reflex form-info-settings book-now no-padding">
            <div class="row">
              <div class="add-post">
                <label style="text-transform: capitalize; font-family: 'CaviarDreams-Bold';">Add post</label>
                <a href="{{url('website/add-post',$event_id)}}">
                  <img src="{{url('public/website/images/plus.png')}}">
                </a>
              </div>
              @if(!empty($posts) && $posts && count($posts))
              @foreach($posts as $post)
              <a href="{{url('website/comments',$post->id)}}">
              @if($post->post_media_type == 1)
                    @if($post->post_image_url)
                      <img src="{{$post->post_image_url}}" class="newr">
                    @else 
                      <img src="{{url('public/website/images/img-brackets.png')}}" class="newr">
                    @endif
              @else 
             <video class="create" controls="" width="100%" height="200px;" style="margin-top: 12px;">
                <source src="{{$post->post_video_url}}" type="video/mp4">
                <!-- <source src="{{url('public/website/video/movie.ogg')}}" type="video/ogg"> -->
             </video>
             @endif
             </a>
              <div class="comment-icons">
             
                <span class="coment-icon"><a href="{{url('website/comments',$post->id)}}"><img src="{{url('public/website/images/comment.png')}}"></a> @if($post->comments > 0 && $post->comments > 1){{$post->comments}} Comments @else {{$post->comments}} Comment @endif</span>
                  <span class="like-icons">
                      
                <span class="like-icon"> <h3>@if($post->likes > 0 && $post->likes > 1) {{$post->likes}} Likes @else {{$post->likes}} Like @endif </h3>
                  @if(!empty($post->like_status) && $post->like_status->like_status == 1)
                    <img src="{{url('public/website/images/heart-icon.png')}}" class="show_red" data-id="{{$post->id}}" >
                  @else
                    <img  class="show_red" data-id="{{$post->id}}" src="{{url('public/website/images/heart.png')}}" >
                  @endif
                </span>

              </div>
             
            
            @endforeach
            @else 
            <h1 style="text-align:center; color: #fff; font-size: 30px;">No Post Available</h1>
            @endif

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
</section>
</div>
</div>

@endsection()
@section('js')
<script type="text/javascript">
  $(".show_red").click(function(){
    var post_id = $(this).data('id');
    // alert(post_id);
    var src = $(this).attr("src");

    var data = {
      '_token': "{{csrf_token()}}",
      'post_id': post_id,
    
      };
    // console.log(data);
    $.ajax({
      url: "{{url('website/like-post')}}/"+post_id,
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
  });



</script>
@endsection()