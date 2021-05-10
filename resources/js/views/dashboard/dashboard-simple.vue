<script>

import Layout from "../../layouts/main";
import PageHeader from "../../components/page-header";

import axios from "axios";
import capaciteTotal from './capacite_total';
import StatTemhumAll from './stat-temp-hum-all';
import alerts from '../alerts/alerts-list';


/**
 * Tableau de bord Component
 */
export default {
    components: {Layout,PageHeader,capaciteTotal,StatTemhumAll,alerts},//
    data() {
        return {
            records:null,
            title: "Tableau de bord",
            items: [{
                text: "Acceuil",
                href: "/"
            },
                {
                    text: "Tableau de bord",
                    active: true,
                },
            ],
        };
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
         <PageHeader :title="title" :items="items" />
       
        <div class="row">
            <!-- end col -->           
            <div class="col-md-12"> 
                <StatTemhumAll/>                                   
            </div> 
            <div class="col-md-12"> 
                <h5 class="text-muted fw-medium mb-2">Capacité totale</h5>
                <capaciteTotal/>                                     
            </div>                                
         <!-- end row -->
            <div class="col-md-12"> 
                <h5 class="text-muted fw-medium mb-2">Alerts</h5>
                <alerts :id="0" :intervale="1440"/>                                     
            </div> 
        <!-- end row -->
      
      </div>
        
    </Layout>
</template>
