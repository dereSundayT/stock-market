<template>
    <dashboard-wrapper :loading="loading" :snackbar="snackbar" :msg="msg"> 
        <template slot="contents"> 
            <h1 class="h3 mb-2 text-gray-800">All Client</h1>
            <p class="mb-4"></p>
            <div class="card shadow mb-4">
                <!-- page heading -->
                <div class="card-header py-3 d-flex justify-content-between">
                    <!-- <button class="btn btn-danger btn-sm" @click="addNewClientForm">Trash()</button> -->
                    <button class="btn btn-outline-primary btn-sm" @click="addNewClientForm">New Client</button>
                </div>
                <!-- end of page heading -->
                <div class="card-body">
                    <!-- datatable -->
                    <template>
                        <v-card>
                            <v-card-title>
                                <v-spacer></v-spacer>
                                <v-text-field
                                    v-model="search"
                                    append-icon="mdi-magnify"
                                    label="Search"
                                    single-line
                                    hide-details
                                />
                            </v-card-title>
                            <!-- datatable -->
                            <v-data-table       
                                :headers="headers"
                                :items="itemsWithIndex"
                                :search="search"
                                :loading="loading"
                                loading-text="Loading... Please wait"
                            > 
                                <!-- profit_staus -->
                                <template v-slot:item.profit_status="{ item }">
                                    <span v-if="item.profit_status > 0" class="green">
                                    + € {{ item.profit_status }}
                                    </span>
                                    <span v-if="item.profit_status < 0" class="red">
                                    - € {{ Math.abs(item.profit_status) }}
                                    </span>
                                    <span v-if="item.profit_status === 0">
                                        € {{ item.profit_status }}
                                    </span>
                                
                                    
                                </template>
                                <!-- action buttons -->
                                <template v-slot:item.actions="{ item }">
                                 
                                    <router-link :to="{name:'clientstock',params: { clientID: item.id}}">
                                            <v-icon
                                            small
                                            title="View Stocks"
                                        >
                                            mdi-eye
                                        </v-icon>
                                        </router-link>
                                    
                                </template>
                            </v-data-table>
                        </v-card>
                    </template>
                    <!-- datatablr -->
                    <!-- stock form modal for both Adding & Editting -->
                   <v-row justify="center">
                        <v-dialog
                            v-model="dialog"
                            persistent
                            max-width="600px"
                        >
                            <v-card>
                                <v-card-title>
                                    <span class="text-h5">Add new Client </span>
                                    <v-spacer></v-spacer>
                                </v-card-title>
                                <v-card-text>
                                    <v-container>
                                        <v-row>
                                            <v-col cols="12">
                                                <v-text-field
                                                label="username*"
                                                type="text"
                                                v-model="client.username"
                                                required
                                                ></v-text-field>
                                            </v-col>
                                        </v-row>    
                                    </v-container>
                                </v-card-text>
                                <v-card-actions v-if="formButtonControl">
                                    <v-spacer></v-spacer>
                                    <v-btn
                                        color="blue darken-1"
                                        text
                                        @click="dialog=false"
                                    >
                                        Close
                                    </v-btn>
                
                                    <v-btn
                                        color="blue darken-1"
                                        text
                                        @click="addNewClient"
                                    >
                                        Add
                                    </v-btn>
                                </v-card-actions>
                                <v-card-actions v-else>
                                    <v-spacer></v-spacer>
                                     <v-progress-circular
                                        indeterminate
                                        size="30">
                                     </v-progress-circular>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </v-row>
                     <!-- stock form modal for both Adding & Editting -->
                   
                 
                </div>
            </div>
        </template>
    </dashboard-wrapper>
</template>

<script>
import DashboardWrapper from '../DashboardWrapper.vue'
import axios from 'axios'

export default {
    components:{DashboardWrapper},
    data () {
        return {
            loading:false,
            dialog: false,
            delDialog: false,
            search: '',
            formButtonControl: true,
            //
            snackbar: false,
            msg: '',
            //
            headers: [
            {
                text: 'S/N',
                align: 'start',
                sortable: false,
                value: 'index',
            },
            { text: 'Client', value: 'username' },
            { text: 'Cash Balance', value: 'formated_wallet_bal' },
            { text: 'Gain/Loss', value: 'profit_status' },
            { text: 'Action', value: 'actions' },
            ],
            clients: [],
            edit: false,
            client : {
                username : '',
            },
            
      }
    },
    created () {
        this.fetchClients()
    },
    methods:{
          formatedErrorResponse(error){
            this.loading = false
            this.formButtonControl = true
            let msg = error.response === undefined
					? 'No internet, please ensure you are connected to the internet'
					: error.response.data.errors === undefined
					? error.response.data.message
					: error.response.data.errors
            this.snackbar = true
            if(error.response.status === 401){
                    localStorage.clear();
                    this.$router.push({name:'login'})
            }else{
                return msg
            }

        },
        addNewClientForm () {
            this.edit = false
            this.dialog = true
             this.client = {
                username : '',
            }
        },
        async addNewClient() {
            this.loading = true
            this.formButtonControl = false
            try {
                 const res = await axios.post('api/v1/virtual-investment/clients',this.client)
                 if(res.data.status==='success'){
                    this.msg = res.data.message
                    this.dialog = false
                    this.snackbar = true
                    this.stock = {}
                     this.loading = false
                    this.formButtonControl = true
                    this.fetchClients()
                }
            } catch (error) {
                this.msg  = this.formatedErrorResponse(error)
            }
           
        },
        async fetchClients() {
           this.loading = true
           try {
               const res = await axios.get('api/v1/virtual-investment/clients')
               if(res.data.status==='success'){
                    // this.msg = res.data.message
                    let initialClient = res.data.data
                    //sorting
                    initialClient.sort((a,b) => a.profit_status - b.profit_status)
                    initialClient.reverse()
                    this.clients =  initialClient
                    this.loading = false
                    this.formButtonControl = true 
                }
            } catch (error) {
               this.msg = this.formatedErrorResponse(error)
           }
        },
      //
    },
    computed: {
    itemsWithIndex() {
      return this.clients.map(
        (items, index) => ({
          ...items,
          index: index + 1
        }))
    }
  }
    
}
</script>