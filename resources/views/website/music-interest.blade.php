@extends('website.common')
@section('title', 'Motive')

@section('content')
<style type="text/css">
  .alert.alert-success.alert-dismissible.text-center.alertz {
    max-width: 751px;
    margin: 0 auto;
    width: 100%;
}
</style>
<section class="bg-color main-section"> 
    <div class="container">
        <div class="heading-top">
            <h1>Music Interests</h1>
        </div>
        @include('website.notifications_message')
        <div class="tabs">
          <div id="interest1">
            <div class="modal-dialog">

              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal"> </button>
                  <h4 class="modal-title">Select  music interests</h4>
              </div>
              <div class="modal-body">
                  <div class="row">
                    <div class="col-sm-12">
                      @if($music_count == $user_music_count)
                      <div class="user-wrap">
                        <input type="checkbox" id="CheckAlls" name="" checked>
                        <label class="check">All</label>
                      </div>
                    @else 
                    <div class="user-wrap">
                        <input type="checkbox" id="CheckAlls" name="">
                        <label class="check">All</label>
                      </div>
                    @endif
                </div>
            </div>
            <div class="first-content">
                <div class="row">
                    <form method="post" action="{{url('website/edit-music-interest')}}"> 
                      {{csrf_field()}}  
                      @foreach($music_interest as $music)
                      @php 
                        $image = $music->image ? url('/public') . DIRECTORY_SEPARATOR . $music->image : url('/') . DIRECTORY_SEPARATOR . env('ADMIN_IMAGES_PATH') . 'user.png';
                      @endphp
                      <div class="col-sm-4">
                        <div class="form-group">
                          <input type="checkbox"  name="music[]" value="{{$music->id}}" class="checkBoxClass music_int" 
                          @foreach($user_music_interest as $user_music)
                            @if($music->id == $user_music->music_interest_id) checked @endif
                          @endforeach><label class="check animal new"><img src="{{$image}}">{{$music->name}}</label>
                      </div>
                  </div> 
                  @endforeach
                  <div class="col-md-12">
                    <button type="submit" class="btn btn-default register yes">Submit</button>
                </div>
                </form> 
                </div>
                  






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
<script type="text/javascript">
  $(document).ready(function() {
    $("ul.nav.navbar-nav li").click(function () {
      $("ul.nav.navbar-nav li").removeClass("active");
      $(this).addClass("active");   
      
  });
    $("#CheckAll").click(function () {
      // alert('dda');
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
  $(document).ready(function(){
    $(".music_int").click(function(){
      
      let checked = 0;
      let All = 0;

        $(".music_int").each(function(){
          if($(this).prop("checked")){
            checked++;
          }
          All++;
        })

        if(checked == All){
          $("#CheckAlls").prop("checked",true)
        }else {
          $("#CheckAlls").prop("checked",false)
        }
      }); 
});
</script>
@endsection()