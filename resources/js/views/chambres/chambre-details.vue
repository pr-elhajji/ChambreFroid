<script>
import Layout from "../../layouts/main";
import ChambreInfo from "./chambre-info"
import ChambreLots from "./chambre-lots"
import axios from "axios";

/**
 * Tableau de bord Component
 */
export default {
    components: {Layout, ChambreInfo,ChambreLots},//
    data() {
        return {
            isActiveWeek: true,
            isActiveMonth: false,
            isActiveYear: false,
            title: "Détails de la chambre",
            showModal: false,
            statData: null,
            records:null,
            series: [
                {
                    name: "",
                    data: []
                }
            ],
            chartOptions: {
                chart: {
                    stacked: true,
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: true
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: "15%",
                        // endingShape: "rounded"
                    }
                },
                dataLabels: {
                    enabled: false
                },
                xaxis: {
                    categories: []
                },
                colors: ["#556ee6", "#f1b44c", "#34c38f"],
                legend: {
                    position: "bottom"
                },
                fill: {
                    opacity: 1
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
            }
        };
    },
    props: {
        id: {
            type: String,
            default: ""
        }
    },
    methods: {
       /* getRendementByWeek: function () {
            this.isActiveWeek= true;
            this.isActiveMonth= false;
            this.isActiveYear= false;
            axios.get("/api/Chambres/"+this.id)  // 1 par semaine
                .then(response => {
                    let statData=null
                    statData= response.data
                    this.series=[
                        {
                            name: "Rendements par semaine",
                            data: statData.map(ele => ele.rendement)
                        }
                    ];
                    this.chartOptions = {...this.chartOptions, ...{
                            xaxis: {
                                categories:statData.map(ele => ele.jour)
                            }
                        }}
                })
                .catch(error => console.log(error));
        },
        getRendementByMonth: function () {
            this.isActiveWeek= false;
            this.isActiveMonth= true;
            this.isActiveYear= false;
            axios.get("/api/Chambres/rendementstat/"+this.id+"/2")  // 1 par semaine
                .then(response => {
                    var statData = response.data
                    this.series=[
                        {
                            name: "Rendements par mois",
                            data: statData.map(ele => ele.rendement)
                        }
                    ];
                    this.chartOptions = {...this.chartOptions, ...{
                            xaxis: {
                                categories:statData.map(ele => ele.jour)
                            }
                        }}
                })
                .catch(error => console.log(error));
        },
        getRendementByYear: function () {
            this.isActiveWeek= false;
            this.isActiveMonth= false;
            this.isActiveYear= true;
            axios.get("/api/Chambres/rendementstat/"+this.id+"/3")  // 1 par semaine
                .then(response => {
                    var statData = response.data
                    this.series=[
                        {
                            name: "Rendements par mois",
                            data: statData.map(ele => ele.rendement)
                        }
                    ];
                    this.chartOptions = {...this.chartOptions, ...{
                            xaxis: {
                                categories:statData.map(ele => ele.mois)
                            }
                        }}
                })
                .catch(error => console.log(error));
        },*/

    },
    mounted() {
        axios.get('/api/chambres/'+this.id)// axios.get('api/regionbyrendement')
            .then(response => {
                this.records = response.data
            }) .catch(error => console.log(error))
    },
};
</script>

<template>
    <Layout>
        <!-- start page title -->
        <div class="row">
            <ChambreInfo :id=this.id :data=this.records />
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="card">
                <div class="card-body">
                   <h4 class="card-title mb-4">Température & Humidité</h4>
                    <apexchart
                        class="apex-charts"
                        type="bar"
                        dir="ltr"
                        height="362"
                        :series="series"
                        :options="chartOptions"
                    ></apexchart>
                    <!-- <RendementStat :name="name" :data="data" :categories="categories" />-->
                </div>
            </div>
        </div>
        <!-- end row -->
        <div class="row">
          <ChambreLots :id=this.id :data=this.records />
        </div>
    </Layout>
</template>
