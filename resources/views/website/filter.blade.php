@extends('website.common')
@section('title', 'Motive')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
<style type="text/css">
    .form-reflex .form-group img {
    position: relative;
    top: 0px;
    width: auto;
    filter: brightness(100);
    -moz-filter: brightness(100);
    -webkit-filter: brightness(100);
    left: 0;
    height: 57px;
    right: 0;
    margin: auto;
}


#interest input[type='checkbox']:checked ~ img
{
filter: none;
}

#interest .form-group label {
    margin-top: 34px;
}

#interest label.check:after {
    right: 0;
    left: 0;
    bottom: 61px;
    height: 0;
    width: 51px;
}
.loop2.owl-carousel .owl-nav.disabled .owl-prev{
        top: 0px;

}
.loop2.owl-carousel .owl-nav.disabled .owl-next{
        top: 0;

}
.owl-carousel.alert .owl-stage-outer{
        display: flex;

}
.owl-carousel.alert  .form-group {
    margin-bottom: 36px;
}
.dropdown-menu {
    min-width: 253px;
}
.form-reflex.form-info-settings.filter input.form-control{
        cursor: pointer;

}
button.btn.btn-default.register {
    display: inline-block;
    text-align: center;
}
.loop2 .item {
    display: inline-block;
}
.loop2 .item {
    display: inline-block;
    width: 53%;
}  
.pub {
    display: flex;
} 

</style>
<section class="bg-color main-section"> 
    <div class="container">
        <div class="heading-top" style="padding:77px 0 0 ;">
            <h1>filter</h1>
        </div>
        <div class="tabs">

            <div class="tab-content" style="width: 100%;">
                <div id="tab1" class="tab active">
                  <div class="col-md-8 col-md-offset-2">
                     <div class="form-reflex form-info-settings filter">
                        <div class="row">
                            <form method="post">
                            {{csrf_field()}} 
                            <div class="col-sm-12 margin-top-50">
                                <h2 class="text-center uppercase">My Location is London</h2>
                                
                                <div class="range-slider margin-top-70">
                                    <input class="range-slider__range uppercase" name="miles" type="range" value="0" min="0" max="500">I AM LOOKING FOR AN EVENT WITHIN&nbsp;<span class="range-slider__value"> 0</span> MILES
                                </div>
                                <h2 class="margin-top-70 uppercase text-center margin-bottom-50">the date is</h2>
                                <div class="filter">
                                    <input class="form-control" id="date" name="date" autocomplete="off" placeholder="MM/DD/YYYY" type="text"/>
                                </div>
                                <h2 class="margin-top-70 uppercase text-center">i am in the mood for</h2>
                               <!--  <div class="loop2 owl-carousel owl-theme margin-top-60 " id="interest">
                                    <div class="pub"></div>
                                </div> -->
                                <div class="border-top-bottom padding-50 loop owl-carousel owl-theme margin-top-60 alert" id="interest">
                                @foreach($public_interest as $public)
                                    @php 
                                        $image = $public->image ? url('/public') . DIRECTORY_SEPARATOR . $public->image : url('/') . DIRECTORY_SEPARATOR . env('ADMIN_IMAGES_PATH') . 'user.png';
                                    @endphp
                                    <div class="item">
                                        <div class="form-group">
                                            <input type="checkbox" name="public[]" id="{{$public->id}}" value="{{$public->id}}" class="checkBoxClass public"><img src="{{$image}}"><label class="check car">{{$public->name}}</label>
                                        </div>
                                    </div>
                                @endforeach
                                </div>


                                <h2 class="margin-top-70 uppercase text-center">i want to listen</h2>
                               <!--  <div class="loop2  owl-carousel owl-theme margin-top-60" id="interest">
                                    <div class="mus"></div>
                                </div> -->
                                   <div class="border-top-bottom padding-50 loop owl-carousel owl-theme margin-top-60 alert" id="interest">
                                    @foreach($music_interest as $music)
                                        @php 
                                            $image = $music->image ? url('/public') . DIRECTORY_SEPARATOR . $music->image : url('/') . DIRECTORY_SEPARATOR . env('ADMIN_IMAGES_PATH') . 'user.png';
                                        @endphp
                                    <div class="item">
                                        <div class="form-group">
                                            <input type="checkbox" name="music[]" id="{{$music->id}}" value="{{$music->id}}" class="checkBoxClass music"><img src="{{$image}}"><label class="check car">{{$music->name}}</label>
                                        </div>
                                    </div>
                                @endforeach
                                </div>
                             <div class="text-center">
                                <input id="lat" type="hidden" name="lat" value="30.7001201" class="checkBoxClass">
                                <input id="lon" type="hidden" name="lon" value="76.699006" class="checkBoxClass">
                                <button type="button" class="btn btn-default register" id="reset" style="margin-top: 30px;">Reset</button>
                                <button onclick="getLocation()" type="submit" class="btn btn-default register" style="margin-top: 30px;">Submit</button>
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

