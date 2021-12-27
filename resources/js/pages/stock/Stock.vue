<template>
    <dashboard-wrapper :loading="loading" :snackbar="snackbar" :msg="msg">
        <template slot="contents">
            <h1 class="h3 mb-2 text-gray-800">Stock Listing</h1>
            <p class="mb-4"></p>
            <div class="card shadow mb-4">
                <!-- page heading -->
                <div class="card-header py-3 d-flex justify-content-between">
                    <!-- <button class="btn btn-danger btn-sm" @click="addNewStockForm">Trash()</button> -->
                    <button
                        class="btn btn-outline-primary btn-sm"
                        @click="addNewStockForm"
                    >
                        New Stock
                    </button>
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
                                        @click="editUnitPrice(item)"
                                    >
                                        mdi-pencil
                                    </v-icon>
                                    <v-icon small @click="confirmDel(item)">
                                        mdi-delete
                                    </v-icon>
                                </template>
                            </v-data-table>
                        </v-card>
                    </template>
                    <!-- datatablr -->
                    <!-- stock form modal for both Adding & Editting -->
                    <v-row justify="center">
                        <v-dialog v-model="dialog" persistent max-width="600px">
                            <v-card>
                                <v-card-title>
                                    <span class="text-h5" v-if="edit"
                                        >Update Stock Unit Price</span
                                    >
                                    <span class="text-h5" v-else
                                        >Add new Stock
                                    </span>
                                    <v-spacer></v-spacer>
                                </v-card-title>
                                <v-card-text>
                                    <v-container>
                                        <v-row>
                                            <v-col cols="12">
                                                <v-text-field
                                                    label="Company name*"
                                                    type="text"
                                                    v-model="stock.company_name"
                                                    required
                                                ></v-text-field>
                                            </v-col>
                                        </v-row>
                                        <v-row>
                                            <v-col cols="12">
                                                <v-text-field
                                                    label="Stock unit Price*"
                                                    type="number"
                                                    v-model="stock.unit_price"
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
                                        @click="dialog = false"
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
                                        size="30"
                                    >
                                    </v-progress-circular>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </v-row>
                    <!-- confirm delete -->
                    <v-dialog v-model="delDialog" width="500">
                        <v-card>
                            <v-card-title class="text-h5 grey lighten-2">
                                Confirm Delete
                            </v-card-title>
                            <v-card-text>
                                Are you sure you want to delete this stock
                                <b>{{ stock.company_name }}</b>
                            </v-card-text>
                            <v-divider></v-divider>
                            <!--  -->
                            <v-card-actions v-if="formButtonControl">
                                <v-spacer></v-spacer>
                                <v-btn
                                    class="ma-2"
                                    color="primary"
                                    depressed
                                    text
                                    @click="deleteStock('no')"
                                >
                                    Cancel
                                </v-btn>
                                <v-btn
                                    color="error"
                                    text
                                    depressed
                                    @click="deleteStock('yes')"
                                >
                                    Yes, Delete
                                </v-btn>
                            </v-card-actions>
                            <!--  -->
                            <v-card-actions v-else>
                                <v-spacer></v-spacer>
                                <v-progress-circular indeterminate size="30">
                                </v-progress-circular>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                    <!-- end of confirm Delete -->
                </div>
            </div>
        </template>
    </dashboard-wrapper>
</template>

<script>
import DashboardWrapper from "../DashboardWrapper.vue";
import axios from "axios";

