
<script>
import axios from "axios";
import Layout from "../../layouts/main";
import PageHeader from "../../components/page-header";
import ChambreEtat from "./chambre-etat";

export default {
    components: {
        Layout,
        PageHeader,
        ChambreEtat
    },
    props: {
        id: {
            type: String,
            default: ""
        }
    },
    data() {
        return {
            records:null,
            title: "Chambres",
            items: [{
                text: "Acceuil",
                href: "/"
            },
                {
                    text: "Chambres",
                    active: true,
                },
            ]
        };
    },
    created(){
        this.getData();
    },
    /*mounted() {
        // TODO requete le taux d'auccupation ou calculer le taux = 100* Taux réel/capacité max
        // TODO Récupérer les températeure et l'humidité de la dernière journées
        this.interval = setInterval(this.getData, 60000);
    },*/
    methods:{
        getData(){
            axios.get('/api/chambres/')// axios.get('api/regionbyrendement')
                .then(response => {
                    this.records = response.data;
                }).catch(error => console.log(error));
        }
    },

}


</script>

<template>
    <Layout>
        <PageHeader :title="title" :items="items" />
        <div class="row">
            <div  v-for="(item, index) in records" :key="index" class="col-xl-4 col-md-6"> <!--  -->
                <a v-bind:href="'/chambre/details/'+item.id"  class="">
                    <div class="card text-center">
                        <div class="card-block bg-c-pink text-white">
                        <h5> {{item.numero}}</h5>
                    </div>
                    <ChambreEtat :id=String(item.id) />
                </div>
                </a>
            </div>
        </div>
    </Layout>
</template>
<style scoped>

</style>
