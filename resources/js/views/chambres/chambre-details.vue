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
        /*axios.get('/api/chambres/'+this.id)// axios.get('api/regionbyrendement')
            .then(response => {
                this.records = response.data
            }) .catch(error => console.log(error))*/
    },
};
</script>

<template>
    <Layout>
        <!-- row -->
        <div class="row">   
            <div class="col-lg-4"> 
                <ChambreInfo :id=this.id />
            </div>
            <div class="col-lg-8">
                                <ChambreEtatTempHum :id=this.id />

            </div>
        </div>            
        
        <!-- end row -->
        <!-- row -->
        <div class="row">
            <div class="col-sm-12">
                <ChambreLots :id=this.id />

            </div>
        </div>
        <!-- end row -->
        
    </Layout>
</template>
