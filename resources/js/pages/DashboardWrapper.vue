<template>
    <div>
        <div id="wrapper">
            <side-bar/>
            <div id="content-wrapper" class="d-flex flex-column">
                <div id="content">
                    <top-bar/>
                    <div class="container-fluid"> 
                        <slot name="contents"></slot>
                        
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

export default {
    components:{SideBar,TopBar}
}
</script>