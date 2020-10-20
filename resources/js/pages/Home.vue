<template>
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">{{ $route.meta.title }}</h1>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-header border-0">
                                <h3 class="card-title">
                                    <i class="far fa-chart-bar"></i>
                                    TOTAL DAYA
                                </h3>
                            </div>
                            <div class="card-body p-0">
                                <div class="d-md-flex">
                                    <div class="p-1 flex-fill" style="overflow: hidden">
                                        <div style="height: 350px; overflow: hidden">
                                            <PembangkitTotalChart
                                                :chart-data="pembangkittotalData"
                                                :height="350"
                                                :options="chartOptions"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header border-0">
                                <h3 class="card-title">
                                    <i class="far fa-chart-bar"></i>
                                    PLTA DJUANDA
                                </h3>
                            </div>
                            <div class="card-body p-0">
                                <div class="d-md-flex">
                                    <div class="p-1 flex-fill" style="overflow: hidden">
                                        <div style="height: 350px; overflow: hidden">
                                            <PembangkitPltaChart
                                                :chart-data="pembangkitpltaData"
                                                :height="350"
                                                :options="chartOptions"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-header border-0">
                                <h3 class="card-title">
                                    <i class="far fa-chart-bar"></i>
                                    MINI HYDRO
                                </h3>
                            </div>
                            <div class="card-body p-0">
                                <div class="d-md-flex">
                                    <div class="p-1 flex-fill" style="overflow: hidden">
                                        <div style="height: 350px; overflow: hidden">
                                            <PembangkitMiniHydroChart
                                                :chart-data="pembangkitminihydroData"
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
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header border-0">
                                <h3 class="card-title">
                                    <i class="far fa-chart-bar"></i>
                                    TOTAL PENYALURAN
                                </h3>
                            </div>
                            <div class="card-body p-0">
                                <div class="d-md-flex">
                                    <div class="p-1 flex-fill" style="overflow: hidden">
                                        <div style="height: 350px; overflow: hidden">
                                            <PenyaluranTotalChart
                                                :chart-data="penyalurantotalData"
                                                :height="350"
                                                :options="doughnutOption"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header border-0">
                                <h3 class="card-title">
                                    <i class="far fa-chart-bar"></i>
                                    PENYALURAN
                                </h3>
                            </div>
                            <div class="card-body p-0">
                                <div class="d-md-flex">
                                    <div class="p-1 flex-fill" style="overflow: hidden">
                                        <div style="height: 350px; overflow: hidden">
                                            <PenyaluranPlnChart
                                                :chart-data="penyaluranplnData"
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

import PembangkitTotalChart from "../components/charts/BarChart.js";
import PembangkitPltaChart from "../components/charts/BarChart.js";
import PembangkitMiniHydroChart from "../components/charts/BarChart.js";

import PenyaluranTotalChart from "../components/charts/DoughnutChart.js";
import PenyaluranPlnChart from "../components/charts/BarChart.js";

import { mapActions, mapMutations, mapState } from "vuex";

