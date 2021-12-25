<template>
    <dashboard-wrapper :loading="loading" :snackbar="snackbar" :msg="msg"> 
        <template slot="contents"> 
            <h1 class="h3 mb-2 text-gray-800">Clients Stock Lisiting  <small style="font-size:13px">{{this.userDetails.username}}</small></h1>
            <p class="mb-4"></p>
            <div class="card shadow mb-4">
                <!-- page heading -->
                <div class="card-header py-3 d-flex justify-content-between">
                    <div> 
                        <!-- <span>back</span> -->
                        <button class="btn btn-primary btn-sm" @click="fundClientWallet">Fund Wallet</button>
                    </div>
                    <button class="btn btn-outline-primary btn-sm"  @click="dialog=true">Purchase Stock</button>
                </div>
                <!-- end of page heading -->
                <div class="card-body">
                    <!-- datatable -->
                    <template>
                        <v-card>
                            <!-- datatable -->
                            <v-data-table       
                                :headers="headers"
                                :items="itemsWithIndex"
                            >
                            <template v-slot:item.profit_status="{ item }">
                                    <span v-if="item.profit_status > 0" class="green">
                                    + € {{ item.profit_status }}
                                    </span>
                                    <span v-if="item.profit_status < 0" class="red">
                                    - € {{ item.profit_status }}
                                    </span>
                                    <span v-if="item.profit_status === 0">
                                        € {{ item.profit_status }}
                                    </span>
                                
                                    
                                </template>

                            </v-data-table> 
                            <!-- basic formation -->
                            <v-row>
                                 <!-- -->
                                <v-col cols="4"></v-col>
                                <v-col cols="4"></v-col>
                                <v-col cols="4">
                                    <v-row>
                                        <v-col cols="3"></v-col>
                                        <v-col cols="5">Total</v-col> 
                                        <v-col cols="4">
                                            <span v-bind:class="[summary.total_status ? 'green' : 'red']"> {{summary.total}}</span>
                                        </v-col>
                                    </v-row>
                                      <v-row>
                                        <v-col cols="3"></v-col>
                                        <v-col cols="5">Invested</v-col>
                                        <v-col cols="4">{{summary.invested}}</v-col>
                                    </v-row>
                                      <v-row>
                                        <v-col cols="3"></v-col>
                                        <v-col cols="5">Performance</v-col>

                                        <v-col cols="4">
                                            <span v-bind:class="[summary.performance_status ? 'green' : 'red']"> {{summary.performance}}</span>
                                        </v-col>
                                    </v-row>
                                      <v-row>
                                        <v-col cols="3"></v-col>
                                        <v-col cols="5">Cash Balance</v-col>
                                        <v-col cols="4">{{userDetails.formated_wallet_bal}}</v-col>
                                    </v-row>
                                </v-col>
                            </v-row>
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
                                                <select class="form-control" v-model="selected">
                                                    <option value=""> select a stock</option>
                                                    <option v-for="stock in stocks" :key="stock.id" v-bind:value="stock.id" > 
                                                        {{stock.company_name}}  | €{{stock.unit_price}}
                                                    </option>
                                                </select>

        
                                                </v-col>
                                        </v-row>
                                        <v-row>
                                            <v-col cols="12">
                                                <v-text-field
                                                label="Volume*"
                                                type="number"
                                                v-model="volume"
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
            { text: 'Gain/Loss', value: 'profit_status' },
            ],
            clientID:'',
            stocks: [],
            mystocks: [],
            userDetails : '', 
            summary : '', 
            selected : '',  
            volume : 1
        
      }
    },
    created () {
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
                    this.userDetails = res.data.user
                    this.summary = res.data.summary
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
            this.loading = true
            this.formButtonControl = false
            let data = {
                volume:this.volume,
                stock_id:this.selected,
                client_id:this.clientID
                }
            try {
                const res = await axios.post('/api/v1/virtual-investment',data)
                if(res.data.status==='success'){
                this.msg = res.data.message
                this.snackbar = true
                this.dialog = false
                this.selected = ''
                this.volume = 1
                this.formButtonControl = true
                this.fetchClientStocks(this.clientID)

            }else{
                this.msg = res.data.message
            }
            this.loading = false
            this.formButtonControl = true
                
            } catch (error) {
                this.loading = false
            }
           
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