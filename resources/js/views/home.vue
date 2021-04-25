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
                image: '', // image de des chambres
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
    <chambres id=""/>
</template>
