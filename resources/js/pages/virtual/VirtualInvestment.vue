<template>
    <dashboard-wrapper :loading="loading" :snackbar="snackbar" :msg="msg"> 
        <template slot="contents"> 
            <h1 class="h3 mb-2 text-gray-800">All Client</h1>
            <p class="mb-4"></p>
            <div class="card shadow mb-4">
                <!-- page heading -->
                <div class="card-header py-3 d-flex justify-content-between">
                    <button class="btn btn-danger btn-sm" @click="addNewClientForm">Trash()</button>
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
                                <!-- action buttons -->
                                <template v-slot:item.actions="{ item }">
                                    <v-icon
                                        small
                                        class="mr-2"
                                        title='Fund Wallet'
                                        @click="editUnitPrice(item)"
                                    >
                                        mdi-pencil
                                    </v-icon>
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
                                    <span class="text-h5" v-if="edit">Update Stock Unit Price</span>
                                    <span class="text-h5" v-else>Add new Stock </span>
                                    <v-spacer></v-spacer>
                                </v-card-title>
                                <v-card-text>
                                    <v-container>
                                           <v-row>
                                            <v-col cols="12">
                                                <v-text-field
                                                label="Company name*"
                                                type="text"
                                                v-model="client.username"
                                                required
                                                ></v-text-field>
                                            </v-col>
                                        </v-row>
                                        <v-row>
                                            <v-col cols="12">
                                                <!-- <v-text-field
                                                label="Stock unit Price*"
                                                type="number"
                                                v-model="client.wallet_balance"
                                                required
                                                ></v-text-field> -->
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
                                        v-if="edit"
                                        color="blue darken-1"
                                        text
                                        @click="addUpdateStock"
                                    >
                                        Update
                                    </v-btn>
                                    <v-btn
                                        v-else
                                        color="blue darken-1"
                                        text
                                        @click="addUpdateStock"
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
            }
      }
    },
    created () {
        this.fetchClients()
    },
    methods:{
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
            const res = await axios.post('api/v1/virtual-investment/clients',this.client)
            if(res.data.status==='success'){
                this.msg = res.data.message
                this.dialog = false
                this.snackbar = true
                this.stock = {}
                this.fetchStocks()
            }else{
             this.msg = res.data.message
         }
        this.loading = false
        this.formButtonControl = true
        },
        //
        addUpdateStock () {
            if(this.edit){
                //edit
                this.updateUnitPrice()
            }else{
                //create new stock
                this.addNewClient()
            }
        },
       async fetchClients() {
           this.loading = true
           try {
               const res = await axios.get('api/v1/virtual-investment/clients')
               if(res.data.status==='success'){
                    this.msg = res.data.message
                    let initialClient = res.data.data
                    //sorting by sortPrice
                    console.log(initialClient)
                    // initialStock.sort((a,b) => a.unit_price - b.unit_price)
                    // initialStock.reverse()
                    this.clients =  initialClient
                }else{
                    this.msg = res.data.message
                }
                this.loading = false
                this.formButtonControl = true   
            } catch (error) {
               this.loading = false
           }
        },
      //
      async  updateUnitPrice() {
          this.loading = true
          this.formButtonControl = false
          const data = {
              unit_price : this.stock.unit_price
          }
         //
         const res = await axios.put(`/api/v1/stocks/${this.stock.id}`,data)
         if(res.data.status==='success'){
            this.msg = res.data.message
            this.dialog = false
            this.snackbar = true
            this.stock = {
                company_name : '',
                unit_price : ''
            }
            this.fetchStocks()
         }else{
             this.msg = res.data.message
         }
        this.loading = false
        this.formButtonControl = true
        },
     //
        editUnitPrice(stock) {
            this.dialog = true
            this.stock = stock
            this.edit = true
        // console.log(stock)
        },
    async confirmDel(stock){
        this.stock = stock
        this.delDialog  = true
    },
    async deleteStock (data) {
        if(data==='no'){
            this.delDialog = false
            this.stock = {
                company_name : '',
                unit_price : ''
            }
        }else{
            this.loading = true
            this.formButtonControl = false
            const res = await axios.delete(`api/v1/stocks/${this.stock.id}`)
            if(res.data.status==='success'){
                this.msg = res.data.message
                this.delDialog = false
                this.snackbar = true
                this.stock = {
                    company_name : '',
                    unit_price : ''
                }
             this.fetchStocks()
         }else{
             this.msg = res.data.message
         }
        this.loading = false
        this.formButtonControl = true
        }

    } , 
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