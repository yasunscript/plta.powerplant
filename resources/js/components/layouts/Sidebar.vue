<template>
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="index3.html" class="brand-link">
            <img
                src="dist/img/AdminLTELogo.png"
                alt="AdminLTE Logo"
                class="brand-image img-circle elevation-3"
                style="opacity: 0.8;"
            />
            <span class="brand-text font-weight-light">AdminLTE 3</span>
        </a>

        <div class="sidebar">
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img
                        :src="`dist/img/${authenticated.photo}`"
                        class="img-circle elevation-2"
                        alt="User Image"
                    />
                </div>
                <div class="info">
                    <a href="#" class="d-block">{{ authenticated.name }}</a>
                </div>
            </div>

            <nav class="mt-2">
                <ul
                    class="nav nav-pills nav-sidebar flex-column"
                    data-widget="treeview"
                    role="menu"
                    data-accordion="false"
                >
                    <li class="nav-item">
                        <router-link exact :to="{ name: 'home' }" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt purple"></i>
                            <p>Dashboard</p>
                        </router-link>
                    </li>
                    <li v-if="$can('read pembangkit')" class="nav-item">
                        <router-link :to="{ name: 'pembangkit' }" class="nav-link">
                            <i class="nav-icon fas fa-cogs yellow"></i>
                            <p>Pembangkit</p>
                        </router-link>
                    </li>
                    <li v-if="$can('read penyaluran')" class="nav-item">
                        <router-link :to="{ name: 'penyaluran' }" class="nav-link">
                            <i class="nav-icon fas fa-broadcast-tower indigo"></i>
                            <p>Penyaluran</p>
                        </router-link>
                    </li>
                    <li v-if="$can('read report')" class="nav-item">
                        <router-link :to="{ name: 'report' }" class="nav-link">
                            <i class="nav-icon fas fa-chart-bar green"></i>
                            <p>Report</p>
                        </router-link>
                    </li>
                    <!--                    -->

                    <li class="nav-item has-treeview" v-if="$can('read roles')">
                        <a href="#" class="nav-link" :class="{ active: $route.name === 'role.permissioins' }">
                            <i class="nav-icon fas fa-table"></i>
                            <p>
                                Setting
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" >
                            <li class="nav-item"><router-link class="nav-link" :to="{name: 'role.permissions'}"><i class="far fa-circle nav-icon"></i>Role Permission</router-link></li>
                            <li class="nav-item"><router-link class="nav-link" :to="{name: 'users'}"><i class="far fa-user nav-icon"></i>Users</router-link></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="javascript:void(0);" @click="logout" class="nav-link">
                            <i class="nav-icon fas fa-power-off red"></i>
                            <p>Logout</p>
                        </a>
                    </li>

                </ul>
            </nav>
        </div>
    </aside>
</template>

<script>
import { mapState } from "vuex";
export default {

    computed: {
        ...mapState("user", {
            authenticated: state => state.authenticated
        })

    },
    methods: {
        logout() {
            return new Promise((resolve, reject) => {
                localStorage.removeItem("token");
                resolve();
            }).then(() => {
                this.$store.state.token = localStorage.getItem("token");
                this.$router.push("/login");
            });
        }
    }
};
</script>

<style lang="scss" scoped></style>
