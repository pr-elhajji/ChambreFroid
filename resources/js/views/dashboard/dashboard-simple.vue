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
                <p>Sera affiché Bientôt</p>
            </div>
        </div>
    </Layout>
</template>
