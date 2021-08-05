@extends('website.common')
@section('title', 'Motive')
@section('content')

<style>

.card {
    margin-bottom: 1.5rem
}

label {
    color: black !important;
    font-weight: 500 !important;
    font-size: 14px !important;
    text-transform: none !important;
    margin: 5px !important;
}


input#user_id:focus{
    background-color: #2a2a2a;
    opacity: unset;
    color: #fff;
}



.card {
    position: relative;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-direction: column;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 1px solid #c8ced3;
    border-radius: .25rem
}

.card-header:first-child {
    border-radius: calc(0.25rem - 1px) calc(0.25rem - 1px) 0 0
}

.card-header {
    padding: .75rem 1.25rem;
    margin-bottom: 0;
    background-color: #f0f3f5;
    border-bottom: 1px solid #c8ced3
}

.card-body {
    flex: 1 1 auto;
    padding: 1.25rem
}

.form-control:focus {
    color: #5c6873;
    background-color: #fff;
    border-color: #c8ced3 !important;
    outline: 0;
    box-shadow: 0 0 0 #F44336
}



}
input.btn.btn-info.btn-fill.btn-wd.btn-clr.px-0 {
    width: 157px;
    text-transform: uppercase;
    height: 52px;
    font-size: 16px;
    color: black;
    letter-spacing: 1px;
}
</style>

    <div class="row" style="margin: 16px 0px 0px 10px;">
    <div class="col-md-3">
    </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <strong>Enter Card Details</strong>
                </div>
                @if ($errors->any())
				<div class="alert alert-danger">
					<ul>
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
						</ul>
					</div>
				@endif
				@if(Session::has('message'))
					<p class="alert alert-success">{{ Session::get('message') }}</p>
				@endif
                <form method="post" action={{url('website/add-details-insert')}}>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="card_title">
                                    <label for="name">Name On Card</label>
                                </div>    
                                <input class="form-control" name="card_name" id="card_name" type="text" >
                            </div>{{csrf_field() }} 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="card_title">
                                    <label for="ccnumber">Card Number</label>
                                </div>    
                                    <input class="form-control" name="card_number" id="card_number" type="text"  autocomplete="email">
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="mdi mdi-credit-card"></i>
                                        </span>
                                    </div>

                            </div>
                        </div>
                    </div>



                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="card_title">
                                    <label for="ccnumber">User Id</label>
                                </div>    
                                    <input  class="form-control" type="text" name="user_id" id="user_id"  type="text" value="{{$user_id }}" readonly/>   
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="mdi mdi-credit-card"></i>
                                        </span>
                                    </div>

                            </div>
                        </div>
                    </div>
                   
                </div>
                <div class="card-footer" style="text-align: center;padding-bottom: 10px;">
                    <input class="btn btn-info btn-fill btn-wd btn-clr px-0"  width="30" type="submit" value="Submit">
                </div>
            </div>
        </div>
    <div class="col-md-3">
    </div>    
    </div>


@endsection()