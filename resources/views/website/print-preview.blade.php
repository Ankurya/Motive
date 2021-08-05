
<link href="{{url('public/website/css/bootstrap.min.css')}}" rel="stylesheet">
<style type="text/css"></style>
<center>
<h1>Ticket List </h1>
<table class="table" >
<tr><th>Id</th><th>Name</th></tr>
@foreach($bought_tickets as $tickets)
@php 
  $formId = "form".$loop->iteration;
@endphp
<tr><td>{{$loop->iteration}}</td>
<td>{{ $tickets->ticket_title }}</td>
</tr>
@endforeach
</center>


<script type="text/javascript" src="http://www.position-absolute.com/creation/print/jquery.printPage.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.2/jquery.min.js"> </script>

 <script src="{{url('public/website/js/bootstrap.min.js')}}"></script>
<script type="text/javascript">
	$(document).ready(function(){
      $('.print').printPage();
    });
</script>

