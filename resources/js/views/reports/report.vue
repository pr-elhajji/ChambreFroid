<script>
import axios from "axios";
import Layout from "../../layouts/main";
import PageHeader from "../../components/page-header";
import _ from "lodash";

//import { saveExcel } from '@progress/kendo-vue-excel-export';

import { required,maxValue } from "vuelidate/lib/validators";
import XLSX from 'xlsx/xlsx';

export default {   
  components: { Layout, PageHeader },
  data() {
      return {
          records:null,
          title: "Rapports",
          items: [{
              text: "Acceuil",
              href: "/configuration"
          },
              {
                  text: "Rapport",
                  active: true,
              },

          ],
          tableData:[],
          totalRows: 1,
          currentPage: 1,
          perPage: 10,
          pageOptions: [10, 25, 50, 100],
          filter: null,
          filterOn: [],
          sortBy: "age",
          sortDesc: false,
          columns : [
                    {   label: "date et heure d'enregistrement",
                        field: "created_at",
                    },
                     {
                        label: "Température",
                        field: "temperature",
                    },
                    {
                        label: "Humidité",
                        field: "humedite",
                    },
                    {
                        label: "Etat de la porte",
                        field: "etat_porte",
                    },
                     {
                        label: "Etat compresseur",
                        field: "etat_compresseur",
                    },
                    {
                        label: "Etat evaporateur",
                        field: "etat_evaporateur",
                    },
                    
                ],
          fields: [
              { key: "created_at", sortable: false },
              { key: "temperature", sortable: false },
              { key: "humedite", sortable: false },
              { key: "etat_porte", sortable: false },
              { key: "etat_compresseur", sortable: true },
              { key: "etat_evaporateur", sortable: true },
             
          ],
             
          //Form search
          formData: {
            dateDebut: '',
            dateFin: '',
            typeClient: 'Tous',            
          },
          show: true,
          submitted : true             
      };
  },
  computed: {
    /**
     * Total no. of records
     */
    rows() {
      return this.tableData.length;
    },
  },
  mounted(){
      axios.get('/api/chambres/')
          .then(response => {
              this.records = response.data
          }) .catch(error => console.log(error))
  },  
  methods :{
    getData(){

    },
    onFiltered(){},
    onSearch(event) {
         event.preventDefault();
         // console.log(formData);
        axios.post("/api/raports/find/", this.formData)
              .then((response) => {
                  this.tableData = response.data;
                  this.$refs.table.refresh();
              }
              )
              .catch((err) => {
                console.log(err.response.data.errors);
                }
              );          
    },
    onReset(event) {
        event.preventDefault()
        // Reset our form values
        this.formData.dateDebut = Date.now();
        this.formData.dateFin = Date.now();
        //this.formData.typeClient = 'Tous';
        // Trick to reset/clear native browser form validation state
        this.show = true
       this.tableData=null
      },
      exportExcel() {
                let createXLSLFormatObj = [];
                let newXlsHeader = [];
                let vm = this;
                if (vm.columns.length === 0){
                    console.log("Add columns!");
                    return;
                }
                if (vm.tableData.length === 0){
                    console.log("Add data!");
                    return;
                }
                $.each(vm.columns, function(index, value) {
                    newXlsHeader.push(value.label);
                });
                createXLSLFormatObj.push(newXlsHeader);
                $.each(vm.tableData, function(index, value) {
                    let innerRowData = [];
                    $.each(vm.columns, function(index, val) {
                        if (val.dataFormat && typeof val.dataFormat === 'function') {
                            innerRowData.push(val.dataFormat(value[val.field]));
                        }else {
                            innerRowData.push(value[val.field]);
                        }
                    });
                    createXLSLFormatObj.push(innerRowData);
                });
                let filename = vm.filename + ".xlsx";
                let ws_name = vm.sheetname;
                let wb = XLSX.utils.book_new(),
                    ws = XLSX.utils.aoa_to_sheet(createXLSLFormatObj);
                XLSX.utils.book_append_sheet(wb, ws, ws_name);
                XLSX.writeFile(wb, filename);
            }
          
  }, 
  
}
</script>

<template>
    <Layout>
        <PageHeader :title="title" :items="items" />
          <div class="row">
             <div class="col-lg-12">
          <!-- Recherche -->
          <div class="card">
          <div class="card-body">
            <h4 class="card-title mb-4">Recherche</h4>
            <!-- Form -->
            <b-form  @submit="onSearch" @reset="onReset" v-if="show">            
              <div class="row">
                <div class="col-lg-6">
                   <b-form-group class="mb-3 form-label" id="example-date" 
                                 label="Date début" label-for="date">
                        <b-form-input id="dateDebut" v-model="formData.dateDebut"  type="date" required></b-form-input>
                  </b-form-group>
                </div>
                <div class="col-lg-6">
                   <b-form-group class="mb-3 form-label"  id="example-date"
                      label="Date fin" label-for="date" >
                    <b-form-input id="dateFin" value="2021-05-01" v-model="formData.dateFin"  type="date" required></b-form-input>
                  </b-form-group>
                </div>
                <!--<div class="col-lg-4">
                  <b-form-group class="mb-3 form-label" id="input-group-1" label="Type de client" label-for="formrow-inputState" >
                    <b-form-select v-model="formData.typeClient" class="form-select"  id="formrow-inputState"
                      :options="[
                                { value: 'Tous', text: 'Tous' },
                                { value: 'Externe', text: 'Client externe' },
                                { value: 'Entrepot', text: 'Entrepôt' },                              
                              ]"
                      type="text"
                    ></b-form-select>
                  </b-form-group>
                </div>-->
              </div>             
              <div>
                <b-button type="submit" variant="primary">Rechercher</b-button>
                <b-button type="reset" variant="danger">Reset</b-button>
              </div>
            </b-form>
          </div>
        </div>
      </div>
    </div>
<!-- Fin zone recherche -->
<!-- TODO Barre d'export -->
<!-- resultat -->
<div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Rapport</h4>
                <div class="row mt-4">
                    <div class="col-sm-12 col-md-6">
                        <div class="button-items">
                            <b-button variant="primary" @click="exportExcel">
                                <i class="mdi mdi-microsoft-excel"></i> Exporter en Excel
                                 
                            </b-button>
                            <!--<b-button variant="primary">
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
            <div class="table-responsive mb-0">
              <b-table
                ref="table"
                class="datatables"
                :items="tableData"
                :fields="fields"
                responsive="sm"
                :per-page="perPage"
                :current-page="currentPage"
                :sort-by.sync="sortBy"
                :sort-desc.sync="sortDesc"
                :filter="filter"
                :filter-included-fields="filterOn"
                @filtered="onFiltered"
              ></b-table>
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
</Layout>

</template>

<style scoped>

</style>