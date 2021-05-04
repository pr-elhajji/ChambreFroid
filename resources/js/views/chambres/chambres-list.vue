<script>
import axios from "axios";
import Layout from "../../layouts/main";
import PageHeader from "../../components/page-header";

import vue2Dropzone from "vue2-dropzone";
import { required } from "vuelidate/lib/validators";
import _ from "lodash";
import Swal from "sweetalert2";
export default {    
   components: {
        Layout, PageHeader, vueDropzone: vue2Dropzone },
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
            
            avatar: null,
            avatarName: null,
            chambre: null,

            editedIndex:-1,
            showModal: false,
            submitted: false,
            msgAlert:'',
            errorAlert:false,
            editmode:true,               
            errors: null,

            dropzoneOptions: {
                url:'https://httpbin.org/post',
                thumbnailHeight: 100,
            },
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
            return this.editedIndex === -1 ? 'Ajouter' : 'Modifier'
        },
        fileAdded(file) {
            this.avatar = file;
            this.avatarName = file.name;
        },
        create(){
            // stop here if form is invalid
            this.$v.$touch();
            if (this.$v.$invalid) {
                return;
            } 
            else {
                this.errors = null
                let formData = new FormData();
                if(this.avatar)
                    formData.append("image", this.avatar, this.avatarName);               
                _.each(this.formData, (value, key) => {
                    formData.append(key, value);
                });
                axios.post("/api/chambres/", formData, {
                    headers: {
                        "Content-Type": "multipart/for-data",
                    },
                })
                .then((response) => {
                    this.chambre = response.data;
                    this.showModal= false,
                    this.submitted = true;   

                })
                .catch((err) => {
                    this.errorAlert=true;
                    this.msgAlert="La référence doit être unique | images accetpées:jpeg,png,jpg,gif,svg";

                    if (err.response.status === 422) {
                        this.errors = [];
                        _.each(err.response.data.errors, (error) => {
                            _.each(error, (e) => {
                                this.errors.push(e);
                            });
                        });
                    }                    
                    

                });
            this.submitted = false;
            
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
                    console.log(id);
                    axios.delete('/api/chambres/'+id)// axios.get('api/regionbyrendement')
                    .then(response => {
                        const index = this.records.findIndex(ele => ele.id === id) // find the post index 
                        if (~index) // if the element exists in array
                     this.posts.splice(index, 1) //delete the element
                   
                    }).catch(error => console.log(error));

                Swal.fire("Supprimé!", "success");
                }
            });
        }
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
                            <b-button class="btn btn-success ms-auto" @click="editedIndex=-1, showModal = true">
                                 Ajouter une chambre froide
                            </b-button>
                        </div>      
                        <div class="table-responsive">
                        <table class="table table-centered mb-0 table-nowrap align-middle" >
                            <thead class="table-light">
                            <tr>
                                <th>Chambre</th>
                                <th>Numéro</th>
                                <th>Capacité max</th>
                                <th colspan="2">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="ele in records" :key="ele.id">
                                <td>
                                    <img
                                        :src="'/images/chambres/' + ele.image"
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
                                    <a href="#" @click="supprimer(ele.id)" class="action-icon text-danger">
                                        <i class="mdi mdi-trash-can font-size-18"></i>
                                    </a>
                                    <a href="#" @click="editedIndex=ele.id, showModal = true" class="action-icon text-danger">
                                        <i class="mdi mdi-circle-edit-outline font-size-18"></i>
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
            
            <form @submit.prevent="editmode ? update() : create()">
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
                    <div class="mb-3">
                            <label for="image">Image</label>
                                    <vue-dropzone id="dropzone" ref="file" name="image" :use-custom-slot="true" :options="dropzoneOptions" @vdropzone-file-added="fileAdded">
                                <div class="dropzone-custom-content">
                                    <div class="mb-1">
                                        <i class="display-4 text-muted bx bxs-cloud-upload"></i>
                                    </div>
                                    <h4>Déposez l'image ici ou cliquez pour importer.</h4>
                                </div>
                            </vue-dropzone>
                          
                        </div>
                    <div class="text-end mt-3">
                        <b-button variant="light"  @click="$bvModal.hide('my-modal')">Fermer</b-button>
                        <b-button type="submit" variant="success" class="ms-1">Ajouter la chambre</b-button>
                    </div>
                </div>
            </form>
        </b-modal>
        <!-- End model -->
    </Layout>

</template>

<style scoped>
</style>
