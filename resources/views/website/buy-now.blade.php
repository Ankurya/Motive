@extends('website.common')
@section('title', 'Motive')

@section('content')
<style type="text/css">
  
.form-reflex .form-group .user-wraps label.book-now-value.new{
      margin-top:0px;

}
.form-reflex .form-group .user-wraps label.book-now-value{
        float: none;

}
.new-homep {
    text-align: right;
}
label.book-now-value p {
    display: inline-block;
}

</style>
    <section class="bg-color main-section homeer">
        <div class="container">
            <div class="heading-top">
                <h1>BUY NOW</h1>
            </div>
            <div class="tabs">
                <div class="tab-content">
                    <div id="tab1" class="tab active">
                        <div class="col-md-6 col-md-offset-3">
                            <div class="form-reflex form-info-settings book-nows">
                                <div class="row">
                                    @foreach($final_data as $ticket)
                                    @if($ticket['ticket_quantity'] > 0)
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="user-wraps margin-bottom-30">
                                               <a href="{{url('website/delete-buy-ticket',$ticket['id'])}}" class=""><img src="{{url('public/website/images/cross-button.png')}}"></a>
                                                <div class="col-sm-5">
                                                    <label class="news">{{$ticket['ticket_title']}}</label>
                                                    <p>{{$ticket['ticket_description']}}</p>
                                                </div>
                                                <div class="col-sm-7">
                                                    <div class="new-homep">
                                                  
                                                    <label class="book-now-value new">&#163;{{$ticket['ticket_amount'] * $ticket['ticket_quantity']}}</label>
                                                    <label class="book-now-value values">Quantity: <p>{{ $ticket['ticket_quantity']}} </p></label>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @endforeach()

                                    <div class="col-md-12">
                                        <div class="col-sm-12 text-center no-padding">
                                            <div class="new-formuls-buy">
                                                <div class="new-jack">
                                                    <p>Sub Total</p>
                                                    <span>&#163;{{$total_amount}}</span>
                                                </div>
                                                <div class="new-jack">
                                                    <p>Booking Fee</p>
                                                    <span>&#163;1.25</span>
                                                </div>
                                                <div class="new-jack home">
                                                    <p>Total</p>
                                                    <span>&#163;{{$total_amount + 1.25}}</span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                 <div class="col-md-12">
                                   <div class="new-kite">
                                    <a href="javascript:void(0)" class="btn btn-default register date">Proceed for payment</a>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>



            </div>
        </div>
    </section>
    
    

@endsection()
