
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
            records: null,
            statData: null,
            series: [
                {
                    name: "",
                    data: []
                }
            ], //TODO automatisation
            chartOptions: {
                chart: {
                    height: 250,
                    type: 'radialBar',
                    offsetY: -10
                },
                plotOptions: {
                    radialBar: {
                        startAngle: -135,
                        endAngle: 135,
                        dataLabels: {
                            name: {
                                fontSize: '14px',
                                color: undefined,
                                offsetY: 120
                            },
                            value: {
                                offsetY: 76,
                                fontSize: '16px',
                                color: undefined,
                                formatter: function (val) {
                                    return val + "%";
                                }
                            }
                        }
                    }
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'dark',
                        shadeIntensity: 0.15,
                        inverseColors: false,
                        opacityFrom: 1,
                        opacityTo: 1,
                        stops: [0, 50, 65, 91]
                    },
                },
                stroke: {
                    dashArray: 4
                },
                labels: ['Capacité'],
            },
        }
    },
    watch: {
        series: function () {
            this.interval = setInterval(this.getData, 60000);
        }
    },
    created(){
        this.getData();
    },
    mounted() {
        // TODO requete le taux d'auccupation ou calculer le taux = 100* Taux réel/capacité max
        // TODO Récupérer les températeure et l'humidité de la dernière journées
        this.interval = setInterval(this.getData, 60000);

        /*setInterval(function(){

        }, 3000);*/


        axios.get("/api/chambres/capacite/"+this.id)  // 1 par semaine
            .then(response => {
                let statData=null
                statData= response.data
                this.series=[
                    statData.map(ele => ele.taux)
                ];
            })
            .catch(error => console.log(error));

    },
    methods:{
        getData(){

            axios.get('/api/chambres/' + this.id)// axios.get('api/regionbyrendement')
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
            <div class="d-flex align-items-start">
                <h4 class="card-title mb-4">Info</h4>
                <b-button
                    class="btn btn-success ms-auto"
                >Maintenance</b-button
                >
            </div>
            <div class="row">
                <div class="col-xl-5">
                    <div class="text-center p-4 border-end">
                        <img class="rounded-circle avatar-lg" :src="'/images/chambres/'+records.image" alt />

                        <h5 class="font-size-15">
                            <a href="javascript: void(0);" class="text-dark">{{records.numero}}</a>
                        </h5>
                    </div>

                </div>
                <div class="col-xl-7">
                    <apexchart type="radialBar" height="250" :options="chartOptions" :series="series"></apexchart>
                </div>
            </div>
        </div>
        <div class="card-footer bg-transparent border-top">
            <div class="contact-links d-flex font-size-20">
                <div v-if="this.records.etat_evaporateur ===1"  class="flex-fill">
                    <img class="rounded-circle avatar-sm" src="/images/gifs-machines/ventilateur-marche.gif" />
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
                    <img class="rounded-circle avatar-sm" src="/images/gifs-machines/moteur-marche.png" />
                </div>
                <div v-else class="flex-fill">
                    <img class="rounded-circle avatar-sm" src="/images/gifs-machines/moteur-arret.png" />
                </div>
            </div>
        </div>
    </div>



</template>

<style scoped>

</style>
