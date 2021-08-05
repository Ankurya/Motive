 @extends('website.common') @section('title', 'Motive') @section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" />

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
    
    a.btn_primary {
        min-width: 133px;
        padding: 7px 22px;
        font-size: 17px;
        margin-top: 13px;
    }
    
    .modal-body,
    .modal-header {
        background: transparent;
    }
    
    .user-wrap.pop img.new-reds {
        border-radius: 100%;
        width: 40px;
    }
    
    .tab-content {
        padding: 0;
    }
    
    .heading-top {
        padding: 77px 0 0;
    }
    
    .alert-success {
        color: #3c763d;
        background-color: #dff0d8;
        width: 493px;
        margin-left: 320px;
        border-color: #d6e9c6;
    }
    
    .tabs {
        margin-top: 31px;
    }
    
    .new-homesdf {
        float: right;
        display: inline-block;
        padding-top: 10px;
        padding-bottom: 0;
        position: relative;
        top: 0px;
    }
    
    .new-homesdf i {
        font-size: 25px;
        color: #fff;
    }
    
    .heading {
        margin-top: 20px;
        color: white;
        font-size: 20px;
    }
    table#example th, td {
    color: #fff;
}
div.dataTables_wrapper div.dataTables_filter input {
    width: 250px;
    height: 33px;
    font-size: 18px;
    color: #0f0f0f !important;
}



table.table-bordered.dataTable th, table.table-bordered.dataTable td {
    border-left-width: 0;
    padding: 16px;
}
table tbody tr td {
    border-color: unset!important;
}
select.custom-select.custom-select-sm.form-control.form-control-sm {
    height: 30px;
}
div.dataTables_wrapper div.dataTables_filter label{
  color:white;
}
div.dataTables_wrapper div.dataTables_length label {
    color: white;
}
div.dataTables_wrapper div.dataTables_info {
    color: white;
}


#example_filter input.form-control.form-control-sm {
    color: #000 !important;
    font-size: 15px !important;
    font-weight: 600;
}



#qunantity{
    display:none;
}

.new-homesdf {
    display: none;
}

</style>

<section class="bg-color main-section">
    <div class="container">
        <div class="heading-top">
            <h1>CHECK IN</h1>
        </div>
        <div class="tabs">
            <ul class="tab-links">
                <li class="active"><a href="javascript:void(0)">Tickets</a></li>
                <li><a href="{{url('website/guest-list-name',$event_id)}}">Guest List</a></li>
            </ul>
            <div class="new-homesdf">
                <a href='{{url("website/print-ticket/$event_id")}}' class="print"><i class="fa fa-print" style="cursor: pointer;" aria-hidden="true"></i></a>
            </div>
          <!-- <div class="tab-content">
                <div id="tab1" class="tab active">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="form-reflex form-info-settings">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="search-box">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="myInput" onkeyup="myFunction(this)" placeholder="Search">
                                            <img src="{{url('public/website/images/search.png')}}"></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>  -->
        </div>
        <table id="example" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>Ticket Id</th>
                    <th>Ticket Title</th>
                    <th>Ticket Quantity</th>
                    <th>Ticket Individual Purchase</th>
                    <!-- <th>Event Name</th>
                    <th>Event Location </th> -->
                    <th>Event Date</th>
                    <th>Event Start Time</th>
                    <th>Event End Time</th>
                    <th>Name Who Bought Ticket</th>
                    <th>Remaning Ticket</th>
                </tr>
            </thead>
            <tbody>

            <div id ="qunantity">
            {{$quantity=0}}
             @foreach($bought_tickets as $ticket)
               {{$quantity= $quantity+$ticket->quantity}}

             @endforeach
            </div>

                @foreach($bought_tickets as $ticket)
                <tr>
                    <td><a href='{{url("website/event_details/$ticket->event_id")}}'>{{$ticket->ticket_id}}</a></td>
                    <td>{{$ticket->ticket_title}}</td>
                    <td>{{$ticket->ticket_quantity}}</td>
                    <td>{{$ticket->quantity}}</td>
                    <!-- <th>{{$ticket->event_name}}</td>
                    <th>{{$ticket->event_location}}</th> -->
                    <th>{{$ticket->event_date2}}</th>
                    <th>{{date( 'g:i A', strtotime($ticket->event_time ) )}}</th>
                    <th>{{date( 'g:i A', strtotime($ticket->end_time ) )}}</th>
                    <td>{{$ticket->name}}</td>
                    <td>{{$ticket->ticket_quantity-$quantity}}</td>
                </tr> 
                @endforeach

            </tbody>
            
        </table>

</section>

@endsection() @section('js')
<script type="text/javascript" src="http://www.position-absolute.com/creation/print/jquery.printPage.js"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>

<script>
    // $(".print").click(function(){ 
    $(document).ready(function() {
        $('.print').printPage();
    });
    // });
    $(document).ready(function() {
        $('#example').DataTable();
    });

    function myFunction(event) {
        // Declare variables 

        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("myTable");
        tr = table.getElementsByTagName("tr");

        $("#not_found").hide();
        // Loop through all table rows, and hide those who don't match the search query
        let all_tr = tr.length;
        let not_display = 1;
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[0];
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";

                } else {
                    tr[i].style.display = "none";
                    not_display++;
                }
            }
        }

        // console.log(all_tr,not_display)
        if (all_tr == not_display) {
            $("#not_found").show();
        } else {
            $("#not_found").hide();
        }
        not_found = 1;

        if (event.value.length == 0) {
            $("#not_found").hide();
        }
    }
</script>

<script type="text/javascript">
    $('.add').bind('click', function() {
        var i = $(this).attr('id');
        $('#friend_id').val(i);

    });
</script>
@endsection()