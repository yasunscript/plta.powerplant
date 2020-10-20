import $axios from "../api.js";

const state = () => ({
    pembangkit_total: [],
    pembangkit_plta: [],
    pembangkit_minihydro: [],
    penyaluran_total: [],
    penyaluran_pln: []
});

const mutations = {
    ASSIGN_PEMBANGKIT_TOTAL(state, payload) {
        state.pembangkit_total = payload;
    },
    CLEAR_PEMBANGKIT_TOTAL(state) {
        state.pembangkit_total = [];
    },
    ASSIGN_PEMBANGKIT_PLTA(state, payload) {
        state.pembangkit_plta = payload;
    },
    CLEAR_PEMBANGKIT_PLTA(state) {
        state.pembangkit_plta = [];
    },
    ASSIGN_PEMBANGKIT_MINIHYDRO(state, payload) {
        state.pembangkit_minihydro = payload;
    },
    CLEAR_PEMBANGKIT_MINIHYDRO(state) {
        state.pembangkit_minihydro = [];
    },
    ASSIGN_PENYALURAN_TOTAL(state, payload) {
        state.penyaluran_total = payload;
    },
    CLEAR_PENYALURAN_TOTAL(state) {
        state.penyaluran_total = [];
    },
    ASSIGN_PENYALURAN_PLN(state, payload) {
        state.penyaluran_pln = payload;
    },
    CLEAR_PENYALURAN_PLTA(state) {
        state.penyaluran_pln = [];
    }
};

const actions = {
    getPembangkitTotal({ commit }, payload) {
        return new Promise((resolve, reject) => {
            $axios.get(`/dashboard-pembangkittotal`).then(response => {
                commit("ASSIGN_PEMBANGKIT_TOTAL", response.data);
                // console.log(response.data);
                resolve(response.data);
            });
        });
    },

    getPembangkitPlta({ commit }, payload) {
        return new Promise((resolve, reject) => {
            $axios.get(`/dashboard-pembangkitplta`).then(response => {
                commit("ASSIGN_PEMBANGKIT_PLTA", response.data);
                resolve(response.data);
            });
        });
    },

    getPembangkitMiniHydro({ commit }, payload) {
        return new Promise((resolve, reject) => {
            $axios.get(`/dashboard-pembangkitminihydro`).then(response => {
                commit("ASSIGN_PEMBANGKIT_MINIHYDRO", response.data);
                resolve(response.data);
            });
        });
    },

    // PENYALURAN
    getPenyaluranTotal({ commit }, payload) {
        return new Promise((resolve, reject) => {
            $axios.get(`/dashboard-penyalurantotal`).then(response => {
                commit("ASSIGN_PENYALURAN_TOTAL", response.data);
                resolve(response.data);
            });
        });
    },

    getPenyaluranPln({ commit }, payload) {
        return new Promise((resolve, reject) => {
            $axios.get(`/dashboard-penyaluranpln`).then(response => {
                commit("ASSIGN_PENYALURAN_PLN", response.data);
                resolve(response.data);
            });
        });
    }
};

export default {
    namespaced: true,
    state,
    actions,
    mutations
};
