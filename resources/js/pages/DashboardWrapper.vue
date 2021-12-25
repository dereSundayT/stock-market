<template>
    <div data-app>
        <div id="wrapper">
            <loading v-if="loading"/>
            <snack-bar :msg="msg" v-if="snackbar"/>
            <side-bar/>
            <div id="content-wrapper" class="d-flex flex-column">
                <div id="content">
                    <top-bar :authData="authData"/>
                    <div class="container-fluid"> 
                        <slot name="contents" :token="token" :authData="authData"></slot>
                     </div>
                </div>
                <footer class="sticky-footer bg-white">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <span>Copyright &copy; Your Website 2021</span>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>
    </div>
</template>


<script>
import SideBar from './layouts/SideBar.vue'
import TopBar from'./layouts/TopBar.vue'
import Loading from '../components/Loading.vue'
import SnackBar from '../components/SnackBar.vue'

export default {
    components:{SideBar,TopBar,Loading,SnackBar},
    props: ['loading','snackbar','msg'],
    data () {
        return{
            token : null ,
            authData : null
        }
    },
    created () {
        this.token  = localStorage.getItem('token')
        this.authData = JSON.parse(localStorage.getItem('authData'))
        if(this.token===null){
            this.$router.push('home')
        }
    }
}
</script>