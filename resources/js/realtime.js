import axios from "axios";

const $realtime = axios.create({
    baseURL: "http://202.162.210.219:8082",
    headers: {
        "Content-Type": "application/json"
    },
    crossdomain: true
});

export default $realtime;
