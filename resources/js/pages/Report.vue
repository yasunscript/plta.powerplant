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
                <!-- Unit Pembangkit -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="button-group-horizontal mb-2">
                            <button
                                type="button"
                                :class="unitActive(1)"
                                @click="unit = 1"
                            >PLTA Djuanda</button>
                            <button
                                type="button"
                                :class="unitActive(2)"
                                @click="unit = 2"
                            >Mini Hydro</button>
                            <button type="button" :class="unitActive(3)" @click="unit = 3">PLN</button>
                            <button type="button" :class="unitActive(4)" @click="unit = 4">Non PLN</button>
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
                                :class="periodeActive(1)"
                                @click="periode=1"
                            >Harian</button>
                            <button
                                type="button"
                                :class="periodeActive(2)"
                                @click="periode=2"
                            >Bulanan</button>
                            <button
                                type="button"
                                :class="periodeActive(3)"
                                @click="periode=3"
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
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header border-0">
                                <h3 class="card-title">
                                    <i class="far fa-chart-bar"></i>
                                    TOTAL PRODUKSI
                                </h3>
                                <div class="card-tools">
                                    <ul class="nav nav-pills ml-auto">
                                        <li class="nav-item">
                                            <a
                                                class="nav-link active"
                                                href="javascript:;"
                                                data-toggle="tab"
                                                @click="isChart = true"
                                            >Chart</a>
                                        </li>
                                        <li class="nav-item">
                                            <a
                                                class="nav-link"
                                                href="javascript:;"
                                                data-toggle="tab"
                                                @click="isChart = false"
                                            >Tabular</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="d-md-flex">
                                    <div class="p-1 flex-fill" style="overflow: hidden">
                                        <div style="height: 350px; overflow: hidden">
                                            <PltaChart
                                                v-if="unit == 1 && isChart"
                                                :chart-data="pltaData"
                                                :height="350"
                                                :options="chartOptions"
                                            />
                                            <MinihydroChart
                                                v-if="unit == 2 && isChart"
                                                :chart-data="minihydroData"
                                                :height="350"
                                                :options="chartOptions"
                                            />
                                            <PlnChart
                                                v-if="unit == 3 && isChart"
                                                :chart-data="pltaData"
                                                :height="350"
                                                :options="chartOptions"
                                            />
                                            <NonplnChart
                                                v-if="unit == 4 && isChart"
                                                :chart-data="nonplnData"
                                                :height="350"
                                                :options="chartOptions"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header border-0">
                                <h3 class="card-title">
                                    <i class="far fa-chart-bar"></i>
                                    REKAPITULASI PRODUKSI
                                </h3>
                            </div>
                            <div class="card-body p-0">
                                <div class="d-md-flex">
                                    <div class="p-1 flex-fill" style="overflow: hidden">
                                        <div style="height: 350px; overflow: hidden">
                                            <div class="row"></div>
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
import PltaChart from "../components/charts/BarChart.js";
import MinihydroChart from "../components/charts/BarChart.js";
import PlnChart from "../components/charts/BarChart";
import NonplnChart from "../components/charts/BarChart";

import { mapActions, mapMutations, mapState } from "vuex";

export default {
    components: {
        Breadcrumb,
        PltaChart,
        MinihydroChart,
        PlnChart,
        NonplnChart
    },

    created() {
        this.unit = 1;
        this.periode = 1;
        this.getProduksiData({
            unit: this.unit,
            periode: this.periode,
            month: this.month,
            year: this.year
        });
    },

    data() {
        return {
            isChart: true,
            unit: 1,
            periode: 1,
            month: moment().format("MM"),
            year: moment().format("Y"),
            pltaChart: null,
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
                            // barPercentage: 0.4
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
        unit() {
            this.getProduksiData({
                unit: this.unit,
                periode: this.periode,
                month: this.month,
                year: this.year
            });
        },

        periode() {
            this.getProduksiData({
                unit: this.unit,
                periode: this.periode,
                month: this.month,
                year: this.year
            });
        },

        month() {
            this.getProduksiData({
                unit: this.unit,
                periode: this.periode,
                month: this.month,
                year: this.year
            });
        },

        year() {
            this.getProduksiData({
                unit: this.unit,
                periode: this.periode,
                month: this.month,
                year: this.year
            });
        }
    },

    computed: {
        ...mapState("report", {
            produksi: state => state.produksi
        }),

        years() {
            return _.range(
                2017,
                moment()
                    .add(1, "years")
                    .format("Y")
            );
        },

        pltaData() {
            let time = _.map(this.produksi, function(o) {
                return o.date;
            });
            let total = _.map(this.produksi, function(o) {
                return o.total;
            });

            return (this.pltaChart = {
                labels: time,
                datasets: [
                    {
                        label: "PLTA Djuanda",
                        backgroundColor: "blue",
                        data: total
                    }
                ]
            });
        },

        minihydroData() {
            let time = _.map(this.produksi, function(o) {
                return o.date;
            });
            let total = _.map(this.produksi, function(o) {
                return o.total;
            });

            return (this.minihydroChart = {
                labels: time,
                datasets: [
                    {
                        label: "Mini Hydro",
                        backgroundColor: "blue",
                        data: total
                    }
                ]
            });
        },

        plnData() {
            let time = _.map(this.produksi, function(o) {
                return o.date;
            });
            let total = _.map(this.produksi, function(o) {
                return o.total;
            });

            return (this.PlnChart = {
                labels: time,
                datasets: [
                    {
                        label: "PLN",
                        backgroundColor: "blue",
                        data: total
                    }
                ]
            });
        },

        nonplnData() {
            let time = _.map(this.produksi, function(o) {
                return o.date;
            });
            let total = _.map(this.produksi, function(o) {
                return o.total;
            });

            return (this.PlnChart = {
                labels: time,
                datasets: [
                    {
                        label: "Non PLN",
                        backgroundColor: "blue",
                        data: total
                    }
                ]
            });
        }
    },

    methods: {
        ...mapActions("report", ["getProduksiData"]),

        unitActive(id) {
            let btn;
            let btn1 = "btn btn-primary ";
            if (id === this.unit) {
                btn = btn1 + "active";
            } else {
                btn = btn1;
            }
            return btn;
        },

        periodeActive(id) {
            if (id === this.periode) {
                return "btn btn-success active";
            } else {
                return "btn btn-success";
            }
        }
    }
};
</script>

<style lang="scss" scoped></style>
