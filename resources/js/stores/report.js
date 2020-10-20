import $axios from "../api.js";

const state = () => ({
    produksi: []
});

const mutations = {
    ASSIGN_PRODUKSI(state, payload) {
        state.produksi = payload;
    },

    CLEAR_PRODUKSI(state) {
        state.produksi = [];
    }
};

const actions = {
    getProduksiData({ commit }, payload) {
        return new Promise((resolve, reject) => {
            $axios
                .get(
                    `/report-produksi?unit=${payload.unit}&periode=${payload.periode}&month=${payload.month}&year=${payload.year}`
                )
                .then(response => {
                    commit("ASSIGN_PRODUKSI", response.data);
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
