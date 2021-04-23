<script>
import Layout from "../../layouts/main";
import Stat from "../../components/widgets/stat";

import Rendement from "../../components/widgets/rendement";
import NbMachines from "../../components/widgets/nb-machines";

import axios from "axios";

/**
 * Tableau de bord Component
 */
export default {
    components: {NbMachines, Layout, Stat,Rendement},//
    data() {
        return {
            title: "Tableau de bord",
            showModal: false,

            isActiveWeek: true,
            isActiveMonth: false,
            isActiveYear: false,

            statData: null,
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
        getRendementByWeek: function () {

            this.isActiveWeek= true;
            this.isActiveMonth= false;
            this.isActiveYear= false;
            axios.get("/api/machines/rendementstat/1/1")  // 1 par semaine
                .then(response => {
                    var statData = response.data
                    console.log(statData);
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
            axios.get("/api/machines/rendementstat/1/2")  // 1 par semaine
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
            axios.get("/api/machines/rendementstat/1/3")  // 1 par semaine
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
        },
    },
    mounted() {
        this.getRendementByWeek();
        setTimeout(() => {
            this.showModal = true;
        }, 1500);
    },
};
</script>

<template>
    <Layout>
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-size-18">Tableau de bord</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item active"></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">

            <!-- end col -->
            <div class="col-xl-12">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <div class="media">
                                    <div class="media-body">
                                        <p class="text-muted fw-medium mb-2">Rendement Total</p>
                                        <h4 class="mb-0">
                                            <Rendement id="1" type="regions"/>
                                        </h4>
                                    </div>
                                    <div class="mini-stat-icon avatar-sm align-self-center rounded-circle bg-primary">
                                          <span class="avatar-title">
                                            <i class="bx bx-archive-in font-size-24"></i>
                                          </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Clients total -->
                    <div class="col-md-6">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <div class="media">
                                    <div class="media-body">
                                        <p class="text-muted fw-medium mb-2">Nombre de machines</p>
                                        <h4 class="mb-0">1<NbMachines id="1" type="regions"/></h4>
                                    </div>
                                    <div class="mini-stat-icon avatar-sm align-self-center rounded-circle bg-primary">
                                          <span class="avatar-title">
                                            <i class="bx bx-archive-in font-size-24"></i>
                                          </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end card-body -->
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->

        <div class="row">
            <div class="card">
                <div class="card-body">
                    <div class="float-end">
                        <ul class="nav nav-pills">
                            <li class="nav-item">

                                <a class="nav-link" v-bind:class="{ active: isActiveWeek}" v-on:click="getRendementByWeek"> Jours</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" v-bind:class="{ active: isActiveMonth}" v-on:click="getRendementByMonth">Mois</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" v-bind:class="{ active: isActiveYear}" v-on:click="getRendementByYear">Année</a>
                            </li>
                        </ul>
                    </div>
                    <h4 class="card-title mb-4">Rendement</h4>

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
    </Layout>
</template>
