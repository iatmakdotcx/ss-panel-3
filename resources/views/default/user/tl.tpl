{include file='user/main.tpl'}
<!-- echarts -->
<script src="/assets/public/js/echarts.pie.min.js" type="text/javascript"></script>
<style>
	.chart {
		height: 400px;
		margin: 0px;
		padding: 0px;
	}
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            流量使用情况
            <small>Traffic Log</small>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="callout callout-warning">
                    <h4>注意!</h4>
                    <p>部分节点不支持流量记录.</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body table-responsive no-padding">
                        <div class="chart" id="pieChart"></div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<script>
var trafficDisplay = function(traffic){
	if(traffic < 1024 * 8){
		return traffic + "B";
	}
	if(traffic < 1024 * 1024 * 2){
		return (traffic / 1024.0).toFixed(2) + "KB";
	}
	if(traffic / 1048576.0 < 1024){
		return (traffic / 1048576.0).toFixed(2) + "MB";
	}
	return (traffic / (1048576.0 * 1024.0)).toFixed(2) + "GB";
}
var pieChart = echarts.init(document.getElementById('pieChart'));
pieChart.setOption({
		tooltip: {
			trigger: 'item',
			formatter: function (params) {
				return trafficDisplay(params.value) + '(' + params.percent+"%)";
			}
		},
		calculable: false,
		series: [{
			name: '全员流量统计',
			type: 'pie',
			radius: '65%',
			center: ['50%', '50%'],
			selectedMode: 'single',
			data: {$pieChartValue}
		}]
	});
</script>
{include file='user/footer.tpl'}
