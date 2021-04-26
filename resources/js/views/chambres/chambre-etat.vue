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
            chambreData:null,
        };
    },
    created(){
        this.getData();
    },
    mounted() {
        // TODO requete le taux d'auccupation ou calculer le taux = 100* Taux réel/capacité max
        // TODO Récupérer les températeure et l'humidité de la dernière journées
        this.interval = setInterval(this.getData, 60000);
    },
    methods:{
        getData(){
            axios.get('/api/chambres/'+this.id)// axios.get('api/regionbyrendement')
                .then(response => {
                    this.chambreData = response.data;
                }).catch(error => console.log(error));
        }
    },

}
</script>

<template>
    <div class="card-footer text-center">
        <div class="row">
            <div class="col-4 b-r-default">
                <h5>
                    <span class="text-muted m-b-0">Temp: {{ this.chambreData.temperature }} °C</span>
                </h5>
            </div>
            <div class="col-4 b-r-default">
                <h5>
                    <span class="text-muted m-b-0">Hum: {{ this.chambreData.humedite }}%</span>
                </h5>
            </div>
            <div class="col-4 b-r-default">
                <div v-if="this.chambreData.etat_evaporateur ===1" class="flex-fill">
                    <img class="rounded-circle avatar-sm" src="/images/gifs-machines/ventilateur-marche.gif" />
                </div>
                <div v-else class="flex-fill">
                    <img class="rounded-circle avatar-sm" src="/images/gifs-machines/ventilateur-arret.png" />
                </div>

            </div>
        </div>

    </div>
</template>
<style scoped>

</style>
