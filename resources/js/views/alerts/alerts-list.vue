<script>
import axios from "axios";
export default {
    props: ['id','intervale'],
    data (){
        return {
            alerts: [],
            fields: [
              { key: "chambre", sortable: true },
              { key: "message", sortable: true },
              { key: "date", sortable: true }              
             ],
            // pagination & filtrage
            sortBy: "",
            sortDesc: false,
            filter: null,
            filterOn: [],
            totalRows: 1,
            currentPage: 1,
            perPage: 10,

        
            // fin pagination & filtrage
        }
    },
    methods:{
        getData: function () {
            // pour charque chambre
            let url="";
            if (this.id ===0) //tous
                url="/api/chambres/alerts/"+0+"/"+this.intervale;
            else 
                url="/api/chambres/alerts/"+this.id+"/"+this.intervale; // une chambre

             axios.get(url)  // 1 par semaine
                 .then(response => {
                     this.alerts=response.data;                                          
                 })
                 .catch(error => console.log(error));
         },

         /**
     * Search the table data with search input
     */
    onFiltered(filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.length;
      this.currentPage = 1;
    },

    },
    computed: {
    /**
     * Total no. of records
     */
    rows() {
      return this.alerts.length;
    },
  },
    mounted() {
        // TODO requete le taux d'auccupation ou calculer le taux = 100* Taux réel/capacité max
        // TODO Récupérer les températeure et l'humidité de la dernière journées
        
        //this.interval = setInterval(this.getData, 60000);   
        this.getData();    

    },
}
</script>
<template>    
        
    <div class="card">
        <div class="card-body">
              <!-- Search -->
              <div class="col-12">
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
            <div class="table-responsive">

            <!-- Table -->
              <b-table
                ref="table"
                class="datatables"
                :items="alerts"
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
    
</template>

<style scoped>

</style>
