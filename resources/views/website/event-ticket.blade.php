@extends('website.common')
@section('title', 'Motive')

@section('content')
<style type="text/css">
.first-tickets-alert  .new-file figure {
  position: absolute;
  top: -12px;
  right: -29px;
  font-size: 16px;
  color: #fff;
  background: #000;
  border-radius: 100%;
  height: 30px;
  width: 30px;
  text-align: center;
  line-height: 29px;
}
.first-tickets-alert .new-file {
  position: relative;
}
.modal-header {
    padding: 15px;
    border-bottom:initial;
    background: transparent;
    }
    .modal-body {
    position: relative;
    padding: 15px;
    background: transparent;
    border-radius: 0;
    box-shadow: none;
}
.modal-content{
  box-shadow:none;
  border: none;
}
.first-tickets-alert .new-file {
    min-height: 243px;
    max-width: 479px;
}
.first-tickets-alert .new-good.good p{
      min-height: 100px;

}
.first-tickets-alert .new-find {
    display: unset;
}
.new-good.good {
    display: inline-block;
}

.left-good {
    display: inline-block;
        float: right;

}
.global p {
    font-size: 18px;
    color: #fff;
        word-break: break-all;

}
.left-good p {
    color: #fff;
}
.new-jacks-quantity p {
    font-size: 20px;
    color: #fff;
}
.first-tickets-alert .new-jacks-quantity {
    margin-top: 15px;
    text-align: right;
}
.heading-top {
    padding: 77px 0 0;
}

.alert.alert-success.alert-dismissible.text-center.alertz {
    width: 500px;
    margin-left: 500px;
    margin: 0 auto;
}


</style>
<div class="heading-top">
    <h1>Tickets</h1>
</div>

@include('website.notifications_message')
<div class="first-tickets-alert" id="new-file line" >
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <div class="modal-dialog">
        <div class="modal-content" style="background-color: transparent;">
          <div class="modal-body ">
            <div id="tab1" class="tab active">

              <div class="form-reflex form-info-settings adit">
                <div class="row">
                  <div class="col-md-12">
                    @if($tickets && !empty($tickets) && count($tickets) > 0)
                    @foreach($tickets as $ticket)
                    <div class="new-file">
                      <a href="{{url('website/delete-ticket',$ticket->id)}}">
                        <figure>
                          <i class="fa fa-times" aria-hidden="true"></i>
                        </figure>
                      </a>
                      <div class="col-md-12">
                        <div class="form-group">
                          <div class="new-find">
                            <div class="new-good good">
                              <label style="text-transform: capitalize;">{{$ticket->ticket_title}}</label>
                          
                            </div>
                            <div class="left-good">
                               <p>&#163;{{$ticket->ticket_amount}}</p>

                            </div>

                          </div>
                          <div class="global">
                               <textarea class="form-control" style="height: 130px; border:0px;padding-left: 0;" disabled="">{{$ticket->ticket_description}}</textarea>
                              </div>
                          
                           <div class="new-jacks-quantity">
                            <label class="new-time" style="text-transform: capitalize;">Tickets Available:</label>
                            <p>{{$ticket->ticket_quantity}}</p>
                          </div>
                        </div>
                      </div>
                    </div>  
                  @endforeach
                  @else
                  <h1 style="font-size:30px; font-weight: 500; color: #fff;text-align:center;}">No Ticket Added</h1>
                  @endif
                  </div>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  @endsection()