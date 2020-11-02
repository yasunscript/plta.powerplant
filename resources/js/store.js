import Vue from "vue";
import Vuex from "vuex";

import auth from "./stores/auth.js";
import user from "./stores/user.js";
import dashboard from "./stores/dashboard.js";
import pembangkit from "./stores/pembangkit.js";
import penyaluran from "./stores/penyaluran.js";
import report from "./stores/report.js";

Vue.use(Vuex);

const store = new Vuex.Store({
    modules: {
        auth,
        user,
        dashboard,
        pembangkit,
        penyaluran,
        report
    },
    state: {
        token: localStorage.getItem("token"),
        errors: []
    },
    getters: {
        isAuth: state => {
            return state.token != "null" && state.token != null;
        },
        isCan: (state, permissionName)=> {
            let Permission = state.user.authenticated.permission
            if (typeof Permission != 'undefined') {
                return Permission.indexOf(permissionName) !== -1;
            }
        },
    },
    mutations: {
        SET_TOKEN(state, payload) {
            state.token = payload;
        },
        SET_ERRORS(state, payload) {
            state.errors = payload;
        },
        CLEAR_ERRORS(state) {
            state.errors = [];
        }
    }
});

export default store;
