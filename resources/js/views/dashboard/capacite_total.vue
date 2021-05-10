
<script>
import axios from "axios";

import {
  allCapacite,
} from "./charts-options";

export default {
    
    data() {
        return {
            chambresCapacite: allCapacite,
        } 
    },
    watch: {        
    },
    created(){
        //this.getData();
    },
    mounted() {
        // TODO requete le taux d'auccupation ou calculer le taux = 100* Taux réel/capacité max
        // TODO Récupérer les températeure et l'humidité de la dernière journées
        
        //this.interval = setInterval(this.getData, 60000);   

        axios.get("/api/capacites/all")
        .then((response)=>{                
            var statData = response.data
            this.chambresCapacite.series=[
                {
                    data: statData.map(ele => ele.taux)
                }
            ];
            this.chambresCapacite.chartOptions = {...this.chambresCapacite.chartOptions, ...{
                    xaxis: {
                        categories:statData.map(ele => ele.numero)
                    }
                }}
        })
        .catch(error => console.log(error));

    },
    methods:{
        getData(){
        }

    }
}

</script>

<template>
    <div class="card">
        <div class="card-body">
           
        <apexchart
              class="apex-charts"
              height="350"
              type="bar"
              dir="ltr"
              :series="chambresCapacite.series"
              :options="chambresCapacite.chartOptions"
            ></apexchart>  
    </div>
</div>

</template>

<style scoped>

</style>
