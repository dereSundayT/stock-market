<template>
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3"> \j</div>
            </a>
            <!-- Divider -->
            <hr class="sidebar-divider my-0">
            <!-- Nav Item - Dashboard -->
            <!-- <li class="nav-item active">
                <router-link class="nav-link" :to="{name:'dashboard'}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></router-link>
            </li> -->
            <!-- Nav Item - Stock -->
            <li class="nav-item">
                <router-link class="nav-link" :to="{name:'stock'}">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Stock</span>
                </router-link>
            </li>
             <!-- Nav Item - Virtual Investment -->
            <li class="nav-item">
                <router-link class="nav-link" :to="{name:'virtualinvestment'}">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Virtual Investment</span></router-link>
            </li>
        
        </ul>
</template>