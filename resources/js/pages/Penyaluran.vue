<template>
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">{{ $route.meta.title }}</h1>
                    </div>
                    <breadcrumb></breadcrumb>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <!-- Unit Penyaluran -->
                <div class="row">
                    <!-- PLTA Djuanda -->
                    <div class="col-md-12">
                        <div class="button-group mb-2">
                            <button
                                type="button"
                                :class="getActiveUnit(1)"
                                @click="unit = 1"
                            >Line Tata Jabar 1</button>
                            <button
                                type="button"
                                :class="getActiveUnit(2)"
                                @click="unit = 2"
                            >Line Tata Jabar 2</button>
                            <button
                                type="button"
                                :class="getActiveUnit(3)"
                                @click="unit = 3"
                            >Line Padalarang 1</button>
                            <button
                                type="button"
                                :class="getActiveUnit(4)"
                                @click="unit = 4"
                            >Line Padalarang 2</button>
                            <button
                                type="button"
                                :class="getActiveUnit(5)"
                                @click="unit = 5"
                            >Line Curug</button>
                            <button
                                type="button"
                                :class="getActiveUnit(6)"
                                @click="unit = 6"
                            >Line Ciganea</button>
                        </div>
                    </div>
                </div>
                <!-- Real Time Chart -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="far fa-chart-bar"></i>
                                    Real Time Data
                                </h3>
                                <div class="card-tools">
                                    <div class="btn-group">
                                        <button
                                            type="button"
                                            :class="getActiveRealTime(1)"
                                            @click="aktif = 1"
                                        >On</button>
                                        <button
                                            type="button"
                                            :class="getActiveRealTime(2)"
                                            @click="aktif = 2"
                                        >Off</button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="d-md-flex">
                                    <div class="p-1 flex-fill" style="overflow: hidden">
                                        <div style="height: 350px; overflow: hidden">
                                            <RealTimeChart
                                                :chart-data="realtimeData"
                                                :height="350"
                                                :options="chartOptions"
                                            />
                                        </div>
                                    </div>
                                    <div class="card-pane-right bg-success pt-2 pb-2 pl-4 pr-4">
                                        <div class="description-block mb-2">
                                            <div class="sparkbar pad" data-color="#fff">Time</div>
                                            <h5 class="description-header">{{ time }}</h5>
                                            <!-- <span class="description-text"></span> -->
                                        </div>
                                        <div class="description-block mb-2">
                                            <div class="sparkbar pad" data-color="#fff">Tegangan</div>
                                            <h5 class="description-header">{{ A10 }}</h5>
                                            <span class="description-text">kV</span>
                                        </div>
                                        <div class="description-block mb-2">
                                            <div class="sparkbar pad" data-color="#fff">Arus</div>
                                            <h5 class="description-header">{{ A4 }}</h5>
                                            <span class="description-text">kA</span>
                                        </div>
                                        <div class="description-block mb-2">
                                            <div class="sparkbar pad" data-color="#fff">Daya Aktif</div>
                                            <h5 class="description-header">{{ A16 }}</h5>
                                            <span class="description-text">MW</span>
                                        </div>
                                        <div class="description-block mb-2">
                                            <div class="sparkbar pad" data-color="#fff">Daya Reaktif</div>
                                            <h5 class="description-header">{{ A18 }}</h5>
                                            <span class="description-text">MVAR</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Periode Seletion -->
                <div class="row">
                    <!-- Periode -->
                    <div class="col-md-5">
                        <div class="button-group">
                            <button
                                type="button"
                                :class="getActivePeriode(1)"
                                @click="periode = 1"
                            >Harian</button>
                            <button
                                type="button"
                                :class="getActivePeriode(2)"
                                @click="periode = 2"
                            >Bulanan</button>
                            <button
                                type="button"
                                :class="getActivePeriode(3)"
                                @click="periode = 3"
                            >Tahunan</button>
                        </div>
                    </div>
                    <!-- Month -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <select v-if="periode == 1" v-model="month" class="form-control">
                                <option value="01">Januari</option>
                                <option value="02">Februari</option>
                                <option value="03">Maret</option>
                                <option value="04">April</option>
                                <option value="05">Mei</option>
                                <option value="06">Juni</option>
                                <option value="07">Juli</option>
                                <option value="08">Agustus</option>
                                <option value="09">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                            </select>
                        </div>
                    </div>
                    <!-- Year -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <select v-model="year" class="form-control btn-sm">
                                <option v-for="(y, i) in years" :key="i" :value="y">{{ y }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- Trend Chart -->
                <div class="row">
                    <!-- Power Trend Chart -->
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between">
                                    <h3 class="card-title">Power Trend</h3>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="d-md-flex">
                                    <div class="p-1 flex-fill" style="overflow: hidden">
                                        <div style="height: 350px; overflow: hidden">
                                            <PowerChart
                                                :chart-data="powertrendData"
                                                :height="350"
                                                :options="chartOptions"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Energy Trend Chart -->
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between">
                                    <h3 class="card-title">Energy Trend</h3>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="d-md-flex">
                                    <div class="p-1 flex-fill" style="overflow: hidden">
                                        <div style="height: 350px; overflow: hidden">
                                            <EnergyChart
                                                :chart-data="energytrendData"
                                                :height="350"
                                                :options="chartOptions"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
import moment from "moment";
import numeral from "numeral";
import _ from "lodash";

import Breadcrumb from "../components/layouts/Breadcrumb.vue";
import RealTimeChart from "../components/charts/LineChart.js";
import PowerChart from "../components/charts/LineChart.js";
import EnergyChart from "../components/charts/LineChart.js";

import { mapActions, mapMutations, mapState } from "vuex";

export default {
    components: {
        Breadcrumb,
        RealTimeChart,
        PowerChart,
        EnergyChart
    },
    created() {
        this.unit = 1;
        this.periode = 1;
        this.getTrendData({
            unit: this.unit,
            periode: this.periode,
            month: this.month,
            year: this.year
        });
    },
    data() {
        return {
            unit: 1,
            periode: 1,
            month: moment().format("MM"),
            year: moment().format("Y"),
            // Real Time Now
            time: "",
            A4: 0,
            A10: 0,
            A16: 0,
            A18: 0,
            // Real Time Chart
            aktif: 1,
            interval: 0,
            updateInterval: 1000,
            // Chart Data
            realtimeChart: null,
            powertrendChart: null,
            energytrendChart: null,
            // Chart Options
            chartOptions: {
                scales: {
                    yAxes: [
                        {
                            gridLines: {
                                display: true,
                                lineWidth: "4px",
                                color: "rgba(0, 0, 0, .2)",
                                zeroLineColor: "transparent"
                            },
                            ticks: $.extend(
                                {
                                    beginAtZero: true,
                                    callback: value =>
                                        numeral(value).format("0,0")
                                },
                                {
                                    fontColor: "#495057",
                                    fontStyle: "bold"
                                }
                            )
                        }
                    ],
                    xAxes: [
                        {
                            display: true,
                            gridLines: {
                                display: false
                            },
                            ticks: {
                                fontColor: "#495057",
                                fontStyle: "bold"
                            }
                        }
                    ]
                },
                tooltips: {
                    callbacks: {
                        label(tooltipItem, data) {
                            // Get the dataset label.
                            const label =
                                data.datasets[tooltipItem.datasetIndex].label;

                            // Format the y-axis value.
                            const value = numeral(tooltipItem.yLabel).format(
                                "0,0.00"
                            );

                            return `${label}: ${value}`;
                        }
                    }
                },
                hover: {
                    mode: "mode",
                    intersect: "intersect"
                },
                legend: {
                    display: true,
                    position: "bottom"
                },
                responsive: true,
                maintainAspectRatio: false
            }
        };
    },
    watch: {
        aktif() {
            aktif: this.aktif;
            if (this.aktif == 1) {
                this.interval = setInterval(() => {
                    this.getRealTime({
                        unit: this.unit
                    });
                }, this.updateInterval);
            } else {
                clearInterval(this.interval);
            }
        },
        unit() {
            this.CLEAR_REALTIME();
            this.aktif = 1;
            this.getTrendData({
                unit: this.unit,
                periode: this.periode,
                month: this.month,
                year: this.year
            });
        },
        periode() {
            this.getTrendData({
                unit: this.unit,
                periode: this.periode,
                month: this.month,
                year: this.year
            });
        },
        month() {
            this.getTrendData({
                unit: this.unit,
                peride: this.periode,
                month: this.month,
                year: this.year
            });
        },

        year() {
            this.getTrendData({
                unit: this.unit,
                periode: this.periode,
                month: this.month,
                year: this.year
            });
        }
    },
    computed: {
        ...mapState("penyaluran", {
            realtime: state => state.realtime,
            trends: state => state.trends
        }),
        years() {
            return _.range(
                2017,
                moment()
                    .add(1, "years")
                    .format("Y")
            );
        },

        realtimeData() {
            this.time = this.realtime.time[this.realtime.time.length - 1];
            this.A4 = this.realtime.A4[this.realtime.A4.length - 1];
            this.A10 = this.realtime.A10[this.realtime.A10.length - 1];
            this.A16 = this.realtime.A16[this.realtime.A16.length - 1];
            this.A18 = this.realtime.A18[this.realtime.A18.length - 1];
            return (this.realtimeChart = {
                labels: this.realtime.time,
                datasets: [
                    {
                        label: "Tegangan (kV)",
                        backgroundColor: "transparent",
                        borderColor: "red",
                        pointBorderColor: "red",
                        pointBackgroundColor: "red",
                        // borderWidth: 1,
                        fill: false,
                        data: this.realtime.A10
                    },
                    {
                        label: "Arus (kA)",
                        backgroundColor: "transparent",
                        borderColor: "blue",
                        pointBorderColor: "blue",
                        pointBackgroundColor: "blue",
                        // borderWidth: 1,
                        fill: false,
                        data: this.realtime.A4
                    },
                    {
                        label: "MW",
                        backgroundColor: "transparent",
                        borderColor: "green",
                        pointBorderColor: "green",
                        pointBackgroundColor: "green",
                        // borderWidth: 1,
                        fill: false,
                        data: this.realtime.A16
                    },
                    {
                        label: "MVAR",
                        backgroundColor: "transparent",
                        borderColor: "purple",
                        pointBorderColor: "purple",
                        pointBackgroundColor: "purple",
                        // borderWidth: 1,
                        fill: false,
                        data: this.realtime.A18
                    }
                ]
            });
        },

        powertrendData() {
            let time = _.map(this.trends, function(o) {
                return o.date;
            });
            let data_0 = _.map(this.trends, function(o) {
                return parseFloat(o.data_0).toFixed(2);
            });
            let data_3 = _.map(this.trends, function(o) {
                return parseFloat(o.data_3).toFixed(2);
            });
            let data_8 = _.map(this.trends, function(o) {
                return parseFloat(o.data_8).toFixed(2);
            });
            let data_7 = _.map(this.trends, function(o) {
                return parseFloat(o.data_7).toFixed(2);
            });
            return (this.powertrendChart = {
                labels: time,
                datasets: [
                    {
                        label: "Voltage (kV)",
                        backgroundColor: "transparent",
                        borderColor: "red",
                        pointBackgroundColor: "red",
                        // borderWidth: 1,
                        pointBorderColor: "red",
                        fill: false,
                        data: data_0
                    },
                    {
                        label: "Current (kA)",
                        backgroundColor: "transparent",
                        borderColor: "blue",
                        pointBorderColor: "blue",
                        pointBackgroundColor: "blue",
                        // borderWidth: 1,
                        fill: false,
                        data: data_3
                    },
                    {
                        label: "Power (MW)",
                        backgroundColor: "transparent",
                        borderColor: "green",
                        pointBorderColor: "green",
                        pointBackgroundColor: "green",
                        // borderWidth: 1,
                        fill: false,
                        data: data_8
                    },
                    {
                        label: "MVar",
                        backgroundColor: "transparent",
                        borderColor: "purple",
                        pointBorderColor: "purple",
                        pointBackgroundColor: "purple",
                        // borderWidth: 1,
                        fill: false,
                        data: data_7
                    }
                ]
            });
        },

        energytrendData() {
            let time = _.map(this.trends, function(o) {
                return o.date;
            });
            let data_6 = _.map(this.trends, function(o) {
                return parseFloat(o.data_6);
            });
            let data_10 = _.map(this.trends, function(o) {
                return parseFloat(o.data_10);
            });

            return (this.energytrendChart = {
                labels: time,
                datasets: [
                    {
                        label: "kWh Del.",
                        backgroundColor: "transparent",
                        borderColor: "red",
                        pointBackgroundColor: "red",
                        // borderWidth: 1,
                        pointBorderColor: "red",
                        fill: false,
                        data: data_10
                    }
                    // {
                    //     label: "MVar Del.",
                    //     backgroundColor: "transparent",
                    //     borderColor: "blue",
                    //     pointBorderColor: "blue",
                    //     pointBackgroundColor: "blue",
                    //     // borderWidth: 1,
                    //     fill: false,
                    //     data: data_6
                    // }
                ]
            });
        }
    },
    mounted() {
        if (this.aktif == 1) {
            this.interval = setInterval(() => {
                this.getRealTime({
                    unit: this.unit
                });
            }, this.updateInterval);
        } else {
            clearInterval(this.interval);
        }
    },
    methods: {
        ...mapActions("penyaluran", ["getRealTime", "getTrendData"]),
        ...mapMutations("penyaluran", ["CLEAR_REALTIME"]),

        getActiveRealTime(id) {
            if (id === this.aktif) {
                return "btn btn-primary btn-sm active";
            } else {
                return "btn btn-default btn-sm ";
            }
        },

        getActiveUnit(id) {
            if (id === this.unit) {
                return "btn btn-primary active";
            } else {
                return "btn btn-primary";
            }
        },

        getActivePeriode(id) {
            if (id === this.periode) {
                return "btn btn-primary active";
            } else {
                return "btn btn-primary";
            }
        }
    },
    destroyed() {
        this.CLEAR_REALTIME();
        clearInterval(this.interval);
    }
};
</script>

<style lang="scss" scoped></style>
