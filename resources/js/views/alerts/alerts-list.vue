<script>
export default {
    props: {
        id: {
            type: String,
            default: ""
        }
    },
    data (){
        return {
            records:null,
            showModal: false,
            alerts: null
        }
    },
}
</script>
<template>
    <Layout>
        <PageHeader :title="title" :items="items" />
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start">
                            <h4 class="card-title mb-4">Alerts</h4>
                            <b-button
                                class="btn btn-success ms-auto"
                                @click="showModal = true"
                            >Ajouter un lot</b-button
                            >
                        </div>

                        <div class="table-responsive">
                            <table class="table table-centered table-nowrap table-hover align-middle">
                                <thead class="table-light">
                                <tr>
                                    <th scope="col" style="width: 70px;">Alerts</th>
                                    <th scope="col">Chambre</th>
                                    <th scope="col">Message</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="list in records" :key="list.id">
                                    <td>{{list.numero}} </td>
                                    <td>  {list.chambre}} </td>
                                    <td>  {list.message}} </td>
                                    <td>{{list.type}}</td>
                                    <td>
                                        <ul class="list-inline font-size-20 contact-links mb-0">
                                            <li class="list-inline-item px-2">
                                                <a v-b-tooltip.hover title="Modifier">
                                                    <i class="bx bx-edit"></i>
                                                </a>
                                            </li>
                                            <li class="list-inline-item px-2">
                                                <a v-b-tooltip.hover title="Supprimer">
                                                    <i class="bx bx-layer-minus"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>

<style scoped>

</style>
