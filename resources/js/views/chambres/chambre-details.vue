<script>
import Layout from "../../layouts/main";
import ChambreInfo from "./chambre-info"
import ChambreEtatTempHum from "./chambre-etat-temp-hum"
import ChambreLots from "./chambre-lots"
import axios from "axios";

/**
 * Tableau de bord Component
 */
export default {
    components: {Layout, ChambreInfo,ChambreEtatTempHum,ChambreLots},//
    data() {
        return {
            isActiveWeek: true,
            isActiveMonth: false,
            isActiveYear: false,
            title: "Détails de la chambre",
            showModal: false,
            statData: null,
            records:null,
        };
    },
    props: {
        id: {
            type: String,
            default: ""
        }
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
            <ChambreEtatTempHum :id=this.id />
        </div>
        <!-- end row -->
        <div class="row">
            <ChambreLots :id=this.id :data=this.records />
        </div>
    </Layout>
</template>
