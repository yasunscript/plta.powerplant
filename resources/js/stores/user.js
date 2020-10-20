import $axios from "../api.js";

const state = () => ({
    authenticated: [] //MENAMPUNG USER YANG SEDANG LOGIN
});

const mutations = {
    ASSIGN_USER_AUTH(state, payload) {
        state.authenticated = payload;
    }
};

const actions = {
    getUserLogin({ commit }) {
        return new Promise((resolve, reject) => {
            $axios.get(`user-authenticated`).then(response => {
                //SIMPAN DATA USER TERSEBUT
                commit("ASSIGN_USER_AUTH", response.data.data);
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
