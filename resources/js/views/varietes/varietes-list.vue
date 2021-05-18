<template>
    <Layout>
        <PageHeader :title="title" :items="items" />
        <!-- Liste des variétés -->
<div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Liste des variétés</h4>
                <div class="row mt-4">
                    <div class="col-sm-12 col-md-6">
                        <div class="button-items">         
                            <b-button class="btn btn-success ms-auto" @click="showModal = true">
                                    Ajouter une variété
                            </b-button>
                        
                           <!-- <b-button variant="primary" @click="exportExcel">
                                <i class="mdi mdi-microsoft-excel"></i> Exporter en Excel
                                 
                            </b-button>
                            <b-button variant="primary">
                                <i class="mdi mdi-file-pdf"></i> Impression PDF
                            </b-button> -->  
                           
                        </div>
                    </div>
                </div>

            <div class="row mt-4">
              <div class="col-sm-12 col-md-6">
                <div id="tickets-table_length" class="dataTables_length">
                  <label class="d-inline-flex align-items-center">
                    Affiché&nbsp;
                    <b-form-select
                      v-model="perPage"
                      size="sm"
                      class="form-select form-select-sm"
                      :options="pageOptions"
                    ></b-form-select>
                  </label>
                </div>
              </div>
              <!-- Search -->
              <div class="col-sm-12 col-md-6">
                <div
                  id="tickets-table_filter"
                  class="dataTables_filter text-md-end"
                >
                  <label class="d-inline-flex align-items-center">
                    <b-form-input
                      v-model="filter"
                      type="search"
                      placeholder="Rechercher..."
                      class="form-control form-control-sm ms-2"
                    ></b-form-input>
                  </label>
                </div>
              </div>
              <!-- End search -->
            </div>
            <!-- Table -->
            <div class="table-responsive">
              <b-table
                ref="table"
                class="datatables"
                :items="records"
                :fields="fields"
                responsive="sm"
                :per-page="perPage"
                :current-page="currentPage"
                :sort-by.sync="sortBy"
                sort-desc.sync="false"
                :filter="filter"
                :filter-included-fields="filterOn"
                @filtered="onFiltered"
              >
                
                <template #cell(image)="row">         
                    <div v-if="row.item.image">
                        <img class="rounded-circle avatar-xs" :src="`/images/uploads/varietes/${row.item.image}`" alt />
                    </div>
                 </template>
              
              <template #cell(action)="row">
                    <a @click.prevent="editModal(row.item)" class="action-icon text-danger">
                        <i class="mdi mdi-circle-edit-outline font-size-18"></i>
                    </a>
                    <a href="#" @click="supprimer(row.item.id)" class="action-icon text-danger">
                        <i class="mdi mdi-trash-can font-size-18"></i>
                    </a>
            </template>
              
              </b-table>
            </div>
            <div class="row">
              <div class="col">
                <div
                  class="dataTables_paginate paging_simple_numbers float-end"
                >
                  <ul class="pagination pagination-rounded mb-0">
                    <!-- pagination -->
                    <b-pagination
                      v-model="currentPage"
                      :total-rows="rows"
                    ></b-pagination>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
        
    <!-- Model -->
   <!-- Model -->
        <b-modal id="my-modal" size="lg" :title="formTitle()" v-model="showModal" hide-footer>
            <form @submit.prevent="editmode ? modifier():ajouter()">
                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            <b-alert :show="errorAlert" id="my-alert" variant="warning" dismissible>{{msgAlert}}</b-alert>
                            <label for="name">Libellé:</label>
                            <input id="libelle" name="libelle"  type="text"  class="form-control"
                                    placeholder=""
                                    v-model="formData.libelle"
                                    :class="{
                                        'is-invalid': submitted &&  $v.formData.libelle.$error,
                                    }"
                                    />
                            <div v-if="submitted && !$v.formData.libelle.required"
                                class="invalid-feedback" >
                                    Valeur obligatoire.
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="name">Température de référence:</label>
                            <input id="temp_reference"   type="number" step="0.01" class="form-control"
                                    placeholder=""
                                    :class="{
                                        'is-invalid': submitted &&  $v.formData.temp_reference.$error,
                                    }"
                                    v-model="formData.temp_reference"
                                    />
                            <div v-if="submitted && !$v.formData.temp_reference.required"
                                class="invalid-feedback" >
                                    Valeur obligatoire.
                            </div>
                        </div>
                    </div>
                     <div class="col-12">
                        <div class="mb-3">
                            <label for="name">Humidité de référence:</label>
                            <input id="hum_reference"   type="number" step="0.01" class="form-control"
                                    placeholder=""
                                    :class="{
                                        'is-invalid': submitted &&  $v.formData.hum_reference.$error,
                                    }"
                                    v-model="formData.hum_reference"
                                    />
                            <div v-if="submitted && !$v.formData.hum_reference.required"
                                class="invalid-feedback" >
                                    Valeur obligatoire.
                            </div>
                        </div>
                    </div>
                    <b-form-file
                        v-model="image"
                        :state="Boolean(image)"
                        placeholder=" photo de la variété..."
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
    <!-- End model -->
    </Layout>

