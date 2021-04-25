
<script>
import axios from "axios";

export default {
    props: {
        id: {
            type: String,
            default: ""
        },
        data: {
            type: Object,
            default: {}
        }
    },
    data() {
        return {
            records: {},
            series: [50],
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
    mounted() {
        // TODO requete le taux d'auccupation ou calculer le taux = 100* Taux réel/capacité max
        // TODO Récupérer les températeure et l'humidité de la dernière journées
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
                            <img class="rounded-circle avatar-lg" :src="'/images/chambres/'+data.image" alt />

                        <h5 class="font-size-15">
                            <a href="javascript: void(0);" class="text-dark">{{data.numero}}</a>
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
                <div class="flex-fill">
                    <img class="rounded-circle avatar-sm" src="/images/gifs-machines/ventilateur-marche.gif" />
                </div>
                <div class="flex-fill">
                    <img class="rounded-circle avatar-sm" src="/images/gifs-machines/porte-fermee.png" />
                </div>
                <div class="flex-fill">
                    <img class="rounded-circle avatar-sm" src="/images/gifs-machines/moteur-marche.png" />
                </div>
            </div>
        </div>
    </div>


</template>

<style scoped>

</style>
