
<script>
import axios from "axios";
import Layout from "../../layouts/main";
import PageHeader from "../../components/page-header";

export default {
    components: {
        Layout,
        PageHeader,
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
    mounted(){
        axios.get('/api/chambres')// axios.get('api/regionbyrendement')
            .then(response => {
                this.records = response.data
            }) .catch(error => console.log(error))
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
                    <div class="card-footer text-center">
                         <div class="row">
                            <div class="col-6 b-r-default">
                                <span class="text-muted m-b-0">Temp: -5°C</span>
                            </div>
                             <div class="col-6 b-r-default">
                                 <span class="text-muted m-b-0">Hum: 50%</span>
                             </div>
                        </div>

                    </div>
                </div>
                </a>
            </div>
        </div>
    </Layout>
</template>
<style scoped>

</style>
