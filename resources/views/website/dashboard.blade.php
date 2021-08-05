@extends('website.common')
@section('title', 'Motive')

@section('content')
<style type="text/css">
	rect.bar_item {
    fill: #f5cf5f!important;
        opacity: 1!important;

}
.sold-tickets h2 {
    font-size: 20px;
    color: #fff;
}
.sold-tickets p {
	font-size: 18px;
    color: #fff;
}
.sold-tickets {
    margin-bottom: 27px;
    margin-top: 41px;
    margin-left: 13px;


}
section.bg-color.main-section {
    background: #0f0f0f;
    padding-bottom: 46px;
}
.bcBar .yaxis text, .bcBar .xaxis text {
    fill: #fff!important;
    }
    path.domain {
    stroke: #fff!important;
}
line {
    stroke: #fff;
}
.fill {
    color: #000!important;
}
.legend_div {
    display: none;
}
.total.sales h2 {
    font-size: 30px;
    color: #000;
}
.total.sales p {
    font-size: 20px;
    color: #000;
}
.total.sales {
    display: inline-block;
    background: #f5cf5f;
    padding: 20px 35px;
    border-radius: 14px;
    margin-bottom: 41px;

}
p.homes {
    font-weight: 600;
}
/*div#line-chart-daily {
    margin-top: 55px;
}*/

