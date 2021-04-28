<script>
import axios from "axios";

export default {
    props: {
        id: {
            type: String,
            default: ""
        },
        data: {
            type: Object,
            default: {}
        }
    },
    data (){
        return {
            records: {},
            showModal: false,
            lots: {
                numero: "",
                vareite:"",
                quantite:0

            },
            selected: "",
            selected2: "",
            submitted: false,
        }
    },
    methods:{
        getLots : function() {
            let url='/api/chambres/lots/' + this.id;
            axios.get(url)
                .then(response => {
                    this.records = response.data;
                }).catch(error => console.log(error));
        }
    },
    mounted() {
        this.getLots();
    },
}
</script>
<template>
<div class="row">
    <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start">
                    <h4 class="card-title mb-4">Lots</h4>
                    <b-button
                        class="btn btn-success ms-auto"
                        @click="showModal = true"
                    >Ajouter un lot</b-button>
                </div>
                <!-- Model -->
                <b-modal title="Ajouter un lot" v-model="showModal" hide-footer>
                    <form @submit.prevent="handleSubmit">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="name">Numéro:</label>
                                    <input id="numero"  v-model="lots.numero"
                                        type="text"
                                        class="form-control"
                                        placeholder="Saisir le numéro de lot"
                                        :class="{
                          'is-invalid': submitted && $v.lots.numero.$error,
                        }"
                                    />
                                    <div
                                        v-if="submitted && !$v.lots.numero.required"
                                        class="invalid-feedback"
                                    >
                                        Valeur obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    Variété:
                                    <b-form-select
                                        v-model="selected"
                                        class="mb-3 form-select"
                                    >
                                        <b-form-select-option value="poivron"
                                        >Poivron</b-form-select-option
                                        >
                                        <b-form-select-option value="tomate"
                                        >Tomate</b-form-select-option
                                        >
                                        <b-form-select-option value="Variété 3"
                                        >Vriété 3</b-form-select-option
                                        >
                                    </b-form-select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="name">Quantité:</label>
                                    <input id="name"  v-model="lots.quantite"
                                           type="text"
                                           class="form-control"
                                           placeholder="Saisir le numéro de lot"
                                           :class="{
                          'is-invalid': submitted && $v.lots.quantite.$error,
                        }"
                                    />
                                    <div
                                        v-if="submitted && !$v.lots.quantite.required"
                                        class="invalid-feedback"
                                    >
                                        Valeur obligatoire.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-end mt-3">
                            <b-button variant="light">Fermer</b-button>
                            <b-button type="submit" variant="success" class="ms-1"
                            >Ajouter le lot</b-button
                            >
                        </div>
                    </form>
                </b-modal>
                <!-- End model -->
                <div class="table-responsive">
                    <table class="table table-centered table-nowrap table-hover align-middle">
                        <thead class="table-light">
                        <tr>
                            <th scope="col" style="width: 70px;">Référence</th>
                            <th scope="col">Varieté</th>
                            <th scope="col">Quantité</th>
                            <th scope="col">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="ele in records" :key="ele.id">
                            <td>
                                <span>{{ele.numero}}</span>
                                <div v-if="ele.image">
                                    <img class="rounded-circle avatar-xs" :src="`images/lots/${ele.image}`" alt />
                                </div>
                            </td>
                            <td>
                                <h5 class="font-size-14 mb-1">
                                    <a href="#" class="text-dark">{{ele.libelle}}</a>
                                </h5>
                            </td>
                            <td> <p class="text-muted mb-0">{{ele.capacite}}</p></td>
                            <td>
                                <ul class="list-inline font-size-20 contact-links mb-0">
                                    <li class="list-inline-item px-2">
                                        <a v-b-tooltip.hover title="Modifier">
                                            <i class="bx bx-edit"></i>
                                        </a>
                                    </li>
                                    <li class="list-inline-item px-2">
                                        <a v-b-tooltip.hover title="Supprimer">
                                            <i class="bx bx-basket"></i>
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

</template>

<style scoped>

</style>
