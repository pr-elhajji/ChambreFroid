<script>
import axios from "axios";
import _ from "lodash";
import Swal from "sweetalert2";
import vue2Dropzone from "vue2-dropzone";


export default {
    
    props: {
        id: {
            type: String,
            default: "",
        }
    },
    data (){
        return {
            records:[],
            lot:null,
            editedIndex:-1,
            showModal: false,
            editmode:false,
            varietes:[],
            msgAlert:'',
            errorAlert:false,
            image:null,
            imagename:null,
            formData: {
                numero: "",
                variete_id: "",
                quantite:0,
                chambre_id:this.id,
            },
            updated_lot:'',
        }
    },
    mounted() {
        this.getData();
    },
    methods:{
        fileAdded(file) {
            this.image = file;
            this.imagename = file.name;
        },
        getData : function() {
            let url='/api/chambres/lots/' + this.id;
            console.log(url);
            axios.get(url)//lots dans une chambre
                .then(response => {
                    this.records = response.data;
                }).catch(error => console.log(error));

            url='/api/varietes/';
            axios.get(url) //varietés
                .then(response => {
                    this.varietes = response.data;              
                    
                }).catch(error => console.log(error));
        },

        //ajouter un lot
        ajouter(){
            this.errors = null
            let formData = new FormData();
            formData.append("chambre_id", this.id);
            if(this.image)
                formData.append("image", this.image, this.imageName);
            _.each(this.formData, (value, key) => {
                formData.append(key, value);
            });               

            axios.post("/api/lots/", formData, {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            }).then((response) => {
                    this.showModal= false,
                    this.submitted = true;
                    this.getData();

            }).catch((error) => {
                this.errorAlert=true;                
                this.msgAlert="Erreur "+ error.response.status+ " : "+ "Vérifiez les données saisies";
                this.submitted = false;
            });
        },
    
        modifier(){
            this.errors = null
            let formData = new FormData();
            formData.append("chambre_id", this.id);
            if(this.image)
               formData.append("image", this.image, this.image.name);
            _.each(this.formData, (value, key) => {
                formData.append(key, value);
            });
            axios.post("/api/lots/updatelots/"+this.updated_lot, formData,
            {
                headers: {
                    'Content-Type': 'multipart/form-data'
                },
            }
            ).then((response) => {
                console.log(response.data);
                //this.msgAlert = response.data;
                this.showModal = false;
                this.submitted = true;
                this.getData(); 
            }).catch((err) => {
                this.errorAlert=true;
                this.msgAlert="La référence doit être unique | images accetpées:jpeg,png,jpg,gif,svg";
                this.submitted = false;
            });
        },
        supprimer(id){
           
             Swal.fire({
                title: "Êtes-vous sûr?",
                text: "Vous ne pourrez pas revenir en arrière!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#34c38f",
                cancelButtonColor: "#d33",
                confirmButtonText: "Oui, supprimer!"
            }).then(result => {
                if (result.value) {
                    //TODO methode de supprission
                    axios.delete('/api/lots/'+id)
                    .then(response => {
                        const index = this.records.findIndex(ele => ele.id === id) // find the lot index
                        if (~index) // if the element exists in array
                            this.records.splice(index, 1) //delete the element
                        Swal.fire("Supprimé!", "success");
                    }).catch(error => console.log(error));

                }
            });
        },
        editModal(lot){
            this.errorAlert=false;
                this.editmode = true;
                this.showModal = true;
                this.updated_lot=lot.id;
                this.formData.numero=lot.numero;
                this.formData.variete_id=lot.variete_id;
                this.formData.quantite=lot.quantite;               
               // $('#addNew').modal('show');                
        },
        newModal(id){
            this.errorAlert=false;
            this.editmode = false;
            this.showModal = true;
            this.chambre_id=id;
            //$('#my-modal').modal('show');
        },

        
        formTitle () {
            return this.editmode? 'Modifier':'Ajouter'
        },
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
                       @click="newModal(id)"
                    >Ajouter un lot</b-button>
                </div>

                <div class="table-responsive">
                    <table class="table table-centered table-nowrap table-hover align-middle">
                        <thead class="table-light">
                        <tr>
                            <th scope="col" style="width: 70px;">Référence</th>
                            <th scope="col">image</th>
                            <th scope="col">Varieté</th>
                            <th scope="col">Quantité</th>
                            <th scope="col">Action</th>

                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="ele in records" :key="ele.id">
                            <td> {{ele.numero}} </td>
                            <td>
                                <div v-if="ele.image">
                                    <img class="rounded-circle avatar-xs" :src="`/images/uploads/lots/${ele.image}`" alt />
                                </div>
                            </td>
                            <td>
                                <h5 class="font-size-14 mb-1">
                                    <a href="#" class="text-dark">{{ele.libelle}}</a>
                                </h5>
                            </td>
                            <td> <p class="text-muted mb-0">{{ele.quantite}}</p></td>
                            <td>
                                <a  @click="editModal(ele)" v-b-tooltip.hover title="Modifier">
                                    <i class="bx bx-edit"></i>
                                </a>
                                <a @click="supprimer(ele.id)" v-b-tooltip.hover title="Supprimer">
                                    <i class="bx bx-basket"></i>
                                </a>
                            </td>

                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

     <b-modal id="my-modal" size="lg" :title="formTitle()" v-model="showModal" hide-footer>
        <b-form @submit.prevent="editmode ? modifier() : ajouter()">
        <b-alert :show="errorAlert" variant="warning" dismissible>{{msgAlert}}</b-alert>

        <div class="row">
            <div class="col-16">
                <div class="mb-3">
                    <b-form-group
                        id="input-group-1"
                        label="Référence:"
                        label-for="numero"
                    >
                        <b-form-input  
                            id="numero"
                            v-model="formData.numero"
                            type="text"
                            placeholder="Saisir la référence du lot"
                            required                    
                            class="form-control"
                        ></b-form-input>
                    </b-form-group>
                </div>
                <div class="mb-3">
                    <b-form-file
                        v-model="image"
                        :state="Boolean(image)"
                        placeholder=""
                        drop-placeholder="Glisser l'image içi..."
                    >
                    </b-form-file>
                </div>
                <div class="mb-3">
                    <label class="col-md-2 col-form-label">Variété</label>
                    <a href="/varietes/list" v-b-tooltip.hover title="Ajouer une variété">
                        <i class="mdi mdi-database-plus"></i>
                    </a>
                    <div class="col-md-4">
                      <select class="form-control"  v-model="formData.variete_id" required>
                        <option v-for="item in varietes" :key="item.id"
                        :value="item.id">{{item.libelle}}</option>
                      </select>

                    </div>
                  </div>
                
                <div class="mb-3">
                    <b-form-group
                        id="input-group-1"
                        label="Quantité:"
                        label-for="quantite"
                    >
                        <b-form-input  
                            id="quantite"
                            v-model="formData.quantite"
                            type="number"
                            required                    
                            class="form-control"
                        ></b-form-input>
                    </b-form-group>
                </div>
                <div class="text-end mt-3">
                    <b-button variant="light"  @click="$bvModal.hide('my-modal')">Fermer</b-button>
                    <b-button type="submit" variant="success" class="ms-1">Envoyer</b-button>
                </div>
            </div>
        </div>

        </b-form>
</b-modal>

</div>

</template>

<style scoped>

</style>
