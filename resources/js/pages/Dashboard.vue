<template>
<dashboard-wrapper :loading="loading" :snackbar="snackbar" :msg="msg"> 
    <template slot="contents"> 
          <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                        
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <!-- Total No of Client -->
                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Total No of Client(s)</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{dashboardSummary.no_of_clients}}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-user fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Total No of Stock</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{dashboardSummary.no_of_stocks}}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total N0 Purchased Stock
                                            </div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{dashboardSummary.purchased_stock}}</div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->
                </div>
        
    </template>
</dashboard-wrapper>
</template>


<script>
import axios from 'axios'
import DashboardWrapper from './DashboardWrapper'
import LineChart from './dashboard/LineChart.js'
 
export default {
    components:{DashboardWrapper,LineChart},
     data: () => ({
         loading : false,
         snackbar: false,
         msg :'',
        dashboardSummary : '',
       //
    }),
    methods : {
         formatedErrorResponse(error){
            this.loading = false
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
        async getDashboardSummary(){
            try {
                this.loading= true
                 const res =  await axios.get('/api/v1/dashboard');
                 if(res.data.status === 'success'){
                     this.loading = false
                     this.dashboardSummary = res.data.data
                 }
            } catch (error) {
                this.formatedErrorResponse(error)
            }
        },
       
     
    },
    created(){
        this.getDashboardSummary()
    },
}
</script>