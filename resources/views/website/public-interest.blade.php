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
            <h1>Public Interests</h1>
        </div>
        @include('website.notifications_message')
        <div id="interest">
          <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">Select your interests</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                  <div class="col-sm-12">
                    @if($public_count == $user_public_count)
                    <div class="user-wrap">
                      <input type="checkbox" id="CheckAll" name="" checked>
                      <label class="check">All</label>
                    </div>
                    @else 
                    <div class="user-wrap">
                      <input type="checkbox" id="CheckAll" name="">
                      <label class="check">All</label>
                    </div>
                    @endif
              </div>
          </div>
          <div class="first-content">
            <div class="row">
            <form method="post" action="{{url('website/edit-public-interest')}}">
            {{csrf_field()}}
            @foreach($public_interest as $public)
                @php 
                    $image = $public->image ? url('/public') . DIRECTORY_SEPARATOR . $public->image : url('/') . DIRECTORY_SEPARATOR . env('ADMIN_IMAGES_PATH') . 'user.png';
                @endphp
                <div class="col-sm-4">
                  <div class="form-group">
                     <input type="checkbox" name="public[]" value="{{$public->id}}" class="checkBoxClass public_int" 
                     @foreach($user_public_interest as $user_public)
                     @if($public->id == $user_public->public_interest_id) checked @endif
                     @endforeach><label class="check Community new"><img src="{{$image}}">{{$public->name}}</label>
                    
                 </div>
             </div>
              @endforeach()
                <div class="col-md-12">
                    <button type="submit" class="btn btn-default register yes" data-dismiss="modal">Submit</button>
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
    $(".public_int").click(function(){
      
      let checked = 0;
      let All = 0;

        $(".public_int").each(function(){
          if($(this).prop("checked")){
            checked++;
          }
          All++;
        })

        if(checked == All){
          $("#CheckAll").prop("checked",true)
        }else {
          $("#CheckAll").prop("checked",false)
        }
      }); 


    $(".music_int").click(function(){
      if(!$(this).prop("checked")){
    //alert( $("#CheckAlls").prop("checked"))
    $("#CheckAlls").prop("checked",false);
    //alert($("#CheckAlls").prop("checked"))
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
            /*alert($(".music_int1").prop("checked"))*/

        }else {
            $(".music_int1").prop("checked",false);
        }

    });
});
</script>
@endsection()
