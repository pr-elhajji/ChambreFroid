
<script>
import axios from "axios";

export default {
    props: {
        id: {
            type: String,
            default: ""
        }
    },
    data() {
        return {

            records: {},
            statData: null,
            capacite:{},
        }
    },
    watch: {
    
    },
    created(){
        this.getData();
    },
    mounted() {
        // TODO requete le taux d'auccupation ou calculer le taux = 100* Taux réel/capacité max
        // TODO Récupérer les températeure et l'humidité de la dernière journées
        this.interval = setInterval(this.getData, 60000);
        axios.get("/api/chambres/capacite/"+this.id) 
            .then(response => {
                let statData=null
                this.capacite= response.data
               
            })
            .catch(error => console.log(error));
    },
    methods:{
        getData(){
            axios.get('/api/chambres/' + this.id)
                .then(response => {
                    this.records = response.data;
                }).catch(error => console.log(error));
        }

    }
}

</script>

<template>

        <div class="card">
            <div class="card-body"> 
                
                <div class="row task-dates">
                    <div class="col-6">
                        <div class="mt-6">
                            <h5>{{records.numero}}</h5>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="media">
                                <span v-if="records.image">
    
                                    <img :src="'/images/chambres/'+records.image" alt="" class="avatar-lg me-6" />
                                </span>
                                <span v-else>
                                    <img src="/images/no-image.png" alt="" class="avatar-lg me-6" />
                                </span>
                                <p></p>
                                <p></p>
                        </div>
                    </div>
                </div>
                <div class="row task-dates">
                <div class="col-4">
                    <div class="mt-4">
                    <h5 class="font-size-14">
                        <i class="mdi mdi-coolant-temperature me-1 text-primary"></i> Temp
                    </h5>
                    <p class="text-muted mb-0">{{records.temperature}}°C</p>
                    </div>
                </div>

                <div class="col-4">
                    <div class="mt-4">
                    <h5 class="font-size-14">
                        <i class="mdi mdi-air-humidifier me-1 text-primary"></i> Hum
                    </h5>
                    <p class="text-muted mb-0">{{records.humedite}}%</p>
                    </div>
                </div>
                <div class="col-4">
                    <div class="mt-4">
                    <h5 class="font-size-14">
                        <i class="mdi mdi-layers-triple me-1 text-primary"></i> capacité
                    </h5>
                    <p class="text-muted mb-0">{{capacite.taux}}%</p>
                    </div>
                </div>
                </div>                     
                <div class="card-footer bg-transparent border-top">
                    <div class="contact-links d-flex font-size-20">
                        <div v-if="this.records.etat_evaporateur ===1"  class="flex-fill">
                            <img class="rounded-circle avatar-sm " src="/images/gifs-machines/ventilateur-marche.gif" />
                        </div>
                        <div v-else class="flex-fill">
                            <img class="rounded-circle avatar-sm" src="/images/gifs-machines/ventilateur-arret.png" />
                        </div>

                        <div v-if="this.records.etat_porte ===1" class="flex-fill">
                            <img class="rounded-circle avatar-sm" src="/images/gifs-machines/porte-fermee.png" />
                        </div>
                        <div v-else class="flex-fill">
                            <img class="rounded-circle avatar-sm" src="/images/gifs-machines/porte-ouvetre.png" />
                        </div>

                        <div v-if="this.records.etat_compresseur ===1" class="flex-fill">
                            <img class="rounded-circle avatar-sm" src="/images/gifs-machines/moteur-marche.gif" />
                        </div>
                        <div v-else class="flex-fill">
                            <img class="rounded-circle avatar-sm" src="/images/gifs-machines/moteur-arret.png" />
                        </div>
                    </div>
                </div>
            </div>
        </div>  
   
</template>

<style scoped>
</style>
