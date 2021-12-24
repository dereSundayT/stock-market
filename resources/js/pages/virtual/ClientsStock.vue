<template>
    <dashboard-wrapper :loading="loading" :snackbar="snackbar" :msg="msg"> 
        <template slot="contents"> 
            <h1 class="h3 mb-2 text-gray-800">Clients Stock Lisiting  <small>username</small></h1>
            <p class="mb-4"></p>
            <div class="card shadow mb-4">
                <!-- page heading -->
                <div class="card-header py-3 d-flex justify-content-between">
                    <button class="btn btn-primary btn-sm" @click="fundClientWallet">Fund Wallet</button>
                    <button class="btn btn-outline-primary btn-sm"  @click="dialog=true">Purchase Stock</button>
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
                                    <span class="text-h5">Purchase a Stock</span>
                                    <v-spacer></v-spacer>
                                </v-card-title>
                                <v-card-text>
                                    <v-container>
                                        <v-row>
                                            <v-col cols="12">
                                                <select class="form-group">
                                                    <option v-for="stock in stocks" :key="stock.id" > {{stock.company_name}} </option>
                                                </select>
                                            </v-col>
                                        </v-row>
                                        <v-row>
                                            <v-col cols="12">
                                                <v-text-field
                                                label="Volume*"
                                                type="number"
                                                v-model="stock.volume"
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
                                        @click="purchaseStock"
                                    >
                                        BUY
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
            { text: 'Company', value: 'stock.company_name' },
            { text: 'Volume', value: 'volume' },
            { text: 'Purchase Price', value: 'formated_purchase_price' },
            { text: 'Current Price', value: 'formated_current_price' },
            { text: 'Gain/Loss', value: 'profit_status.amount' },
            ],
            clientID:'',
            stocks: [],
            mystocks: [],
            stock : {
                "client_id": this.clientID,
                "stock_id": '',
                "volume":1
                }
      }
    },
    created () {
        console.log()
        let cid = this.$route.params.clientID
        this.clientID = cid
        this.fetchClientStocks(cid)
    },
    methods:{
       async fetchClientStocks(id) {
           this.loading = true
           try {
               const res = await axios.get(`/api/v1/virtual-investment/clients/${id}`)
               if(res.data.status==='success'){
                    this.msg = res.data.message
                    let initialStock = res.data.data
                    //sorting by sortPrice
                    // console.log(initialClient)
                    // initialStock.sort((a,b) => a.unit_price - b.unit_price)
                    // initialStock.reverse()
                    this.mystocks =  initialStock
                    this.stocks = res.data.stocks
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
        async fundClientWallet() {

        },
        async purchaseStock(){
            console.log(this.stock)
        }
    },
    //
    computed: {
    itemsWithIndex() {
      return this.mystocks.map(
        (items, index) => ({
          ...items,
          index: index + 1
        }))
    }
  }
    
}
</script>