<script src="{{url('public/website/js/custom.js')}}"></script>
<script src="{{url('public/website/js/modernizr.custom.js')}}"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $("#ckbCheckAll").click(function () {
            $(".checkBoxClass").prop('checked', $(this).prop('checked'));
        });

        var rangeSlider = function() {
          var slider = $('.range-slider'),
          range = $('.range-slider__range'),
          value = $('.range-slider__value');

          slider.each(function(){

            value.each(function(){
              var value = $(this).prev().attr('value');
              $(this).html(value);
          });

            range.on('input', function(){
              $(this).next(value).html(this.value);
          });
        });
      };

      rangeSlider();
  });

</script>
<script>
// var x = document.getElementById("demo");

(function(){
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition);
  } else { 
    x.innerHTML = "Geolocation is not supported by this browser.";
  } 
 
})()


function showPosition(position) {
  $("#lat").val(position.coords.latitude);
  $("#lon").val(position.coords.longitude);
}
</script>


<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>

<script>
    jQuery(document).ready(function($) {
        $('.loop').owlCarousel({
            items: 4,
            loop: false,
            itemsDesktop : false,
            autoPlay :false,
            scrollPerPage:false,
            mouseDrag:false,
            touchDrag:false,


            navText: ["<img src='images/client-arrow-rht.png' alt=''/>", "<img src='images/client-arrow-lft.png' alt=''/>"],

            responsiveClass: true,
            responsive: {
                0: {
                    items: 1,
                    nav: true
                },
                600: {
                    items: 4,
                    nav: false,
                    loop: false,
                },

            }
        });
       
        });
    

</script>
 <script>
        jQuery(document).ready(function($) {

   $('.loop2').owlCarousel({
            items: 4,
            loop: true,
             nav: true,

            responsiveClass: true,
            responsive: {
                0: {
                    items: 1,
                    nav: true
                },
                600: {
                    items: 1,
                    nav: true,
                    loop: true,
                },

             
       }
        });
           });


          
</script>
<script>
    $(document).ready(function(){
        var date_input=$('input[name="date"]'); //our date input has the name "date"
        var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
        date_input.datepicker({
            format: 'yyyy-mm-dd',
            container: container,
            todayHighlight: true,
            autoclose: true,
        })
    })
</script>
<!-- <script type="text/javascript">
    $(".public").click(function(){
        var id = $(this).attr('id');
        if($(this).prop('checked')) {
            var name = $(this).next().next().text();
            var image = $(this).next().attr("src");
            $(".pub").append("<div class='item public_item col-md-6' id='"+id+"'><div class='form-group'><input type='checkbox' name='public[]'><img src='"+image+"' style='max-width:40px; max-height:40px;'><label class='check car'>"+name+"</label></div></div>");
        } else {
            $(".public_item#"+id).remove();
        }
    
    });
</script> -->

<!-- <script type="text/javascript">
    $(".music").click(function(){
        var id = $(this).attr('id')
        if($(this).prop('checked')) {
            var name = $(this).next().next().text();
            var image = $(this).next().attr("src");
            $(".mus").append("<div class='item col-xs-2 music_item1' style='float:left;' id='"+id+"'><img src='"+image+"' style='max-width:40px; max-height:40px;'><label class='check car' style='float:left; font-size:10px;'>"+name+"</label></div>");
        } else {
            $(".music_item1#"+id).remove();
        }
    
    });
</script> -->

<script type="text/javascript">
    $("#reset").click(function() {
        location.reload();
    });
</script>

@endsection()