.news {
    margin-bottom: 92px;
}
</style>
<link rel="stylesheet" href="{{url('public/website/css/bar.chart.min.css')}}">
<section class="bg-color main-section">
    <div class="container">
        <div class="heading-top">
            <h1>Dashboard</h1>
        </div>
        <div class="total sales">
        	<h2>TOTAL SALES</h2>
        	<p class="homes"> &#163;{{$total_amount}}</p>
        </div>
        <div class="tabs">
                <h1 style="font-size: 20px;color:#fff;margin-bottom: 42px;">SALE SUMMARY</h1>
                <div class="card">
                
                <div  class="content col-sm-12">

                   <div class="row">
                        <div class="news">
                            <div class="col-md-4">
                                <select id="type" name="type" class="selectpicker form-control">
                                    <option value="" disabled>Select Type</option>
                                    <option value ="daily">Daily</option>
                                    <option value="weekly">Weekly</option>
                                    <option value="monthly">Monthly</option>
                                    <option value="yearly">Yearly</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select name="year" id="year" class="selectpicker form-control">
                                    <option selected disabled>Select Year</option>
                                    @for($i = $min; $i <= $max; $i++)
                                        <option value="{{ $i }}" >{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select name="chart_type" id="chart_type" class="selectpicker form-control">
                                    <option selected disabled>Select Chart Type</option>
                                        <option value="spline" >Spline</option>
                                        <option value="column" >Column</option>
                                </select>
                            </div>
                        </div>
                        <div class="add-product-dashboard">
                          <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="table-responsive">
                                    <div id="line-chart-daily" class="line-chart">
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="table-responsive">
                                    <div id="line-chart-weekly" class="line-chart">
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="table-responsive">
                                    <div id="line-chart-monthly" class="line-chart">
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="table-responsive">
                                    <div id="line-chart-yearly" class="line-chart">
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
      
               
              

            <div class="sold-tickets">
            	<h2>Tickets Sold</h2>
            	<p>{{$total_buy}}/{{$total_tickets}}</p>
            </div>
            <div class="sold-tickets">
            	<h2>{{'Guest Available'}}</h2>
            	<p>{{$guest_event_user}}</p>
            </div>
            @foreach($tickets as $ticket)
            <div class="sold-tickets">
            	<h2>{{$ticket->ticket_title}}</h2>
            	<p>{{$ticket->ticket_sold}}/{{$ticket->ticket_quantity}}</p>
            </div>
            @endforeach()

        </div>
    </div>
</section>
@endsection()
@section('js')
<script src="{{url('public/website/js/chartist.min.js')}}"></script>
<script src="{{url('public/website/js/highcharts.js')}}"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>


<script>

    $(document).ready(function() {
        var type = "daily";
        var year = "{{date('Y')}}";
        // alert(year);
        var chart_type = "spline";
        $("select[id='year']").val(year);
        $("select[id='chart_type']").val(chart_type);
        chartDriver(type, year, chart_type);
        $('#year').on('change', function() {
            year = $(this).val();
            chartDriver(type, year, chart_type);
        });
        $('#type').on('change', function() {
            type = $(this).val();
            chartDriver(type, year, chart_type);
        });
        $('#chart_type').on('change', function() {
            chart_type = $(this).val();
            chartDriver(type, year, chart_type);
        });
    });

    function chartDriver(type, year, chart_type) {

      // alert(chart_type);
        var data = {
            "_token":   "{{csrf_token()}}",
            "type":     type,
            "year":     year,
            "role":     "revenue",
            "event_id": "{{$event_id}}"
        };
        $.ajax({
            type:   'post',
            url:    "{{url('website/analytics')}}",
            data:   data,
            success: function(result) {
                console.log(result);
                var result = JSON.parse(result);
                //console.log(result);
                if(type == 'daily'){
                    $('#line-chart-weekly').hide();
                    $('#line-chart-monthly').hide();
                    $('#line-chart-yearly').hide();
                     $('#line-chart-daily').show();
                    $('#year').show();
                    dailyChart(result.daily_data,result.daily_data_footer, chart_type);

                 }else if (type == 'weekly'){ 
                    $('#line-chart-daily').hide();   
                    $('#line-chart-weekly').show();
                    $('#line-chart-monthly').hide();
                    $('#line-chart-yearly').hide();
                    $('#year').show();

                    weeklyChart(result.week_data,result.week_data_footer, chart_type);
                } else if (type == 'monthly'){
                    $('#line-chart-weekly').hide();
                    $('#line-chart-monthly').show();
                    $('#line-chart-yearly').hide();
                     $('#line-chart-daily').hide();
                    $('#year').show();
                    monthlyChart(result.month_data,result.month_data_footer, chart_type);
                } else if (type == 'yearly') {
                    $('#line-chart-weekly').hide();
                    $('#line-chart-monthly').hide();
                    $('#line-chart-yearly').show();
                     $('#line-chart-daily').hide();
                    $('#year').hide();
                    yearlyChart(result.year_data, chart_type)
                }
            }
        });
    }


     function dailyChart(data,$footer_name, chart_type){
            //console.log(data);
             // console.log($footer_name);
             //   console.log(chart_type);
        var temp = [];
        var mapdata = [];
        var day = [];
        var year = new Date().getFullYear();
        // $(data).each(function(index,val) {
        //      //console.log( index + ": " +val );
        //     var temp = [];
        //     year = val.year;
        //     temp.push(Date.UTC(val.year, val.day));
        //     temp.push(val.value);
        //     mapdata.push(temp);
        // });

        Highcharts.chart('line-chart-daily', {
            chart: {
                type: chart_type
            },

            title: {
                text: 'Daily Analytics'
            },

            subtitle: {
                // text: 'Irregular time data in Highcharts JS'
            },

            yAxis: {
                min: 0,
                title: {
                    text: ''
                },
                allowDecimals: false,
            },
            tooltip: {
              formatter: function () {
                  return "Day: " + this.x + '<br/>' + ': ' + this.y;
              }
            },
            series: [{
                name: '',
                data:data,
            }]
        });

        //console.log(data);

    }

    function weeklyChart(data,footer_name, chart_type) {
        var categoryLabels = [];
        for(var i = 0; i <= 52; i++){
            categoryLabels.push("Week - "+i);
        }

        var mapdata = [];
        var total = 0;
        $(data).each(function(index,val) {
            // console.log( index + ": " +val );
            var temp = [];
            temp.push(val);
            total += val;
            mapdata.push(temp);
        });

        $('#total').html(total);
        //console.log(mapdata);
        Highcharts.chart('line-chart-weekly', {
            chart: {
                type: chart_type,
                inverted: false,
            },
            title: {
                text: 'Weekly Analytics'
            },
            xAxis: {
                type: 'linear',
                labels: {
                    formatter: function() {
                        return categoryLabels[this.value];
                    }
                }
            },
            yAxis: {
                title: {
                    text: ''
                },
                allowDecimals: false,
            },


            tooltip: {
                formatter: function () {
                    return categoryLabels[this.x] + '<br/>' + '} : ' + Highcharts.numberFormat(this.y,0);
                }
            },
            series: [{
                name: '',
                // console.log($data);
                data: mapdata,

            }]

        });
    }



    function monthlyChart($data,$footer_name, chart_type){
        console.log( $data );
        var temp = [];
        var mapdata = [];
        var year = new Date().getFullYear();
        $($data).each(function(index,val) {
            // console.log( index + ": " +val );
            var temp = [];
            year = val.year;
            temp.push(Date.UTC(val.year, val.month));
            temp.push(val.value);
            mapdata.push(temp);
            // console.log(temp);
        });


        Highcharts.chart('line-chart-monthly', {
            chart: {
                type: chart_type
            },
            title: {
                text: 'Monthly Analytics'
            },
            subtitle: {
                // text: 'Irregular time data in Highcharts JS'
            },
            xAxis: {
                type: 'datetime',
                tickInterval: 30 * 24 * 3600 * 1000,
                min: Date.UTC(year, 0),
                max: Date.UTC(year, 11),
                // startOnTick: true,
                labels: {
                    // format: '{value:%B/%Y}',
                    format: '{value:%b %Y}',
                    y: 30,
                    x: -20,
                    align: 'center',
                    autoRotationLimit: 100
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: ''
                },
                allowDecimals: false,
            },
            tooltip: {
                formatter: function () {
                    return Highcharts.dateFormat('%b - %Y', new Date(this.x)) + '<br/>' + ': ' + this.y;
                }
            },

            series: [{
                name: '',
                data: mapdata,
            }]
        });
    }

    function yearlyChart($data, chart_type){

        // console.log($data);
        var temp = [];
        var mapdata = [];
        var year = new Date().getFullYear();
        $($data).each(function(index,val) {
            // console.log( index + ": " +val );
            var temp = [];
            year = val.year;
            temp.push(Date.UTC(val.year));
            temp.push(val.value);
            mapdata.push(temp);
        });

        Highcharts.chart('line-chart-yearly', {
            chart: {
                type: chart_type
            },
            title: {
                text: 'Yearly Analytics'
            },
            subtitle: {
                // text: 'Irregular time data in Highcharts JS'
            },
            xAxis: {
                type: 'datetime',
                //tickInterval: 30 * 24 * 3600 * 1000,
                //min: Date.UTC(year),
                //max: Date.UTC(year),
                // startOnTick: true,
                labels: {
                    // format: '{value:%B/%Y}',
                    format: '{value:%Y}',
                    y: 30,
                    x: -20,
                    align: 'center',
                    autoRotationLimit: 100
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: ''
                },
                allowDecimals: false,
            },
            tooltip: {
                formatter: function () {
                    return Highcharts.dateFormat('%b - %Y', new Date(this.x)) + '<br/>' + ': ' + this.y;
                }
            },
            series: [{
                name: '',
                data: mapdata,
            },]
        });
    }
</script>
@endsection()