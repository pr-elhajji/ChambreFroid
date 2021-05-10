<script>

import axios from "axios";

import StatTemhum from './stat-temp-hum-machine';


/**
 * Tableau de bord Component
 */
export default {
    components: {StatTemhum},//
    data() {
        return {
            chambreData:[],
            isActiveWeek: true,
            isActiveMonth: false,
            isActiveYear: false,
            statData: null, 
            periode:1,          
        };
    },
    props: ['id'],
    methods: {
        getData(){
            axios.get('/api/chambres/')// 
                .then(response => {
                    this.chambreData = response.data;
                }).catch(error => console.log(error));
        },
        getActivation: function (periode) {
            this.isActiveWeek= false;
            this.isActiveMonth= false;
            this.isActiveYear= false;            
            if (periode===1) this.isActiveWeek= true;
            if (periode===2) this.isActiveMonth= true;
            if (periode===3) this.isActiveYear= true; 
            this.periode=periode;          
        },
    
    },
    mounted() {
        this.getData();
        //this.getActivation(1);
    },
};
</script>

<template>

    <div class="row">  
        <h4 class="card-title mb-4">Historique Température & Humidité</h4>
        <div v-for="item in chambreData" :key="item.id" class="col-lg-6"> 
            <div class="card">
                <div class="card-body">    
                    <StatTemhum :id="item.id" :periode="periode" />
                </div>
            </div>
        </div>
        
    </div>
    <!-- end row -->
</template>
