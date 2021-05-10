
<script>
import axios from "axios";
import {chartTemp} from "./charts-options"

export default {
    data() {
        return {
            interval:1400,
            statData: null,
            chartTempHum:chartTemp,
            
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
            // pour charque chambre
            let url="/api/chambres/temphum/"+this.id+"/"+this.interval
             axios.get(url)  // 1 par semaine
                 .then(response => {
                     let statData=null
                     statData= response.data
                     this.chartTempHum.series=[
                         {
                             name: "Temp",
                             data: statData.map(ele => ele.temperature)
                         },
                         {
                             name: "Hum",
                             data: statData.map(ele => ele.humedite)
                         }
                     ];
                     //this.chartTempHum.labels=statData.map(ele => ele.created_at)
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
        this.getData();
    }
}
</script>
<template>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title mb-4">Historique : Température - Humidité</h4>
            <apexchart type="area" height="350" 
                :options="chartTempHum.chartOptions" 
                :series="chartTempHum.series">
            </apexchart>
        </div>
    </div>
</template>

<style scoped>

</style>
