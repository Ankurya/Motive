@extends('website.common')
@section('title', 'Motive')

@section('content')
<style type="text/css">
table#myTable tr td {
    width: 100%;
}
.user-wrap {
    margin-bottom: 21px;
}
.user-wrap img {
    border-radius: 100%;
    width: 39px;
}

.modal-content {
    max-width: 363px;
    margin: 133px auto;
    height: 173px;
    width: 100%;
    background: #0f0f0f;


}
.tab-content {
    padding: 43px 0 0 0;
    width: 100%;
}
a.btn_primary{
  min-width: 133px;
  padding: 7px 22px;
  font-size: 17px;
  margin-top: 13px;
}
.modal-body,.modal-header{
    background: transparent;
}
.user-wrap.pop img.new-reds{
  border-radius: 100%;
  width: 40px;
}
.tab-content {
    padding: 0;
}

.heading-top {
    padding: 20px 0 0;
}

.alert-success {
    color: #3c763d;
    background-color: #dff0d8;
    width: 493px;
    margin-left: 320px;
    border-color: #d6e9c6;
}
.tab-content{
    margin: 0 0;

}
</style>
<section class="bg-color main-section">
    <div class="container">
        <div class="heading-top">
            <h4 class="text-center" style="margin-bottom:20px;color:#d5bd66;font-size:24px">Event Description</h4>
        </div>
        <div class="tabs">
            <div class="tab-content">
                <div id="tab1" class="tab active">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="form-reflex form-info-settings">
                            <div class="row">   
                                <div class="col-md-6">
                                <label>Event Name</label>
                                </div>
                                <div class="col-md-6">
                                    <label>{{$get_event[0]->event_name}}</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Event Location</label>
                                </div>
                                <div class="col-md-6">
                                    <label>{{$get_event[0]->event_location}}</label>
                                </div>    
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Description</label>
                                </div>
                                <div class="col-md-6">
                                    <label>{{$get_event[0]->description}}</label>
                                </div>    
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Event Date</label>
                                </div>
                                <div class="col-md-6">
                                    <label>{{$get_event[0]->event_date}}</label>
                                </div>    
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                        <label>Event Time</label>
                                </div>
                                <div class="col-md-6">
                                <label>{{$get_event[0]->event_time}}</label>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection()