</template>

<script>
import axios from "axios";
import Layout from "../../layouts/main";
import PageHeader from "../../components/page-header";

import { required } from "vuelidate/lib/validators";
import _ from "lodash";
import Swal from "sweetalert2";

export default {    
   components: {
        Layout, PageHeader },
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
            fields: [
                { key: "image", label: 'Image', sortable: false },
                { key: "id", label: 'N°', sortable: false },
                { key: "libelle", label: 'Libellé', sortable: false },
                { key: "temp_reference", label: 'Température de référence', sortable: false },
                { key: "hum_reference",label: 'Humidité de référence', sortable: true },
                { key: "Action"},
            ],
            currentPage: 1,
            perPage: 10,
            pageOptions: [10, 25, 50, 100],
            filter: null,
            sortBy: "id",
            filterOn: [],
            //modal
            formData: {
                libelle: "",
                temp_reference: "",
                hum_reference:"",
            },
            image: null,
            imageName: null,
            variete: null,
            showModal: false,
            submitted: false,
            msgAlert:'',
            errorAlert:false,
            editmode:false,
            errors: null,
            updated_variete:'',
            //fin data modal

        };
    },
    validations: {
        formData: {
            libelle:  { required },
            temp_reference:  { required },
            hum_reference:  { required },
        }
    },
    created(){
        this.getData();
    },
    computed: {
    /**
     * Total no. of records
     */
        rows() {
        return this.records.length;
        },
    },
    methods:{
        getData(){
            axios.get('/api/varietes')// axios.get('api/regionbyrendement')
                .then(response => {
                    this.records = response.data;
                }).catch(error => console.log(error));
        },
        onFiltered(){},
        formTitle () {
            return this.editmode? 'Modifier':'Ajouter';
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
                axios.post("/api/varietes", formData, {
                    headers: {
                        "Content-Type": "multipart/for-data",
                    },
                })
                .then((response) => {
                    console.log(response.data);
                    this.showModal= false,
                    this.submitted = true;
                    this.getData();
                    this.$refs.table.refresh()

                })
                .catch((err) => {
                    this.errorAlert=true;
                    this.msgAlert="Erreur de saisie";
                    this.submitted = false;

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
                    axios.delete('/api/varietes/'+id)// axios.get('api/regionbyrendement')
                    .then(response => {
                        this.msgAlert = response.data;
                        /*const index = this.records.findIndex(ele => ele.id === id) // find the post index
                        if (~index) // if the element exists in array
                        this.records.splice(index, 1) //delete the element*/
                        this.getData();
                        this.$refs.table.refresh()


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
                axios.post("/api/varietes/updatevarietes/"+this.updated_variete, formData, {
                    headers: {
                        "Content-Type": "multipart/for-data",
                    },
                }).then((response) => {
                    console.log(response.data);
                    this.showModal = false;
                    this.submitted = true;
                    this.editmode = false;
                    this.getData(); 
                    //this.msgAlert = response.data;
                    this.$refs.table.refresh()                       

                    }).catch((err) => {
                        this.errorAlert=true;
                        this.msgAlert="Erreur dans l'info saisie";
                        this.submitted = false;
                    });

            }

        },
        editModal(variete){
            console.log(variete);
            this.editmode = true;
            this.showModal = true;
            this.updated_variete=variete.id;
            this.formData.libelle=variete.libelle;
            this.formData.temp_reference=variete.temp_reference;
            this.formData.hum_reference=variete.hum_reference;
            this.imageName=variete.image;
            // $('#addNew').modal('show');
        },
        newModal(){
            this.editmode = false;
            this.showModal = true;
            //$('#my-modal').modal('show');
        },
    
    },
}
</script>

<style scoped>

</style>
