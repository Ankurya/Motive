@extends('layouts.admin_hf')   
@section('title', '')  
@section('pageContent')

<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">


<style>
.container1 input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
}

/* Create a custom checkbox */
.checkmark {
  position: absolute;
  top: 0;
  left: 0;
  height: 25px;
  width: 25px;
  background-color: #c1c1c1;
}

/* On mouse-over, add a grey background color */
.container1:hover input ~ .checkmark {
  background-color: #ccc;
}

/* When the checkbox is checked, add a blue background */
.container1 input:checked ~ .checkmark {
  background-color: #2196F3;
}

/* Create the checkmark/indicator (hidden when not checked) */
.checkmark:after {
  content: "";
  position: absolute;
  display: none;
}

/* Show the checkmark when checked */
.container1 input:checked ~ .checkmark:after {
  display: block;
}

/* Style the checkmark/indicator */
.container1 .checkmark:after {
  left: 9px;
  top: 5px;
  width: 5px;
  height: 10px;
  border: solid white;
  border-width: 0 3px 3px 0;
  -webkit-transform: rotate(45deg);
  -ms-transform: rotate(45deg);
  transform: rotate(45deg);
}
label.container1.maintence-checkbox {
    position: relative;
    top: 20px;
}
</style>
    <div class="content">
		<div class="custom-breadcrumb">
		<div class="container-fluid">
		<p><a href="{{url('dashboard')}}"> Home</a> <i class="ti-angle-right"></i>  <span>Send Message Management </span> </p>
		</div>
		</div>
        <div class="container-fluid">
		<div class="col-sm-3">
		<h3>Maintanence</h3></div>
		<div class="col-sm-3">
			<label class="container1 maintence-checkbox">
  <input type="checkbox" id="switch">
  <span class="checkmark"></span>
</label>
</div>
     	</div>
     	</div>


 @endsection('pageContent')
 

 
 @section('jqueryPageContent')
 
 
  
 <script>
 //alert('cvc');
	check_status();
    function check_status(){
	$.ajax({
			async: true,
			url: '<?php echo url('admin/maintenance_status'); ?>',
			method: 'GET',
			dataType: 'text',
			success: function(returnData){
				data = returnData; 
				if(data == '1'){
					//$('#switch').val($(this).is(':checked'));   
					  $("#switch").prop("checked", true);
				}
			}
		});
	}	
	
	
  var jq = $;
 	$("#switch").click(function(){
			$.ajax({
			async: true,
			url: '<?php echo url('admin/maintenance_update'); ?>',
			method: 'GET',
			dataType: 'text',
			success: function(returnData){
				data = returnData; 
				if(data == '1'){
					  $("#switch").prop("checked", true);
				}else{
					 $("#switch").prop("checked", false);
				}
			}
		});
	}); 
 </script>
 
 
 
@endsection('jqueryPageContent')
