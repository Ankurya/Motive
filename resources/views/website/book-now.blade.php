@extends('website.common')
@section('title', 'Motive')

@section('content')
<style type="text/css">
  .heading-top {
    padding: 50px 0;
}
.tab-content {
    width: 100%;
}
.quanity-control {
    float: right;
    border: 1px solid #feda71;
    width: 112px;
        border-radius: 7px;

}
 .quanity-control span.input-group-btn {
    display: inline-block;
    width: 30px;
    float: left;
}
.quanity-control input[type='text'] {
    width: 50px;
    height: 35px;
    position: relative;
    border: none;
    display: inline-block;
    border-radius: 0px;
    float: left;
    text-align: center;
    padding: 0;
        color: #fff;
    font-weight: 700;
}
.quanity-control span.input-group-btn {
    display: inline-block;
    width: 30px;
    float: left;
}
button.quantity-left-minus.btn.btn-danger.btn-number, button.quantity-right-plus.btn.btn-success.btn-number {
    background-color: #feda71;
    color: #505050;
    height: 35px;
    font-size: 12px;
    border: none;
    float: left;
    margin: 0;
    padding: 4px 9px;

}

.form-reflex.book-now .form-group .user-wrap label{
      margin-left: 0;

}

</style>
<section class="bg-color main-section"> 
  <div class="container">
    <div class="heading-top">
      <h1>select tickets</h1>
    </div>
    <div class="tabs">
      <div class="tab-content">
        <div id="tab1" class="tab active">
          <div class="col-md-6 col-md-offset-3">
              <form method="post" onsubmit="return submit_form();">
                {{csrf_field()}}
           <div class="form-reflex form-info-settings book-now">
            <div class="row">
              @foreach($tickets as $ticket)
              <div class="col-md-12">
                <div class="form-group">
                  <div class="user-wrap margin-bottom-30">
                   <div class="col-sm-9">
                     <label>{{$ticket->ticket_title}}</label>
                     <p>
                       {{$ticket->ticket_description}}
                     </p>
                   </div>     
                   <div class="col-sm-3">
                    <a id="{{$ticket->id}}" href="javascript:;" class="btn_primary btn-small add">add</a>
                    <div class="quanity-control quan" id="{{$ticket->id}}">
                        <span class="input-group-btn">
                          <button type="button" class="quantity-left-minus btn btn-danger btn-number minus"  data-type="minus" data-field="quant[{{$ticket->id}}][]">
                          <span class="glyphicon glyphicon-minus"></span>
                          </button>
                          </span>
                               <input type="text" name="quant[{{$ticket->id}}][]" id ="{{$ticket->id}}" class="form-control input-number input" value="" min="1" max="{{$ticket->ticket_quantity}}">
                          <span class="input-group-btn">
                          <button type="button" class="quantity-right-plus btn btn-success btn-number plus" data-type="plus" data-field="quant[{{$ticket->id}}][]">
                          <span class="glyphicon glyphicon-plus"></span>
                          </button>
                          </span>
                    </div>
                     <label class="book-now-value">&#163;{{$ticket->ticket_amount}}</label>
                   </div>     
                 </div>
               </div>
             </div>
            @endforeach

   <div class="col-md-12 margin-top-30">
    <div class="col-sm-12 text-center">
     <button type="submit" class="btn btn-default register date">BUY TICKET</button>
   </div>
 </div>
 </form>

 
</div>
</div>
</div>
</div>
</div>
</section>
@endsection()
@section('js')
<script type="text/javascript">
 $('.btn-number').click(function(e){
    e.preventDefault();
   
    fieldName = $(this).attr('data-field');
    type      = $(this).attr('data-type');
    var input = $("input[name='"+fieldName+"']");
    var currentVal = parseInt(input.val());

    if (!isNaN(currentVal)){
        if(type == 'minus') {
           let input_field = $(this).parent().next().val();
           if(currentVal > input.attr('min')) {
                input.val(currentVal - 1).change();

            } 
           if(input_field){
            if(parseInt(input_field) == 1){
              var val = $(this).parent().next().val('');
              $(this).parent().parent().hide()

             // console.log($(this).parent().next().val())
             $(this).parent().parent().prev().show();
             
            }
           }
          
        } else if(type == 'plus') {
            if(currentVal < input.attr('max')) {
                input.val(currentVal + 1).change();
            }
            if(parseInt(input.val()) == input.attr('max')) {
                $(this).attr('disabled', true);
            }

        }
    } else {
        input.val(0);
    }
});
$('.input-number').focusin(function(){
   $(this).data('oldValue', $(this).val());
});
$('.input-number').change(function() {
    
    minValue =  parseInt($(this).attr('min'));
    maxValue =  parseInt($(this).attr('max'));
    valueCurrent = parseInt($(this).val());
    
    name = $(this).attr('name');
    if(valueCurrent >= minValue) {
        $(".btn-number[data-type='minus'][data-field='"+name+"']").removeAttr('disabled')
    } else {
        alert('Sorry, the minimum value was reached');
        $(this).val($(this).data('oldValue'));
    }
    if(valueCurrent <= maxValue) {
        $(".btn-number[data-type='plus'][data-field='"+name+"']").removeAttr('disabled')
    } else {
        alert('Sorry, the maximum value was reached');
        $(this).val($(this).data('oldValue'));
    }
    
    
});
$(".input-number").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
             // Allow: Ctrl+A
            (e.keyCode == 65 && e.ctrlKey === true) || 
             // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
</script>

<script type="text/javascript">
  // var idd = $(".quan").attr('id')
  $(".quan").hide();
  $(".add").click(function() {
    var id = $(this).attr('id')
    $(this).hide();
    $(this).next().show();
  });


  // $(".minus").click(function(){
  //   var value = $(".input").val();
  // });
</script>

<script type="text/javascript">
  
  $('.add').click(function(){
    $(this).next().find("input").val(1);
    $(this).next().find("input").attr("value",1);
  });
  $(".quantity-left-minus").click(function(){
    $(this).parent().next().attr("value",'')
  })
</script>
<script type="text/javascript">
  function submit_form() {
    var all = 0;
    var empty = 0;

    var input = $(".input").each(function(i){
      all++;
      if($(this).val() == "" || $(this).val() == 0 && parseInt($(this).attr("value") < 1)){
        empty++;
       }
    });
    if(empty == all){
       $('.submit').prop('disabled', true);
      return false;
    }else {
      $('.submit').prop('disabled', false);
      return true;
    }
   
  }
</script>


@endsection()