export default {
    components: {
        PembangkitTotalChart,
        PembangkitPltaChart,
        PembangkitMiniHydroChart,
        PenyaluranTotalChart,
        PenyaluranPlnChart
    },
    created() {
        this.periode = 1;
        this.title = "Daily";
        this.getPembangkitTotal();
        this.getPembangkitPlta();
        this.getPembangkitMiniHydro();
        // PENYALURAN
        this.getPenyaluranTotal();
        this.getPenyaluranPln();
    },
    data() {
        return {
            periode: 1,
            day: moment().format("DD"),
            month: moment().format("MM"),
            year: moment().format("Y"),
            title: "",
            aktif: true,
            interval: 0,
            updateInterval: 10000,
            pembangkittotalChart: null,
            pembangkitpltaChart: null,
            pembangkitminihydroChart: null,
            penyalurantotalChart: null,
            penyaluranplnChart: null,
            chartOptions: {
                // hover: {
                //     animationDuration: 0
                // },
                animation: {
                    duration: 1,
                    onComplete: function() {
                        var chartInstance = this.chart,
                            ctx = chartInstance.ctx;

                        ctx.font = Chart.helpers.fontString(
                            Chart.defaults.global.defaultFontSize,
                            (Chart.defaults.global.defaultFontStyle = "bold"),
                            Chart.defaults.global.defaultFontFamily
                        );
                        ctx.textAlign = "center";
                        ctx.textBaseline = "bottom";

                        this.data.datasets.forEach(function(dataset, i) {
                            var meta = chartInstance.controller.getDatasetMeta(
                                i
                            );
                            meta.data.forEach(function(bar, index) {
                                var data = dataset.data[index];
                                ctx.fillText(
                                    numeral(data).format("0,0.00"),
                                    bar._model.x,
                                    bar._model.y - 5
                                );
                            });
                        });
                    }
                },
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
                            },
                            barPercentage: 0.4
                        }
                    ]
                },
                // tooltips: {
                //     callbacks: {
                //         label(tooltipItem, data) {
                //             // Get the dataset label.
                //             const label =
                //                 data.datasets[tooltipItem.datasetIndex].label;

                //             // Format the y-axis value.
                //             const value = numeral(tooltipItem.yLabel).format(
                //                 "0,0.00"
                //             );

                //             return `${label}: ${value}`;
                //         }
                //     }
                // },
                tooltips: false,
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
            },
            doughnutOption: {
                animation: {
                    animateScale: true,
                    animateRotate: true
                },
                hover: {
                    mode: "mode",
                    intersect: "intersect"
                },
                legend: {
                    display: true,
                    position: "bottom"
                },
                maintainAspectRatio: false,
                responsive: true,
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            var dataset =
                                data.datasets[tooltipItem.datasetIndex];
                            var total = dataset.data.reduce(function(
                                previousValue,
                                currentValue,
                                currentIndex,
                                array
                            ) {
                                return previousValue + currentValue;
                            });
                            var currentValue = dataset.data[tooltipItem.index];
                            var percentage = Math.floor(
                                (currentValue / total) * 100 + 0.5
                            );
                            return percentage + "%";
                        }
                    }
                },
                barPercentage: 0.4
            }
        };
    },
    watch: {
        aktif() {
            aktif: this.aktif;
            if (this.aktif == true) {
                this.interval = setInterval(() => {
                    this.getPembangkitTotal();
                    this.getPembangkitPlta();
                    this.getPembangkitMiniHydro();
                    // PENYALURAN
                    this.getPenyaluranTotal();
                    this.getPenyaluranPln();
                }, this.updateInterval);
            } else {
                clearInterval(this.interval);
            }
        }
    },
    mounted() {
        if (this.aktif == true) {
            this.interval = setInterval(() => {
                this.getPembangkitTotal();
                this.getPembangkitPlta();
                this.getPembangkitMiniHydro();
                // PENYALURAN
                this.getPenyaluranTotal();
                this.getPenyaluranPln();
            }, this.updateInterval);
        } else {
            clearInterval(this.interval);
        }
    },
    computed: {
        ...mapState("dashboard", {
            pembangkit_total: state => state.pembangkit_total,
            pembangkit_plta: state => state.pembangkit_plta,
            pembangkit_minihydro: state => state.pembangkit_minihydro,
            penyaluran_total: state => state.penyaluran_total,
            penyaluran_pln: state => state.penyaluran_pln
        }),

        pembangkittotalData() {
            let unit = _.map(this.pembangkit_total, function(o) {
                return o.unit;
            });
            let total = _.map(this.pembangkit_total, function(o) {
                return o.total;
            });
            let max = _.map(this.pembangkit_total, function(o) {
                return o.max;
            });
            return (this.pembangkittotalChart = {
                labels: unit,
                datasets: [
                    {
                        label: "Aktif",
                        backgroundColor: "blue",
                        data: total
                    },
                    {
                        label: "Max",
                        backgroundColor: "red",
                        data: max
                    }
                ]
            });
        },

        pembangkitpltaData() {
            let unit = _.map(this.pembangkit_plta, function(o) {
                return o.unit;
            });
            let total = _.map(this.pembangkit_plta, function(o) {
                return o.total;
            });
            return (this.pembangkitpltaChart = {
                labels: ["Daya"],
                datasets: [
                    {
                        label: [unit[0]],
                        backgroundColor: "blue",
                        data: [total[0]]
                    },
                    {
                        label: [unit[1]],
                        backgroundColor: "red",
                        data: [total[1]]
                    },
                    {
                        label: [unit[2]],
                        backgroundColor: "green",
                        data: [total[2]]
                    },
                    {
                        label: [unit[3]],
                        backgroundColor: "purple",
                        data: [total[3]]
                    },
                    {
                        label: [unit[4]],
                        backgroundColor: "orange",
                        data: [total[4]]
                    },
                    {
                        label: [unit[5]],
                        backgroundColor: "indigo",
                        data: [total[5]]
                    }
                ]
            });
        },

        pembangkitminihydroData() {
            let unit = _.map(this.pembangkit_minihydro, function(o) {
                return o.unit;
            });
            let total = _.map(this.pembangkit_minihydro, function(o) {
                return o.total;
            });
            return (this.pembangkitminihydroChart = {
                labels: ["Daya"],
                datasets: [
                    {
                        label: [unit[0]],
                        backgroundColor: "blue",
                        data: [total[0]]
                    },
                    {
                        label: [unit[1]],
                        backgroundColor: "red",
                        data: [total[1]]
                    }
                ]
            });
        },

        // PENYALURAN
        penyalurantotalData() {
            let unit = _.map(this.penyaluran_total, function(o) {
                return o.unit;
            });
            let total = _.map(this.penyaluran_total, function(o) {
                return o.total;
            });
            return (this.penyaluranplnChart = {
                labels: unit,
                datasets: [
                    {
                        backgroundColor: ["blue", "red"],
                        data: total
                    }
                ]
            });
        },

        penyaluranplnData() {
            let unit = _.map(this.penyaluran_pln, function(o) {
                return o.unit;
            });
            let total = _.map(this.penyaluran_pln, function(o) {
                return o.total;
            });
            return (this.penyaluranplnChart = {
                labels: ["kWh"],
                datasets: [
                    {
                        label: [unit[0]],
                        backgroundColor: "blue",
                        data: [total[0]]
                    },
                    {
                        label: [unit[1]],
                        backgroundColor: "red",
                        data: [total[1]]
                    },
                    {
                        label: [unit[2]],
                        backgroundColor: "green",
                        data: [total[2]]
                    },
                    {
                        label: [unit[3]],
                        backgroundColor: "purple",
                        data: [total[3]]
                    },
                    {
                        label: [unit[4]],
                        backgroundColor: "orange",
                        data: [total[4]]
                    },
                    {
                        label: [unit[5]],
                        backgroundColor: "indigo",
                        data: [total[5]]
                    }
                ]
            });
        }
    },
    methods: {
        ...mapActions("dashboard", [
            "getPembangkitTotal",
            "getPembangkitPlta",
            "getPembangkitMiniHydro",
            "getPenyaluranTotal",
            "getPenyaluranPln"
        ]),
        ...mapMutations("dashboard", [
            "CLEAR_PEMBANGKIT_TOTAL",
            "CLEAR_PEMBANGKIT_PLTA",
            "CLEAR_PEMBANGKIT_MINIHYDRO",
            "CLEAR_PENYALURAN_TOTAL",
            "CLEAR_PENYALURAN_PLTA"
        ])
    },
    destroyed() {
        this.CLEAR_PEMBANGKIT_TOTAL();
        this.CLEAR_PEMBANGKIT_PLTA();
        this.CLEAR_PEMBANGKIT_MINIHYDRO();
        this.CLEAR_PENYALURAN_TOTAL();
        this.CLEAR_PENYALURAN_PLTA();
        clearInterval(this.interval);
    }
};
</script>

<style lang="scss" scoped></style>
