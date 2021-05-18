<script>
import axios from "axios";
import Layout from "../../layouts/main";
import PageHeader from "../../components/page-header";

import { required } from "vuelidate/lib/validators";
import _ from "lodash";
import Swal from "sweetalert2";
export default {
   components: {
        Layout, PageHeader},
    data() {
        return {
            records:null,
            title: "Chambres",
            items: [{
                text: "Configuration",
                href: "/configuration"
            },
                {
                    text: "Chambres",
                    active: true,
                },
            ],
            formData: {
                numero: "",
                capacite: "",
                image:""
            },
            image: null,
            imageName: null,
            chambre: null,
            //b-modal
            showModal: false,
            submitted: false,
            msgAlert:'',
            errorAlert:false,
            editmode:false,
            errors: null,
            updated_chambre:'',
        };
    },
    validations: {
        formData: {
            numero:  { required },
            capacite: { required },
        }
    },
    mounted(){
        this.getData();
    },

    methods:{
        getData(){
            axios.get('/api/chambres/')// axios.get('api/regionbyrendement')
                .then(response => {
                    this.records = response.data;
                }).catch(error => console.log(error));
        },
        formTitle () {
            return this.editmode? 'Modifier':'Ajouter'
        },
        fileAdded(file) {
            this.image = file;
            this.imageName = file.name;
        },
        ajouter(){
            // stop here if form is invalid
            this.$v.$touch();
            if (this.$v.$invalid) {
                return;
            }
            else {
                this.errors = null
                let formData = new FormData();
                if(this.image)
                    formData.append("image", this.image, this.imageName);
                _.each(this.formData, (value, key) => {
                    formData.append(key, value);
                });
                axios.post("/api/chambres/", formData, {
                    headers: {
                        "Content-Type": "multipart/for-data",
                    },
                })
                .then((response) => {
                    this.msgAlert = response.data;
                    this.showModal= false,
                    this.submitted = true;
                    this.getData();
                })
                .catch((err) => {
                    this.errorAlert=true;
                    this.msgAlert="La référence doit être unique | images accetpées:jpeg,png,jpg,gif,svg";
                    this.submitted = false;

                });
            }

        },
        supprimer(id){
            Swal.fire({
                title: "Êtes-vous sûr?",
                text: "Vous ne pourrez pas revenir en arrière!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#34c38f",
                cancelButtonColor: "#f46a6a",
                confirmButtonText: "Oui, supprimer!"
            }).then(result => {
                if (result.value) {
                    //TODO methode de supprission
                    axios.delete('/api/chambres/'+id)// axios.get('api/regionbyrendement')
                    .then(response => {
                        this.msgAlert = response.data;
                        const index = this.records.findIndex(ele => ele.id === id) // find the post index
                        if (~index) // if the element exists in array
                        this.records.splice(index, 1) //delete the element

                    }).catch(error => console.log(error));

                Swal.fire("Supprimé!", "success");
                }
            });
            },
        modifier(){
            // stop here if form is invalid
            this.$v.$touch();
            if (this.$v.$invalid) {
                return;
            }
            else {
                this.errors = null
                let formData = new FormData();
                if(this.image)
                    formData.append("image", this.image, this.imageName);
                _.each(this.formData, (value, key) => {
                    formData.append(key, value);
                });
                axios.post("/api/chambres/updatechambres/"+this.updated_chambre, formData, {
                    headers: {
                        "Content-Type": "multipart/for-data",
                    },
                }).then((response) => {
                    this.showModal = false;
                    this.submitted = true;
                    this.getData(); 
                    //this.msgAlert = response.data;                        

                    }).catch((err) => {
                        this.errorAlert=true;
                        this.msgAlert="La référence doit être unique | images accetpées:jpeg,png,jpg,gif,svg";
                        this.submitted = false;
                    });

            }

        },
        editModal(chambre){
            this.editmode = true;
            this.showModal = true;
            this.submitted=false;
            this.updated_chambre=chambre.id;
            this.formData.numero=chambre.numero;
            this.formData.capacite=chambre.capacite;
            this.imagename=chambre.image;
            // $('#addNew').modal('show');
        },
        newModal(){
            this.editmode = false;
            this.showModal = true;
            this.submitted=false;
            //$('#my-modal').modal('show');
            this.formData.numero='';
            this.formData.numero='';
            this.formData.capacite='';
            this.imagename='';
        },
    },

}
</script>
<template>
    <Layout>
        <PageHeader :title="title" :items="items" />
        <div class="row">
            <div class="col-xl-12">
                 <div class="card">
                    <div class="card-body">

                         <b-alert :show="submitted" variant="success" dismissible>La chambre a été ajouté avec succès</b-alert>

                         <div class="d-flex align-items-start">
                            <h4 class="card-title mb-4">Liste des chambres froides</h4>
                            <b-button class="btn btn-success ms-auto" @click="newModal()">
                                 Ajouter une chambre froide
                            </b-button>
                        </div>
                        <div class="table-responsive">
                        <table class="table table-centered mb-0 table-nowrap align-middle" >
                            <thead class="table-light">
                            <tr>
                                <th>Chambre</th>
                                <th>Référence</th>
                                <th>Capacité max</th>
                                <th colspan="2">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="ele in records" :key="ele.id">
                                <td>
                                    <img
                                        :src="'/images/uploads/chambres/' + ele.image"
                                        :alt="ele.numero"
                                        :title="ele.numero"
                                        class="avatar-md"
                                    />
                                </td>

                                <td>
                                    <h5 class="font-size-14 text-truncate">
                                        <a :href="`/chambres/details/${ele.id}`" class="text-dark">
                                            {{ele.numero}}
                                        </a>
                                    </h5>
                                </td>
                                <td>{{ele.capacite}}</td>
                                <td>
                                    <a href="#" @click.prevent="editModal(ele)" class="action-icon text-danger">
                                        <i class="mdi mdi-circle-edit-outline font-size-18"></i>
                                    </a>
                                    <a href="#" @click="supprimer(ele.id)" class="action-icon text-danger">
                                        <i class="mdi mdi-trash-can font-size-18"></i>
                                    </a>
                                    
                                </td>
                            </tr>

                            </tbody>
                        </table>
                        </div>
                        <div class="row mt-4">
                            <div class="col-sm-6">
                                <a href="/configuration" class="btn btn-secondary">
                                  <i class="mdi mdi-arrow-left me-1"></i> Configuration
                                </a>

                            </div>
                        <!-- end col -->

                        </div>
                        <!-- end row-->
                    </div>
                 </div>
            </div>
        </div>

        <!-- Model -->
        <b-modal id="my-modal" size="lg" :title="formTitle()" v-model="showModal" hide-footer>
            <form @submit.prevent="editmode ? modifier() : ajouter()">
                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            <b-alert :show="errorAlert" id="my-alert" variant="warning" dismissible>{{msgAlert}}</b-alert>
                            <label for="name">Référence:</label>
                            <input id="numero" name="numero"  type="text"  class="form-control"
                                    placeholder="Saisir le numéro de la chambre"
                                    v-model="formData.numero"
                                    :class="{
                                        'is-invalid': submitted &&  $v.formData.numero.$error,
                                    }"
                                    />
                            <div v-if="submitted && !$v.formData.numero.required"
                                class="invalid-feedback" >
                                    Valeur obligatoire.
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="name">Capacité max:</label>
                            <input id="capacite"   type="number" class="form-control"
                                    placeholder="Saisir la capacité max"
                                    :class="{
                                        'is-invalid': submitted &&  $v.formData.capacite.$error,
                                    }"
                                    v-model="formData.capacite"
                                    />
                            <div v-if="submitted && !$v.formData.capacite.required"
                                class="invalid-feedback" >
                                    Valeur obligatoire.
                            </div>
                        </div>
                    </div>
                    <b-form-file
                        v-model="image"
                        :state="Boolean(image)"
                        placeholder=" photo de la chambre..."
                        drop-placeholder="Glisser l'image içi..."
                    >
                    </b-form-file>
                    <div class="text-end mt-3">
                        <b-button variant="light"  @click="$bvModal.hide('my-modal')">Fermer</b-button>
                        <b-button type="submit" variant="success" class="ms-1">Envoyer</b-button>
                    </div>
                </div>
            </form>
        </b-modal>
        <!-- End model -->
    </Layout>

</template>

<style scoped>
</style>
