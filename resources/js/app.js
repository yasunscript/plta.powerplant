require("./bootstrap");

import Vue from "vue";
import router from "./router.js";
import store from "./store.js";
import App from "./App.vue";
import BootstrapVue from "bootstrap-vue";
import Permissions from './mixins/Permission.js'

Vue.use(BootstrapVue);
Vue.mixin(Permissions);

import "bootstrap-vue/dist/bootstrap-vue.css";
import { mapActions, mapGetters, mapState } from "vuex";

new Vue({
    el: "#app",
    router,
    store,
    components: {
        App
    },
    computed: {
        ...mapGetters(["isAuth"]),
        ...mapState(["token"]),
        ...mapState("user", {
            user_authenticated: state => state.authenticated
        })
    },
    methods: {
        ...mapActions("user", ["getUserLogin"])
    },
    created() {
        if (this.isAuth) {
            this.getUserLogin();
        }
    }
});
