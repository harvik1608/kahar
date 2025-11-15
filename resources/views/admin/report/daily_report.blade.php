@extends('include.header')
@section('content')
<div class="page-header">
    <div class="page-title">
        <h4>Daily Report</h4>
        <h6></h6>
    </div>
</div>
<div class="row">
    <div class="col-xxl-9 col-lg-6 d-flex">
        <div class="card flex-fill">
            <div class="card-header pb-2 d-flex align-items-center justify-content-between flex-wrap">
                <h5 class="mb-2">Daily Sale Report</h5>                             
                <input type="date" id="report_chart_1" value="<?php echo date('Y-m-d'); ?>" />
            </div>
            <div class="card-body">
                <div id="company-chart"></div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('assets/js/apexcharts.min.js') }}"></script>
<script>
	var page_title = "Daily Report";
    $(document).ready(function(){
        if($('#company-chart').length > 0) {
            load_chart1();
        }
        $("#report_chart_1").change(function(){
            load_chart1();
        });
    });
    function load_chart1()
    {
        $.ajax({
            url: "{{ route('admin.load_date_wise_chart') }}",
            type: "GET",
            data:{
                date: $("#report_chart_1").val()
            },
            success:function(response){

                // Destroy previous chart before creating new one
                var companyChart = null;
                if (companyChart !== null) {
                    companyChart.destroy();
                }

                var sColStacked = {
                    chart: {
                        height: 240,
                        type: 'bar',
                        fontFamily: 'Nunito, sans-serif', 
                        toolbar: { show: false }
                    },
                    colors: ['#212529'],
                    series: [{
                        name: 'Company',
                        data: response.values
                    }],
                    xaxis:{
                        categories: response.categories,
                        labels: {
                            style: {
                                colors: '#6B7280',
                                fontSize: '13px',
                            }
                        }
                    },
                    yaxis: {
                        labels: { show: false }
                    },
                    dataLabels: { enabled: false },
                    fill: { opacity: 1 }
                }

                companyChart = new ApexCharts(document.querySelector("#company-chart"), sColStacked);
                companyChart.render();
            }
        });
    }

</script>
@endsection
