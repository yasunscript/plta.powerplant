import $realtime from "../realtime.js";
import $axios from "../api.js";

const state = () => ({
    realtime: {
        time: [],
        A0: [],
        A6: [],
        A12: [],
        A14: []
    },
    trends: [],
    maxItem: 10
});

const mutations = {
    ASSIGN_REALTIME(state, payload) {
        state.realtime = {
            time: payload.time,
            A0: payload.A0,
            A6: payload.A6,
            A12: payload.A12,
            A14: payload.A14
        };
    },
    CLEAR_REALTIME(state) {
        state.realtime = {
            time: [],
            A0: [],
            A6: [],
            A12: [],
            A14: []
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
                .get(`api?M=16&I=${payload.unit}&F=3&A=0&N=70&T=float`)
                .then(response => {
                    if (state.realtime.time.length > state.maxItem) {
                        state.realtime.time = state.realtime.time.slice(1);
                    }
                    if (state.realtime.A0.length > state.maxItem) {
                        state.realtime.A0 = state.realtime.A0.slice(1);
                    }
                    if (state.realtime.A6.length > state.maxItem) {
                        state.realtime.A6 = state.realtime.A6.slice(1);
                    }
                    if (state.realtime.A12.length > state.maxItem) {
                        state.realtime.A12 = state.realtime.A12.slice(1);
                    }
                    if (state.realtime.A14.length > state.maxItem) {
                        state.realtime.A14 = state.realtime.A14.slice(1);
                    }

                    let result = response.data;
                    let time = result.response[0].t.split("T")[1];
                    let A0 = parseFloat(result.response[0].v.A0).toFixed(2);
                    let A6 = parseFloat(result.response[0].v.A6).toFixed(2);
                    let A12 = parseFloat(result.response[0].v.A12).toFixed(2);
                    let A14 = parseFloat(result.response[0].v.A14).toFixed(2);

                    state.realtime.time.push(time);
                    state.realtime.A0.push(A0);
                    state.realtime.A6.push(A6);
                    state.realtime.A12.push(A12);
                    state.realtime.A14.push(A14);

                    commit("ASSIGN_REALTIME", state.realtime);
                    resolve(state.realtime);
                });
        });
    },

    getTrendData({ commit }, payload) {
        return new Promise((resolve, reject) => {
            $axios
                .get(
                    `/pembangkit-metering?unit=${payload.unit}&periode=${payload.periode}&month=${payload.month}&year=${payload.year}`
                )
                .then(response => {
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
