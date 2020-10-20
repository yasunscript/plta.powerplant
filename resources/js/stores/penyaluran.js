import $realtime from "../realtime.js";
import $axios from "../api.js";

const state = () => ({
    realtime: {
        time: [],
        A4: [], //Tegangan
        A10: [], //Arus
        A16: [], //MW
        A18: [] //MVAR
    },
    trends: [],
    maxItem: 10
});

const mutations = {
    ASSIGN_REALTIME(state, payload) {
        state.realtime = {
            time: payload.time,
            A4: payload.A4,
            A10: payload.A10,
            A16: payload.A16,
            A18: payload.A18
        };
    },
    CLEAR_REALTIME(state) {
        state.realtime = {
            time: [],
            A4: [],
            A10: [],
            A16: [],
            A18: []
        };
    },
    ASSIGN_DATA_TREND(state, payload) {
        state.trends = payload;
    }
};

const actions = {
    getRealTime({ commit, state }, payload) {
        return new Promise((resolve, reject) => {
            $realtime
                .get(`api?M=16&I=${payload.unit}&F=3&A=0&N=600&T=float`)
                .then(response => {
                    if (state.realtime.time.length > state.maxItem) {
                        state.realtime.time = state.realtime.time.slice(1);
                    }
                    if (state.realtime.A4.length > state.maxItem) {
                        state.realtime.A4 = state.realtime.A4.slice(1);
                    }
                    if (state.realtime.A10.length > state.maxItem) {
                        state.realtime.A10 = state.realtime.A10.slice(1);
                    }
                    if (state.realtime.A16.length > state.maxItem) {
                        state.realtime.A16 = state.realtime.A16.slice(1);
                    }
                    if (state.realtime.A18.length > state.maxItem) {
                        state.realtime.A18 = state.realtime.A18.slice(1);
                    }

                    let result = response.data;
                    let time = result.response[0].t.split("T")[1];
                    let A4 = parseFloat(result.response[0].v.A4 / 1000).toFixed(
                        2
                    );
                    let A10 = parseFloat(result.response[0].v.A10).toFixed(2);
                    let A16 = parseFloat(
                        result.response[0].v.A16 / 1000
                    ).toFixed(2);
                    let A18 = parseFloat(
                        result.response[0].v.A18 / 1000
                    ).toFixed(2);

                    state.realtime.time.push(time);
                    state.realtime.A4.push(A4);
                    state.realtime.A10.push(A10);
                    state.realtime.A16.push(A16);
                    state.realtime.A18.push(A18);

                    commit("ASSIGN_REALTIME", state.realtime);
                    resolve(state.realtime);
                });
        });
    },

    //ACTION UNTUK MENG-HANDLE REQUEST KE SERVER
    getTrendData({ commit }, payload) {
        return new Promise((resolve, reject) => {
            //MINTA DATA CHART POWER KE SERVER BERDASARKAN BULAN DAN TAHUN
            $axios
                .get(
                    `/penyaluran-metering?unit=${payload.unit}&periode=${payload.periode}&month=${payload.month}&year=${payload.year}`
                )
                .then(response => {
                    //KEMUDIAN KIRIM DATA NYA KE MUTATION UNTUK KEMUDIAN DISIMPAN KE STATE
                    commit("ASSIGN_DATA_TREND", response.data);
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
