<script>
import Layout from "../layouts/main";
import PageHeader from "../components/page-header";


import axios from "axios";



/**
 * Projects-grid component
 */
export default {
    components: { Layout, PageHeader },
    data() {
        return {
            records:[{
                id: '',
                nom: '',
                image: '', // image de la régions
            }] ,
            title: "Acceuil",
            items: [
                {
                    text: "",
                    href: "/"
                },
                {
                    text: "",
                    active: true
                }
            ]
        };
    },
};
</script>

<template>
    <Layout>
        <PageHeader :title="title" :items="items" />
        <regions id=""/>
    </Layout>
</template>
