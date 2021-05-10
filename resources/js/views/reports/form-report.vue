<script>
import axios from "axios";
import Layout from "../../layouts/main";
import PageHeader from "../../components/page-header";
export default {   
    components: { Layout, PageHeader },
data() {
        return {
            records:null,
            title: "Rapports",
            items: [{
                text: "Acceuil",
                href: "/configuration"
            },
                {
                    text: "Rapport",
                    active: true,
                },

            ],
            
        };
    },
}
</script>

<template>
    <Layout>
        <PageHeader :title="title" :items="items" />
        <div class="row">
           <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Recherhce et filtrage</h4>

                    
                    

                    <div class="mt-4 pt-3">
                        <h5 class="font-size-14 mb-3">Type de client</h5>
                        <b-form-checkbox id="productdiscountCheck1" class="mt-2 form-check" value="accepted" unchecked-value="not_accepted" >Client Externe</b-form-checkbox>

                        <b-form-checkbox id="productdiscountCheck2" class="mt-2 form-check" value="accepted" unchecked-value="not_accepted" >Entrepôt</b-form-checkbox>
                     </div>

                    <div class="mt-4 pt-3">
                        <h5 class="font-size-14 mb-3">Date début</h5>
                        <div>
                            <b-form-checkbox id="checkbox-1"  class="form-check" name="checkbox-1" value="accepted" unchecked-value="not_accepted">
                                4
                                <i class="bx bx-star text-warning"></i> & Above
                            </b-form-checkbox>

                        </div>
                    </div>
                    <div class="mt-4 pt-3">
                        <h5 class="font-size-14 mb-3">Date fin</h5>
                        <div>
                            <b-form-checkbox id="checkbox-1"  class="form-check" name="checkbox-1" value="accepted" unchecked-value="not_accepted">
                                4
                                <i class="bx bx-star text-warning"></i> & Above
                            </b-form-checkbox>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Layout>

</template>

<style scoped>

</style>