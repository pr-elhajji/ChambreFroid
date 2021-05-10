<script>
import Layout from "../layouts/main";
import PageHeader from "../components/page-header";
export default {
    components: {
        Layout,
        PageHeader
    },
    data() {
        return {
            records:null,
            title: "Configuration",
            items: [{
                text: "Acceuil",
                href: "/"
            },
                {
                    text: "Configuration",
                    active: true,
                },
            ]
        };
    },
    
}
</script>
<template>
<Layout>
    <PageHeader :title="title" :items="items" />
    <div class="row">        
            <div class="col-md-4">
                <a href="#">
                    <b-card bg-variant="primary" class="text-white-50">
                        <h5 class="mt-0 mb-4 text-white">
                            <i class="mdi mdi-bullseye-arrow me-3"></i> Chambres
                        </h5>    
                    </b-card>
                </a>
            </div>

        <div class="col-md-4">
            <a href="#">
                <b-card bg-variant="success" class="text-white-50">
                    <h5 class="mt-0 mb-4 text-white">
                        <i class="mdi mdi-check-all me-3"></i> Variétes
                    </h5>
                </b-card>
            </a>
        </div>
        <div class="col-md-4">
            <b-card bg-variant="info" class="text-white-50">
                <h5 class="mt-0 mb-4 text-white">
                    <i class="mdi mdi-alert-circle-outline me-3"></i> Alerts
                </h5>
            </b-card>
        </div>
    </div>
    <!-- end row -->   
</Layout>

</template>
