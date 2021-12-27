<template>
    <div class="container">
        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div
                                class="col-lg-6 d-none d-lg-block bg-login-image"
                            ></div>
                            <div class="col-lg-6">
                                <div class="py-5"></div>
                                    <div class="py-5"></div>
                                <div class="p-5">
                                    
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">
                                            Welcome Back!
                                        </h1>
                                        <div class="alert alert-danger" v-if="error"> {{msg}} </div>
                                    </div>
                                    <div class="user">
                                        <div class="form-group">
                                            <input
                                                type="email"
                                                class="form-control form-control-user"
                                                id="exampleInputEmail"
                                                aria-describedby="emailHelp"
                                                placeholder="Enter Email Address..."
                                                v-model="form.email"
                                                required
                                            />
                                        </div>
                                        <div class="form-group">
                                            <input
                                                type="password"
                                                class="form-control form-control-user"
                                                id="exampleInputPassword"
                                                placeholder="Password"
                                                v-model="form.password"
                                                required
                                            />
                                        </div>
                                        <div class="form-group">
                                            <div
                                                class="custom-control custom-checkbox small"
                                            >
                                                <input
                                                    type="checkbox"
                                                    class="custom-control-input"
                                                    id="customCheck"
                                                />
                                            </div>
                                        </div>
                                        <button
                                            v-if="loading"
                                            class="btn btn-primary btn-user btn-block"
                                        >
                                            <v-progress-circular
                                                
                                                indeterminate
                                                size="30">
                                            </v-progress-circular>
                                        </button>
                                        <button
                                            v-else
                                            class="btn btn-primary btn-user btn-block"
                                            @click="login"
                                        >
                                            Login
                                        </button>
                                        <!-- <hr> -->
                                    </div>
                                    <hr />
                                    <div class="text-center">
                                        <!-- <a class="small" href="forgot-password.html">Forgot Password?</a> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            loading: false,
            error : false,
            msg : '',
            form: {
                email: "admin@demo.com",
                password: "123456",
            },
        };
    },
    methods: {
        async login() {
            this.loading = true;
            this.error = false
            try {
                 const res = await axios.post("/api/v1/login", this.form);
                 
                if(res.data.status==='success'){
                    this.loading = false
                    //store the information of this user in localStorage
                    const userData = res.data.data
                    const token = res.data.token
                    //
                    localStorage.setItem("authData", JSON.stringify(userData));
                    localStorage.setItem("token",token)
                    //redirec to dahboard page
                    this.$router.push({name:'dashboard'})
                }else{
                    this.loading = false;
                }
                
            } catch (error) {
                this.loading = false;
                const errMsg =	error.response === undefined
					? 'No internet, please ensure you are connected to the internet'
					: error.response.data.errors === undefined
					? error.response.data.message
					: error.response.data.errors
                this.msg = errMsg
                this.error= true
            }
            
           
        },
    },
    created (){
         this.token  = localStorage.getItem('token')
        if(this.token!==null){
            this.$router.push({name:'dashboard'})
        }

    }
};
</script>
