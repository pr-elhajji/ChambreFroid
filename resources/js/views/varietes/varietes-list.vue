<template>
    <Layout>
        <PageHeader :title="title" :items="items" />
        <div class="row">
            <div class="col-xl-12">
                 <div class="card">
                    <div class="card-body">
                         <div class="d-flex align-items-start">
                            <h4 class="card-title mb-4">Liste des variétés</h4>
                            <b-button class="btn btn-success ms-auto" @click="showModal = true">
                                 Ajouter une variété
                            </b-button>
                        </div>      
                        <div class="table-responsive">
                        <table class="table table-centered mb-0 table-nowrap align-middle" >
                            <thead class="table-light">
                            <tr>
                                <th>variété</th>
                                <th>Référence</th>
                                <th>Libellé</th>
                                <th>Température de référence</th>
                                <th>humidité de référence</th>
                                <th>Remarques</th>
                                <th colspan="2">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    <img
                                        src="/images/chambres/image-1.jpg"
                                        alt="chambre 1 -img"
                                        title="chambre 1 -img"
                                        class="avatar-md"
                                    />
                                </td>
                                <td>
                                    <h5 class="font-size-14 text-truncate">
                                        <a href="/chambre/details/1" class="text-dark">
                                            V001
                                        </a>
                                    </h5>                                
                                </td>
                                <td>Variété 1</td>
                                <td>-5°</td>  
                                <td>Humidité de référence</td> 
                                <td>A vérifier chaque 15 jours</td>                             
                                <td>
                                    <a href="javascript:void(0);" class="action-icon text-danger">
                                        <i class="mdi mdi-trash-can font-size-18"></i>
                                    </a>
                                    <a href="javascript:void(0);" class="action-icon text-danger">
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
                <b-modal title="Ajouter une variété" v-model="showModal" hide-footer>
                    <form>
                        <div class="row">
                            <div class="col-12">
                                <div class="invalid-feedback">
                                    Cette valeur est obligatoire.
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="name">Référence:</label>
                                <input id="numero" name="numero"  type="text"  class="form-control"
                                        
                                        placeholder="Saisir la référence"/>
                                <div  class="invalid-feedback" >
                                        Valeur obligatoire.
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="name">Libellé:</label>
                                <input id="name"   type="text" class="form-control"
                                        placeholder="Saisir la Libellé de la variété"
                                        />
                                <div  class="invalid-feedback">
                                    Valeur obligatoire.
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="name">Température de référence:</label>
                                <input id="name"   type="text" class="form-control"
                                        placeholder="Saisir une température"
                                        />
                                <div  class="invalid-feedback">
                                    Valeur obligatoire.
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="name">Humidité de référence:</label>
                                <input id="name"   type="text" class="form-control"
                                        placeholder="Saisir une valeur d'humidité"
                                        />
                                <div  class="invalid-feedback">
                                    Valeur obligatoire.
                                </div>
                            </div>
                            
                            Température de référence
                            <div class="mb-3">
                                    <label for="price">Image</label>
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
                                <b-button variant="light">Fermer</b-button>
                                <b-button type="submit" variant="success" class="ms-1">Ajouter la variété</b-button>
                            </div>
                        </div>
                    </form>
                </b-modal>
                <!-- End model -->
    </Layout>

</template>

<script>
import axios from "axios";
import Layout from "../../layouts/main";
import PageHeader from "../../components/page-header";

import vue2Dropzone from "vue2-dropzone";

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

            showModal: false,
            submitted: false,
            errors: null,

            dropzoneOptions: {
                url: "https://httpbin.org/post",
                thumbnailHeight: 100,
            },
        };
    },
    created(){
        this.getData();
    },
    
    methods:{
        getData(){
            axios.get('/api/chambres/')// axios.get('api/regionbyrendement')
                .then(response => {
                    this.records = response.data;
                }).catch(error => console.log(error));
        }
    },
}
</script>

<style scoped>

</style>
