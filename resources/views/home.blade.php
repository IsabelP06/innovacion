@extends('layouts.app')
@section('title')
@endsection
@section('content')
    <div class="container-fluid">
        <div class="w-100 my-2 d-print-none">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="iniciofiltro" class="fw-bold">Inicio : </label>
                            <input type="date" name="inicio" id="iniciofiltro"
                                class="form-control-sm border_controls_filter">
                        </div>
                        <div class="col-md-3">
                            <label for="finfiltro" class="fw-bold">Fin : </label>
                            <input type="date" name="fin" id="finfiltro" class="form-control-sm border_controls_filter">
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-primary btn-sm" id="btnupdatecharts"> <i
                                    class="fa fa-search"></i>&nbsp;
                                Buscar</button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-danger btn-sm" onclick="print()" id="btnreporte"> <i
                                    class="fa fa-file"></i>&nbsp;
                                Reporte</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="reportPage">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-12 mt-2">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="fs-5">Conformidad con entrega de pedidos</h3>
                                <canvas id="pieChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-2">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="fs-5">Motivos de no conformidad</h3>
                                <canvas id="comboChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mt-2">
                <div class="card">
                    <div class="card-body">
                        <h3 class="fs-5">No conformidades por empresa</h3>
                        <canvas id="barChart2"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')

    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script>
    <script>
        function updateEstado(param) {}
        var ctx = document.getElementById("pieChart");
        ctx.height = 130;
        var pieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: [""],
                datasets: [{
                    label: "Conformidad",
                    data: [0],
                    borderColor: "rgba(0, 194, 146,0.1)",
                    borderWidth: "0",
                    backgroundColor: ["#76ff03", "#ff1744", "#ffab40"]
                }]
            },
            options: {
                plugins: {
                    datalabels: {
                        color: '#000',
                        backgroundColor: '#ffffff',
                        borderRadius: 5,
                        font: {
                            weight: 'bold',
                            size: 13
                        },

                    }
                }
            }

        });
        pieChart.update();

        function updatePieChart(data) {
            const labels = [];
            const cantidad = [];
            const colores=[];
            data.forEach((element) => {
                labels.push(element.estado);
                if(element.estado=="OK"){
                    colores.push("#76ff03");
                }else if(element.estado=="NO OK"){
                    colores.push("#ff1744");
                }else{
                    colores.push("#ffab40");
                }
                cantidad.push(element.cantidad);
            });
            if (!data.length) {
                labels.push("sin resultados");
                cantidad.push(1);
            }
            pieChart.config.data.datasets[0].data = [];
            pieChart.config.data.datasets[0].backgroundColor = [];
            pieChart.config.data.labels = [];
            pieChart.update();
            pieChart.config.data.datasets[0].data.push(...cantidad);
            pieChart.config.data.datasets[0].backgroundColor.push(...colores);
            pieChart.config.data.labels.push(...labels);
            pieChart.update();
        }
        var ctx2 = document.getElementById("barChart2");
        var horizontalBarChart = new Chart(ctx2, {
            type: 'horizontalBar',
            data: {
                labels: [
                    ""
                ],
                axis: 'y',
                datasets: [{
                    label: "Cantidad",
                    data: [2],
                    borderColor: "#ff867f",
                    borderWidth: "0",
                  
                    backgroundColor: "#ff867f"
                }]
            },
            options: {
                plugins: {
                    datalabels: {
                        color: '#000',
                        backgroundColor: '#ffffff',
                        borderRadius: 5,
                        font: {
                            weight: 'bold',
                            size: 13
                        },
                        formatter: (value, ctx) => {
                            let sum = 0;
                            let dataArr = ctx.chart.data.datasets[0].data;
                            dataArr.map(data => {
                                sum += data;
                            });
                            let percentage = (value * 100 / sum).toFixed(2) + "%";
                            return percentage;
                        },
                    }
                },
                scales: {
                    xAxes: [{
                        ticks: {
                            beginAtZero: true,
                            stepSize: 2
                        }
                    }],
                    yAxes: [{
                        ticks: {
                           fontSize: 9
                        }
                    }]

                }
            }
        });

        function updateHorizontalBar(data) {
            const labels = [];
            const cantidad = [];
            data.forEach((element) => {
                var modlabel=element.transportista.split(' ');
                var arraynuevo=[];
                if(modlabel.length>3 && modlabel.length<7){
                    arraynuevo.push(modlabel.slice(0,3).join(' '));
                    arraynuevo.push(modlabel.slice(3).join(' '));
                }else if(modlabel.length>6){
                    arraynuevo.push(modlabel.slice(0,3).join(' '));
                    arraynuevo.push(modlabel.slice(3,6).join(' '));
                    arraynuevo.push(modlabel.slice(6).join(' '));
                }else{
                    arraynuevo.push(modlabel);
                }
                labels.push(arraynuevo);
                cantidad.push(element.cantidad);
            });
            horizontalBarChart.config.data.datasets[0].data = cantidad;
            horizontalBarChart.config.data.labels = labels;
            horizontalBarChart.update();
        }
        var ctx3 = document.getElementById("comboChart");
        var comboChart = new Chart(ctx3, {
            type: 'bar',
            data: {
                labels: ["BARRA CORTA"],
                datasets: [{
                    type: 'line',
                    label: 'Observaciones',
                    data: [0],
                    borderwidth: "2",
                    borderColor: "orange",
                    backgroundColor: "rgba(253, 94, 1, 0.5)",
                    datalabels:{
                        align: 'start',
                        anchor: 'end',
                        borderRadius:5,
                        backgroundColor: "#fff",
                        color: '#000',
                        font: {
                            weight: 'bold',
                            size: 13
                        },
                        formatter: (value, ctx) => {
                            let sum = 0;
                            let sum2 = 0;
                            let dataArr = ctx.chart.data.datasets[0].data;
                            let max=dataArr[dataArr.length-1];
                            let percentage = (value * 100 / max).toFixed(2) + "%";
                            return percentage;
                        },

                    }
                    
                }, {
                    type: 'bar',
                    label: '',
                    data: [0],
                    backgroundColor: "red",
                    datalabels: {
                        color: '#000',
                        backgroundColor: '#ffffff',
                        borderRadius: 5,
                        font: {
                            weight: 'bold',
                            size: 13
                        },
                        align: "center",
                        anchor:"end"


                    }
                }]
            },
            options: {
               
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            stepSize: 1,

                        }
                    }],
                    xAxes: [{
                        ticks: {
                            autoSkip: true,
                            maxRotation: 0,
                            minRotation: 0,
                            fontSize:9
                        }
                    }]
                }
            }
        });

        function updateCombroChart(data) {
            const labels = [];
            const cantidad = [];
            const cantidad2 = [];
            data.forEach((element) => {
                var modlabel=element.observacion.split(' ');
                var arraynuevo=[];
                if(modlabel.length>3 && modlabel.length<7){
                    arraynuevo.push(modlabel.slice(0,2).join(' '));
                    arraynuevo.push(modlabel.slice(2).join(' '));
                }else if(modlabel.length>6){
                    arraynuevo.push(modlabel.slice(0,2).join(' '));
                    arraynuevo.push(modlabel.slice(2,5).join(' '));
                    arraynuevo.push(modlabel.slice(5).join(' '));
                }else{
                    arraynuevo.push(modlabel);
                }
                labels.push(arraynuevo);
                cantidad.push(element.cantidad);
                cantidad2.push(element.cantidad);
            });
           var suma=0;
          var  before=0;
          var  suma2=0;
          var  max=cantidad2[0];
          var  proporcion=[];
            cantidad2.map(data => {
                 suma += data;
            });
            cantidad2.forEach((element,index) => { 
                suma2=element+before;
                before=suma2;
                var  porcentaje=Number((suma2*100/suma).toFixed(2));
                var value=max*porcentaje/100;
                proporcion.push(value);

            });
            console.log(proporcion);
            comboChart.config.data.datasets[1].data = cantidad;
            comboChart.config.data.datasets[0].data = proporcion;
            comboChart.config.data.labels = labels;
            comboChart.update();
        }

        function getDataCharts() {
            var fin = $("#finfiltro").val();
            var inicio = $("#iniciofiltro").val();
            const data = {
                inicio,
                fin
            };
            $.ajax({
                url: '/dashboard/indicadores',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data,
                type: 'POST',
                success: function(json) {
                    console.log(json);
                    if (json.success) {
                        updatePieChart(json.chart1);
                        updateHorizontalBar(json.chart2);
                        updateCombroChart(json.chart3);
                    }
                },
                error: function(jqXHR, status, error) {
                    console.log(status);
                },

            });
        }
        $(document).ready(function() {
            const hoy = moment().format("yyyy-MM-DD");
            const tomorrow = moment().add(2, "days").format("yyyy-MM-DD");
            const iniciomes = moment().startOf("month").format("yyyy-MM-DD");
            $("#iniciofiltro").val(iniciomes);
            $("#finfiltro").val(tomorrow);
            $("#iniciofiltro").attr("max", hoy);
            $("#finfiltro").attr("min", iniciomes);
            $("#finfiltro").attr("max", tomorrow);
            $("#iniciofiltro").on("change", function() {
                $("#finfiltro").attr("min", $("#iniciofiltro").val());
            })
            getDataCharts();
            $("#btnupdatecharts").on("click", function() {
                getDataCharts();
            });
        });
    </script>
    <script src="{{ asset('js/exportchart.js') }}"></script>
@endsection
