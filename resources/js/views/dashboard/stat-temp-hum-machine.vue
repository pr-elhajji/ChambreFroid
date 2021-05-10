<script>

import axios from "axios";

import {
  statHumTem,  
} from "./charts-options";

/**
 * Tableau de bord Component
 */
export default {

    data() {
        return {
            chambreData:[],
            chartTempHum : {
                series: [],
                chartOptions: {
                    chart: {
                    type: 'bar',
                    height: 200
                    },
                    plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        endingShape: 'rounded'
                    },
                    },
                    dataLabels: {
                    enabled: false
                    },
                    stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                    },
                    xaxis: {
                    categories: [],
                    },
                    yaxis: {
                    title: {
                        text: ''
                    }
                    },
                    fill: {
                    opacity: 1
                    },
                    tooltip: {
                    y: {
                        formatter: function (val) {
                            return (val).toFixed(2);
                        }
                    }
                    }
                },
            },            
            isActiveWeek: true,
            isActiveMonth: false,
            isActiveYear: false,

            statData: null,
           
        };
    },
    props: ['id','periode'],
    methods: {
        
        getByStat: function () {

            let url="/api/machines/temphumstat/"+this.id+"/"+this.periode;
            axios.get(url)  // 1 par semaine
                .then(response => {
                    var statData = response.data
                     this.chartTempHum.series=[
                         {
                             name: "Temp max",
                             data: statData.map(ele => ele.max_temp)
                         },
                         {
                             name: "Tem min",
                             data: statData.map(ele => ele.min_temp)
                         },
                          {
                             name: "Hum max",
                             data: statData.map(ele => ele.max_hum)
                         },
                         {
                             name: "Hum min",
                             data: statData.map(ele => ele.min_hum)
                         }
                     ];

                    this.chartTempHum.chartOptions = {...this.chartTempHum.chartOptions, ...{
                            xaxis: {
                                categories:statData.map(ele => ele.created_at)
                            }
                        }}
                })
                .catch(error => console.log(error));
        },
    
    },
    mounted() {
        this.getByStat();
    },
};
</script>

<template>
 <div class="card">
    <div class="card-body">
        <apexchart 
            type="bar" 
            height="200" 
            :options="this.chartTempHum.chartOptions" 
            :series="this.chartTempHum.series">
        </apexchart>
    </div>
</div>
  <!-- end row -->
</template>
