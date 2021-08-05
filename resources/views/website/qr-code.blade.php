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
    .home-wrapper h3 {
      text-align: center;
      color: #fff;
      font-size: 30px;
          margin-bottom: 28px;

    }
    .home-wrapper label {
      font-size: 20px;
    color: #fff;
    width: 160px;
    text-align: left;
    }
    .home-wrapper p {
     font-size: 18px;
    color: #fff;
    display: inline-block;
    text-align: left;
    width: 122px;

    }
    .home-wrapper figure{
          margin: 25px 0 80px 0;

    }
    .qrcode {
    display: inline-block;
    margin-left: 17px;
    text-align: center;
}


  </style>
  <section class="bg-color main-section"> 
    <div class="container">
      <div class="row">
       <div class="col-md-8 col-md-offset-2">  
        <div class="home-wrapper">
          <div class="heading-top">
            <h1>Tickets</h1>
          </div>
          <h3>SHOW THIS TICKETS AT THE TIME OF ENTRY OF THE EVENT</h3>
          <div class="qrcode">
          <div class="col-md-12">
            <label>EVENT NAME:</label>
            <P style="text-transform: uppercase;">   {{$event->event_name}}</P>
          </div>
          <div class="col-md-12">
            <label>TICKETS:</label>
            <p>{{$ticket->quantity}}</p>
          </div>
          <div class="col-md-12">

            <label>AMOUNT:</label>
            <P>&#163;{{$ticket->amount}}</P>
          </div>
          <div class="col-md-12">
            <figure>
              <img src="{{$ticket->qr_image}}">
            </figure>


        </div>  


      </div>
    </div>

  </section>

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


@endsection()