export default {
    components: { DashboardWrapper },
    data() {
        return {
            loading: false,
            dialog: false,
            delDialog: false,
            search: "",
            formButtonControl: true,
            //
            snackbar: false,
            msg: "",
            //
            headers: [
                {
                    text: "S/N",
                    align: "start",
                    sortable: false,
                    value: "index",
                },
                { text: "Company", value: "company_name" },
                { text: "Unit price", value: "formated_unit_price" },
                { text: "Updated at", value: "updated_at" },
                { text: "Action", value: "actions" },
            ],
            stocks: [],
            edit: false,
            stock: {
                company_name: "",
                unit_price: "",
            },
        };
    },
    created() {
        this.fetchStocks();
    },
    methods: {
        clr() {
            this.msg = ''
            this.snackbar = false
        },
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
        validateForm(stock) {
            if (stock.company_name === "" || stock.unit_price === "") {
                this.loading = false;
                this.formButtonControl = true;
                this.snackbar = true;
                this.msg = "Error:: Please fill all the field";
                return true;
            } else {
                return false;
            }
        },
        addNewStockForm() {
            this.edit = false;
            this.dialog = true;
            this.stock = {
                company_name: "",
                unit_price: "",
            };
        },
        async addNewStock() {
            this.loading = true;
            this.formButtonControl = false;
            this.clr()
            if (!this.validateForm(this.stock)) {
                try {
                    const res = await axios.post("api/v1/stocks", this.stock);
                    if (res.data.status === "success") {
                        this.msg = res.data.message;
                        this.dialog = false;
                        this.snackbar = true;
                        this.stock = {};
                        this.fetchStocks();
                    } else {
                        this.msg = res.data.message;
                        this.snackbar = true;
                    }
                    this.loading = false;
                    this.formButtonControl = true;
                } catch (error) {
                   this.formatedErrorResponse(error)
                }
            }
        },
        addUpdateStock() {
            if (this.edit) {
                //edit
                this.updateUnitPrice();
            } else {
                //create new stock
                this.addNewStock();
            }
        },
        async fetchStocks() {
            this.loading = true;
            try {
                const res = await axios.get("api/v1/stocks");
                if (res.data.status === "success") {
                    // this.msg = res.data.message
                    let initialStock = res.data.data;
                    //sorting by sortPrice
                    initialStock.sort((a, b) => a.unit_price - b.unit_price);
                    initialStock.reverse();
                    this.stocks = initialStock;
                } else {
                    this.msg = res.data.message;
                }
                this.loading = false;
                this.formButtonControl = true;
            } catch (error) {
               this.msg =  this.formatedErrorResponse(error)
            }
        },
        //
        async updateUnitPrice() {
            this.loading = true;
            this.formButtonControl = false;
            if (!this.validateForm(this.stock)) {
                const data = {
                    unit_price: this.stock.unit_price,
                };
                try {
                    const res = await axios.put(
                        `/api/v1/stocks/${this.stock.id}`,
                        data
                    );
                    if (res.data.status === "success") {
                        this.msg = res.data.message;
                        this.dialog = false;
                        this.snackbar = true;
                        this.stock = {
                            company_name: "",
                            unit_price: "",
                        };
                        this.fetchStocks();
                    } else {
                        this.msg = res.data.message;
                    }
                    this.loading = false;
                    this.formButtonControl = true;
                } catch (error) {
                   this.formatedErrorResponse(error)
                }
            }
        },
        //
        editUnitPrice(stock) {
            this.dialog = true;
            this.stock = stock;
            this.edit = true;
        },
        async confirmDel(stock) {
            this.stock = stock;
            this.delDialog = true;
        },
        async deleteStock(data) {
            if (data === "no") {
                this.delDialog = false;
                this.stock = {
                    company_name: "",
                    unit_price: "",
                };
            } else {
                this.loading = true;
                this.formButtonControl = false;
                try {
                    const res = await axios.delete(
                    `api/v1/stocks/${this.stock.id}`
                );
                if (res.data.status === "success") {
                    this.msg = res.data.message;
                    this.delDialog = false;
                    this.snackbar = true;
                    this.stock = {
                        company_name: "",
                        unit_price: "",
                    };
                    this.loading = false;
                    this.formButtonControl = true;
                    this.fetchStocks();
                }
                } catch (error) {
                    this.msg = this.formatedErrorResponse(error)
                }
            }
        },
    },
    computed: {
        itemsWithIndex() {
            return this.stocks.map((items, index) => ({
                ...items,
                index: index + 1,
            }));
        },
    },
};
</script>
