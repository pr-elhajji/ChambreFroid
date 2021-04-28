
<script>
import axios from "axios";

export default {
    data() {
        return {
            interval:1400,
            statData: null,
            series: [
                {
                    name: "",
                    data: []
                },
                {
                    name: "",
                    data: []
                }
            ],
            chartOptions: {
                chart: {
                    type: 'area',
                    stacked: false,
                    height: 350,
                    zoom: {
                        type: 'x',
                        enabled: true,
                        autoScaleYaxis: true
                    },
                    toolbar: {
                        autoSelected: 'zoom'
                    }
                },
                dataLabels: {
                    enabled: false
                },
                markers: {
                    size: 0,
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        inverseColors: false,
                        opacityFrom: 0.5,
                        opacityTo: 0,
                        stops: [0, 90, 100]
                    },
                },
                yaxis: {
                    labels: {
                        formatter: function (val) {
                            return (val / 1000000).toFixed(0);
                        },
                    },
                },
                xaxis: {
                    type: 'datetime',
                    categories: []
                },
                tooltip: {
                    shared: false,
                    y: {
                        formatter: function (val) {
                            return (val / 1000000).toFixed(0)
                        }
                    }
                }
            },
        };
    },
    props: {
        id: {
            type: String,
            default: ""
        }
    },

    methods: {
        getData: function () {
            let url="/api/chambres/temphum/"+this.id+"/"+this.interval
             axios.get(url)  // 1 par semaine
                 .then(response => {
                     let statData=null
                     statData= response.data
                     this.series=[
                         {
                             name: "Temp",
                             data: statData.map(ele => ele.temperature)
                         },
                         {
                             name: "Hum",
                             data: statData.map(ele => ele.humedite)
                         }
                     ];
                     this.chartOptions = {...this.chartOptions, ...{
                             xaxis: {
                                 categories:statData.map(ele => ele.created_at)
                             }
                         }}
                 })
                 .catch(error => console.log(error));
         },


    },
    mounted() {
        this.getData();
    }
}
</script>
<template>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title mb-4">Température & Humidité</h4>
            <apexchart type="area" height="350" :options="chartOptions" :series="series"></apexchart>
        </div>
    </div>
</template>

<style scoped>

</style>
