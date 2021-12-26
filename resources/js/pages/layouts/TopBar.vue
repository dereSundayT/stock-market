<template>
     <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
        <!-- Sidebar Toggle (Topbar) -->
        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
        </button>
        <!-- Topbar Navbar -->
        <ul class="navbar-nav ml-auto">
            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{authData.name}}</span>
                    <img class="img-profile rounded-circle"
                        src="assets/img/undraw_profile.svg">
                </a>
                <!-- Dropdown - User Information -->
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                    aria-labelledby="userDropdown">
                    <a class="dropdown-item" @click="logout">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                        Logout
                    </a>
                </div>
            </li>

        </ul>
        <v-overlay :value="loading">
      <v-progress-circular
        indeterminate
        size="64"
      ></v-progress-circular>
    </v-overlay>
</nav>
</template>

<script>
import axios from 'axios'
import Loading from '../../components/Loading.vue'
export default {
    props : ['authData'],
    components: {Loading},
    data() {
        return {
            loading: false,
        }
    },
    methods : {
       async logout(){
            try {
                this.loading = true
                const res = await axios.post('/api/v1/logout')
                if(res.data.status==='success'){
                    this.loading = false
                    localStorage.clear();
                    this.$router.push({ name: "login"})
                }
                
            } catch (error) {
                this.loading = false 
                localStorage.clear();
                this.$router.push({ name: "home"})
            }
        }
    }
}
</script>