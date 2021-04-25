<script>
import Layout from "../../layouts/main";
import Stat from "../../components/widgets/stat";

import axios from "axios";

/**
 * Tableau de bord Component
 */
export default {
    components: {Layout, Stat},//
    data() {
        return {
            title: "Tableau de bord",
            showModal: false,

        }
    },
    props: {
        id: {
            type: String,
            default: ""
        }
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
                                           <!-- <Rendement id="1" type="regions"/>-->
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
                                        <h4 class="mb-0">1
                                           <!-- <NbMachines id="1" type="regions"/>-->
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

                    </div>
                    <h4 class="card-title mb-4">Rendement</h4>

                </div>
            </div>
        </div>
        <!-- end row -->
    </Layout>
</template>
