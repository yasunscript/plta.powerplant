<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function getProduksiData()
    {
        $unit = request()->unit;
        $periode = request()->periode;
        $month = request()->month;
        $year = request()->year;

        $data = [];

        switch ($unit) {
            case '1': //Ptla Djuanda
                $array_unit = ['Metering1', 'Metering2', 'Metering3', 'Metering4', 'Metering5', 'Metering6'];
                if ($periode == 1) {
                    $filter = $year . '-' . $month;
                    $parse = Carbon::parse($filter);
                    $array_date = range($parse->startOfMonth()->format('d'), $parse->endOfMonth()->format('d'));
                    $dataMetering = [];
                    foreach ($array_unit as $key => $value) {
                        $model_name = 'App\Pembangkit\Plta\\' . $value;
                        $metering = $model_name::select(
                            DB::raw('date(from_unixtime(`time@timestamp`)) as date'),
                            DB::raw('(MAX(data_format_6) - MIN(data_format_6))*1000 as diff')
                        )
                            ->where(DB::raw('from_unixtime(`time@timestamp`)'), 'LIKE', '%' . $filter . '%')
                            ->groupBy(DB::raw('date(from_unixtime(`time@timestamp`))'))
                            ->get();

                        $diff = [];
                        foreach ($array_date as $row) {
                            $f_date = strlen($row) == 1 ? 0 . $row : $row;
                            $date = $filter . '-' . $f_date;

                            $total = $metering->firstWhere('date', $date);
                            $diff[] = $total ? $total->diff : 0;
                        }
                        $dataMetering[] = [
                            'unit' => $value,
                            'total' => $diff,
                        ];
                    }
                    foreach ($array_date as $row) {
                        $f_date = strlen($row) == 1 ? 0 . $row : $row;
                        $date = $filter . '-' . $f_date;
                        $data[] = [
                            'date' => $f_date,
                            'unit1' => $dataMetering[0]['total'][$row - 1],
                            'unit2' => $dataMetering[1]['total'][$row - 1],
                            'unit3' => $dataMetering[2]['total'][$row - 1],
                            'unit4' => $dataMetering[3]['total'][$row - 1],
                            'unit5' => $dataMetering[4]['total'][$row - 1],
                            'unit6' => $dataMetering[5]['total'][$row - 1],
                            'total' => $dataMetering[0]['total'][$row - 1] +
                                $dataMetering[1]['total'][$row - 1] + $dataMetering[2]['total'][$row - 1] +
                                $dataMetering[3]['total'][$row - 1] + $dataMetering[4]['total'][$row - 1] +
                                $dataMetering[5]['total'][$row - 1]
                        ];
                    }
                } elseif ($periode == 2) {
                    $bulan = 'JAN FEB MAR APR MEI JUN JUL AUG SEP OKT NOV DES';
                    $array_bulan = explode(' ', $bulan);
                    $array_year = range(1, 12);
                    $dataMetering = [];
                    foreach ($array_unit as $key => $value) {
                        $model_name = 'App\Pembangkit\Plta\\' . $value;
                        $metering = $model_name::select(
                            DB::raw('CONCAT(YEAR(from_unixtime(`time@timestamp`)),"-",MONTH(from_unixtime(`time@timestamp`))) as date'),
                            DB::raw('(MAX(data_format_6) - MIN(data_format_6))*1000 as diff')
                        )
                            ->where(DB::raw('YEAR(from_unixtime(`time@timestamp`))'), 'LIKE', '%' . $year . '%')
                            ->groupBy(DB::raw('CONCAT(YEAR(from_unixtime(`time@timestamp`)),"-",MONTH(from_unixtime(`time@timestamp`)))'))
                            ->get();

                        $diff = [];
                        foreach ($array_year as $row) {
                            $year_month = $year . '-' . $row;

                            $total = $metering->firstWhere('date', $year_month);
                            $diff[] = $total ? $total->diff : 0;
                        }
                        $dataMetering[] = [
                            'unit' => $value,
                            'total' => $diff,
                        ];
                    }
                    foreach ($array_year as $row) {
                        $data[] = [
                            'date' => $array_bulan[$row - 1],
                            'unit1' => $dataMetering[0]['total'][$row - 1],
                            'unit2' => $dataMetering[1]['total'][$row - 1],
                            'unit3' => $dataMetering[2]['total'][$row - 1],
                            'unit4' => $dataMetering[3]['total'][$row - 1],
                            'unit5' => $dataMetering[4]['total'][$row - 1],
                            'unit6' => $dataMetering[5]['total'][$row - 1],
                            'total' => $dataMetering[0]['total'][$row - 1] +
                                $dataMetering[1]['total'][$row - 1] + $dataMetering[2]['total'][$row - 1] +
                                $dataMetering[3]['total'][$row - 1] + $dataMetering[4]['total'][$row - 1] +
                                $dataMetering[5]['total'][$row - 1]
                        ];
                    }
                } else {
                    $array_year = range($year - 9 < 2017 ? 2017 : $year - 9, $year);
                    $dataMetering = [];
                    foreach ($array_unit as $key => $value) {
                        $model_name = 'App\Pembangkit\Plta\\' . $value;
                        $metering = $model_name::select(
                            DB::raw('year(from_unixtime(`time@timestamp`)) as year'),
                            DB::raw('(MAX(data_format_6) - MIN(data_format_6))*1000 as diff')
                        )
                            ->groupBy(DB::raw('year(from_unixtime(`time@timestamp`))'))
                            ->get();

                        $diff = [];
                        foreach ($array_year as $row) {
                            $total = $metering->firstWhere('year', $row);

                            $diff[$row] = $total ? $total->diff : 0;
                        }
                        $dataMetering[] = [
                            'unit' => $value,
                            'total' => $diff
                        ];
                    }
                    foreach ($array_year as $row) {
                        $data[] = [
                            'date' => $row,
                            'unit1' => $dataMetering[0]['total'][$row],
                            'unit2' => $dataMetering[1]['total'][$row],
                            'unit3' => $dataMetering[2]['total'][$row],
                            'unit4' => $dataMetering[3]['total'][$row],
                            'unit5' => $dataMetering[4]['total'][$row],
                            'unit6' => $dataMetering[5]['total'][$row],
                            'total' => $dataMetering[0]['total'][$row] +
                                $dataMetering[1]['total'][$row] + $dataMetering[2]['total'][$row] +
                                $dataMetering[3]['total'][$row] + $dataMetering[4]['total'][$row] +
                                $dataMetering[5]['total'][$row]
                        ];
                    }
                }
                break;

            case '2': //Minihydro
                $array_unit = ['Metering1', 'Metering2'];
                if ($periode == 1) {
                    $filter = $year . '-' . $month;
                    $parse = Carbon::parse($filter);
                    $array_date = range($parse->startOfMonth()->format('d'), $parse->endOfMonth()->format('d'));
                    $dataMetering = [];
                    foreach ($array_unit as $key => $value) {
                        $model_name = 'App\Pembangkit\Minihydro\\' . $value;
                        $metering = $model_name::select(
                            DB::raw('date(from_unixtime(`time@timestamp`)) as date'),
                            DB::raw('(MAX(data_format_6) - MIN(data_format_6)) * 1000 as diff')
                        )
                            ->where(DB::raw('from_unixtime(`time@timestamp`)'), 'LIKE', '%' . $filter . '%')
                            ->groupBy(DB::raw('date(from_unixtime(`time@timestamp`))'))
                            ->get();

                        $diff = [];
                        foreach ($array_date as $row) {
                            $f_date = strlen($row) == 1 ? 0 . $row : $row;
                            $date = $filter . '-' . $f_date;

                            $total = $metering->firstWhere('date', $date);
                            $diff[] = $total ? $total->diff : 0;
                        }
                        $dataMetering[] = [
                            'unit' => $value,
                            'total' => $diff
                        ];
                    }
                    foreach ($array_date as $row) {
                        $f_date = strlen($row) == 1 ? 0 . $row : $row;
                        $date = $filter . '-' . $f_date;
                        $data[] = [
                            'date' => $f_date,
                            'unit1' => $dataMetering[0]['total'][$row - 1],
                            'unit2' => $dataMetering[1]['total'][$row - 1],
                            'total' => $dataMetering[0]['total'][$row - 1] + $dataMetering[1]['total'][$row - 1]
                        ];
                    }
                } elseif ($periode == 2) {
                    $bulan = 'JAN FEB MAR APR MEI JUN JUL AUG SEP OKT NOV DES';
                    $array_bulan = explode(' ', $bulan);
                    $array_year = range(1, 12);
                    $dataMetering = [];
                    foreach ($array_unit as $key => $value) {
                        $model_name = 'App\Pembangkit\Minihydro\\' . $value;
                        $metering = $model_name::select(
                            DB::raw('CONCAT(YEAR(from_unixtime(`time@timestamp`)),"-",MONTH(from_unixtime(`time@timestamp`))) as date'),
                            DB::raw('(MAX(data_format_6) - MIN(data_format_6))*1000 as diff')
                        )
                            ->where(DB::raw('YEAR(from_unixtime(`time@timestamp`))'), 'LIKE', '%' . $year . '%')
                            ->groupBy(DB::raw('CONCAT(YEAR(from_unixtime(`time@timestamp`)),"-",MONTH(from_unixtime(`time@timestamp`)))'))
                            ->get();

                        $diff = [];
                        foreach ($array_year as $row) {
                            $year_month = $year . '-' . $row;

                            $total = $metering->firstWhere('date', $year_month);
                            $diff[] = $total ? $total->diff : 0;
                        }
                        $dataMetering[] = [
                            'unit' => $value,
                            'total' => $diff
                        ];
                    }
                    foreach ($array_year as $row) {
                        $data[] = [
                            'date' => $array_bulan[$row - 1],
                            'unit1' => $dataMetering[0]['total'][$row - 1],
                            'unit2' => $dataMetering[1]['total'][$row - 1],
                            'total' => $dataMetering[0]['total'][$row - 1] + $dataMetering[1]['total'][$row - 1]
                        ];
                    }
                } else {
                    $array_year = range($year - 9 < 2017 ? 2017 : $year - 9, $year);
                    $dataMetering = [];
                    foreach ($array_unit as $key => $value) {
                        $model_name = 'App\Pembangkit\Minihydro\\' . $value;
                        $metering = $model_name::select(
                            DB::raw('year(from_unixtime(`time@timestamp`)) as year'),
                            DB::raw('(MAX(data_format_6) - MIN(data_format_6))*1000 as diff')
                        )
                            ->groupBy(DB::raw('year(from_unixtime(`time@timestamp`))'))
                            ->get();

                        $diff = [];
                        foreach ($array_year as $row) {
                            $total = $metering->firstWhere('year', $row);

                            $diff[$row] = $total ? $total->diff : 0;
                        }
                        $dataMetering[] = [
                            'unit' => $value,
                            'total' => $diff
                        ];
                    }
                    foreach ($array_year as $row) {
                        $data[] = [
                            'date' => $row,
                            'unit1' => $dataMetering[0]['total'][$row],
                            'unit2' => $dataMetering[1]['total'][$row],
                            'total' => $dataMetering[0]['total'][$row] + $dataMetering[1]['total'][$row]
                        ];
                    }
                }
                break;

            case '3': //Minihydro
                $array_unit = ['Metering1', 'Metering2', 'Metering3', 'Metering4'];
                if ($periode == 1) {
                    $filter = $year . '-' . $month;
                    $parse = Carbon::parse($filter);
                    $array_date = range($parse->startOfMonth()->format('d'), $parse->endOfMonth()->format('d'));
                    $dataMetering = [];
                    foreach ($array_unit as $key => $value) {
                        $model_name = 'App\Penyaluran\Pln\\' . $value;
                        $metering = $model_name::select(
                            DB::raw('date(from_unixtime(`time@timestamp`)) as date'),
                            DB::raw('(MAX(data_format_6) - MIN(data_format_6)) * 1000 as diff')
                        )
                            ->where(DB::raw('from_unixtime(`time@timestamp`)'), 'LIKE', '%' . $filter . '%')
                            ->groupBy(DB::raw('date(from_unixtime(`time@timestamp`))'))
                            ->get();

                        $diff = [];
                        foreach ($array_date as $row) {
                            $f_date = strlen($row) == 1 ? 0 . $row : $row;
                            $date = $filter . '-' . $f_date;

                            $total = $metering->firstWhere('date', $date);
                            $diff[] = $total ? $total->diff : 0;
                        }
                        $dataMetering[] = [
                            'unit' => $value,
                            'total' => $diff
                        ];
                    }
                    foreach ($array_date as $row) {
                        $f_date = strlen($row) == 1 ? 0 . $row : $row;
                        $date = $filter . '-' . $f_date;
                        $data[] = [
                            'date' => $f_date,
                            'unit1' => $dataMetering[0]['total'][$row - 1],
                            'unit2' => $dataMetering[1]['total'][$row - 1],
                            'unit3' => $dataMetering[2]['total'][$row - 1],
                            'unit4' => $dataMetering[3]['total'][$row - 1],
                            'total' => $dataMetering[0]['total'][$row - 1] +
                                $dataMetering[1]['total'][$row - 1] + $dataMetering[2]['total'][$row - 1] +
                                $dataMetering[3]['total'][$row - 1]
                        ];
                    }
                } elseif ($periode == 2) {
                    $bulan = 'JAN FEB MAR APR MEI JUN JUL AUG SEP OKT NOV DES';
                    $array_bulan = explode(' ', $bulan);
                    $array_year = range(1, 12);
                    $dataMetering = [];
                    foreach ($array_unit as $key => $value) {
                        $model_name = 'App\Penyaluran\Pln\\' . $value;
                        $metering = $model_name::select(
                            DB::raw('CONCAT(YEAR(from_unixtime(`time@timestamp`)),"-",MONTH(from_unixtime(`time@timestamp`))) as date'),
                            DB::raw('(MAX(data_format_6) - MIN(data_format_6))*1000 as diff')
                        )
                            ->where(DB::raw('YEAR(from_unixtime(`time@timestamp`))'), 'LIKE', '%' . $year . '%')
                            ->groupBy(DB::raw('CONCAT(YEAR(from_unixtime(`time@timestamp`)),"-",MONTH(from_unixtime(`time@timestamp`)))'))
                            ->get();

                        $diff = [];
                        foreach ($array_year as $row) {
                            $year_month = $year . '-' . $row;

                            $total = $metering->firstWhere('date', $year_month);
                            $diff[] = $total ? $total->diff : 0;
                        }
                        $dataMetering[] = [
                            'unit' => $value,
                            'total' => $diff
                        ];
                    }
                    foreach ($array_year as $row) {
                        $data[] = [
                            'date' => $array_bulan[$row - 1],
                            'unit1' => $dataMetering[0]['total'][$row - 1],
                            'unit2' => $dataMetering[1]['total'][$row - 1],
                            'unit3' => $dataMetering[2]['total'][$row - 1],
                            'unit4' => $dataMetering[3]['total'][$row - 1],
                            'total' => $dataMetering[0]['total'][$row - 1] +
                                $dataMetering[1]['total'][$row - 1] + $dataMetering[2]['total'][$row - 1] +
                                $dataMetering[3]['total'][$row - 1]
                        ];
                    }
                } else {
                    $array_year = range($year - 9 < 2017 ? 2017 : $year - 9, $year);
                    $dataMetering = [];
                    foreach ($array_unit as $key => $value) {
                        $model_name = 'App\Penyaluran\pln\\' . $value;
                        $metering = $model_name::select(
                            DB::raw('year(from_unixtime(`time@timestamp`)) as year'),
                            DB::raw('(MAX(data_format_6) - MIN(data_format_6))*1000 as diff')
                        )
                            ->groupBy(DB::raw('year(from_unixtime(`time@timestamp`))'))
                            ->get();

                        $diff = [];
                        foreach ($array_year as $row) {
                            $total = $metering->firstWhere('year', $row);

                            $diff[$row] = $total ? $total->diff : 0;
                        }
                        $dataMetering[] = [
                            'unit' => $value,
                            'total' => $diff
                        ];
                    }
                    foreach ($array_year as $row) {
                        $data[] = [
                            'date' => $row,
                            'unit1' => $dataMetering[0]['total'][$row],
                            'unit2' => $dataMetering[1]['total'][$row],
                            'unit3' => $dataMetering[2]['total'][$row],
                            'unit4' => $dataMetering[3]['total'][$row],
                            'total' => $dataMetering[0]['total'][$row] +
                                $dataMetering[1]['total'][$row] + $dataMetering[2]['total'][$row] +
                                $dataMetering[3]['total'][$row]
                        ];
                    }
                }
                break;


            default: //Penyaluran
                $array_unit = ['Metering1', 'Metering2'];
                if ($periode == 1) {
                    $filter = $year . '-' . $month;
                    $parse = Carbon::parse($filter);
                    $array_date = range($parse->startOfMonth()->format('d'), $parse->endOfMonth()->format('d'));
                    $dataMetering = [];
                    foreach ($array_unit as $key => $value) {
                        $model_name = 'App\Penyaluran\Nonpln\\' . $value;
                        $metering = $model_name::select(
                            DB::raw('date(from_unixtime(`time@timestamp`)) as date'),
                            DB::raw('(MAX(data_format_6) - MIN(data_format_6)) * 1000 as diff')
                        )
                            ->where(DB::raw('from_unixtime(`time@timestamp`)'), 'LIKE', '%' . $filter . '%')
                            ->groupBy(DB::raw('date(from_unixtime(`time@timestamp`))'))
                            ->get();

                        $diff = [];
                        foreach ($array_date as $row) {
                            $f_date = strlen($row) == 1 ? 0 . $row : $row;
                            $date = $filter . '-' . $f_date;

                            $total = $metering->firstWhere('date', $date);
                            $diff[] = $total ? $total->diff : 0;
                        }
                        $dataMetering[] = [
                            'unit' => $value,
                            'total' => $diff
                        ];
                    }
                    foreach ($array_date as $row) {
                        $f_date = strlen($row) == 1 ? 0 . $row : $row;
                        $date = $filter . '-' . $f_date;
                        $data[] = [
                            'date' => $f_date,
                            'unit1' => $dataMetering[0]['total'][$row - 1],
                            'unit2' => $dataMetering[1]['total'][$row - 1],
                            'total' => $dataMetering[0]['total'][$row - 1] +
                                $dataMetering[1]['total'][$row - 1]
                        ];
                    }
                } elseif ($periode == 2) {
                    $bulan = 'JAN FEB MAR APR MEI JUN JUL AUG SEP OKT NOV DES';
                    $array_bulan = explode(' ', $bulan);
                    $array_year = range(1, 12);
                    $dataMetering = [];
                    foreach ($array_unit as $key => $value) {
                        $model_name = 'App\Penyaluran\Nonpln\\' . $value;
                        $metering = $model_name::select(
                            DB::raw('CONCAT(YEAR(from_unixtime(`time@timestamp`)),"-",MONTH(from_unixtime(`time@timestamp`))) as date'),
                            DB::raw('(MAX(data_format_6) - MIN(data_format_6))*1000 as diff')
                        )
                            ->where(DB::raw('YEAR(from_unixtime(`time@timestamp`))'), 'LIKE', '%' . $year . '%')
                            ->groupBy(DB::raw('CONCAT(YEAR(from_unixtime(`time@timestamp`)),"-",MONTH(from_unixtime(`time@timestamp`)))'))
                            ->get();

                        $diff = [];
                        foreach ($array_year as $row) {
                            $year_month = $year . '-' . $row;

                            $total = $metering->firstWhere('date', $year_month);
                            $diff[] = $total ? $total->diff : 0;
                        }
                        $dataMetering[] = [
                            'unit' => $value,
                            'total' => $diff
                        ];
                    }
                    foreach ($array_year as $row) {
                        $data[] = [
                            'date' => $array_bulan[$row - 1],
                            'unit1' => $dataMetering[0]['total'][$row - 1],
                            'unit2' => $dataMetering[1]['total'][$row - 1],
                            'total' => $dataMetering[0]['total'][$row - 1] +
                                $dataMetering[1]['total'][$row - 1]
                        ];
                    }
                } else {
                    $array_year = range($year - 9 < 2017 ? 2017 : $year - 9, $year);
                    $dataMetering = [];
                    foreach ($array_unit as $key => $value) {
                        $model_name = 'App\Penyaluran\Nonpln\\' . $value;
                        $metering = $model_name::select(
                            DB::raw('year(from_unixtime(`time@timestamp`)) as year'),
                            DB::raw('(MAX(data_format_6) - MIN(data_format_6))*1000 as diff')
                        )
                            ->groupBy(DB::raw('year(from_unixtime(`time@timestamp`))'))
                            ->get();

                        $diff = [];
                        foreach ($array_year as $row) {
                            $total = $metering->firstWhere('year', $row);

                            $diff[$row] = $total ? $total->diff : 0;
                        }
                        $dataMetering[] = [
                            'unit' => $value,
                            'total' => $diff
                        ];
                    }
                    foreach ($array_year as $row) {
                        $data[] = [
                            'date' => $row,
                            'unit1' => $dataMetering[0]['total'][$row],
                            'unit2' => $dataMetering[1]['total'][$row],
                            'total' => $dataMetering[0]['total'][$row] +
                                $dataMetering[1]['total'][$row]
                        ];
                    }
                }
                // if ($periode == 1) {
                //     $filter = $year . '-' . $month;
                //     $parse = Carbon::parse($filter);
                //     $array_date = range($parse->startOfMonth()->format('d'), $parse->endOfMonth()->format('d'));
                //     $model_name = 'App\Penyaluran\\' . $array_unit[$unit - 3];
                //     $metering = $model_name::select(
                //         DB::raw('date(from_unixtime(`time@timestamp`)) as date'),
                //         DB::raw('(MAX(data_format_6) - MIN(data_format_6)) * 1000 as diff')
                //     )
                //         ->where(DB::raw('from_unixtime(`time@timestamp`)'), 'LIKE', '%' . $filter . '%')
                //         ->groupBy(DB::raw('date(from_unixtime(`time@timestamp`))'))
                //         ->get();

                //     foreach ($array_date as $row) {
                //         $f_date = strlen($row) == 1 ? 0 . $row : $row;
                //         $date = $filter . '-' . $f_date;
                //         $total = $metering->firstWhere('date', $date);

                //         $data[] = [
                //             'date' => $f_date,
                //             'total' => $total ? $total->diff : 0,
                //         ];
                //     }
                // } elseif ($periode == 2) {
                //     $bulan = 'JAN FEB MAR APR MEI JUN JUL AUG SEP OKT NOV DES';
                //     $array_bulan = explode(' ', $bulan);
                //     $array_year = range(1, 12);
                //     $model_name = 'App\Penyaluran\\' . $array_unit[$unit - 3];
                //     $metering = $model_name::select(
                //         DB::raw('CONCAT(YEAR(from_unixtime(`time@timestamp`)),"-",MONTH(from_unixtime(`time@timestamp`))) as date'),
                //         DB::raw('(MAX(data_format_6) - MIN(data_format_6))*1000 as diff')
                //     )
                //         ->where(DB::raw('YEAR(from_unixtime(`time@timestamp`))'), 'LIKE', '%' . $year . '%')
                //         ->groupBy(DB::raw('CONCAT(YEAR(from_unixtime(`time@timestamp`)),"-",MONTH(from_unixtime(`time@timestamp`)))'))
                //         ->get();

                //     foreach ($array_year as $row) {
                //         $year_month = $year . '-' . $row;
                //         $total = $metering->firstWhere('date', $year_month);

                //         $data[] = [
                //             'date' => $array_bulan[$row - 1],
                //             'total' => $total ? $total->diff : 0,
                //         ];
                //     }
                // } else {
                //     $array_year = range($year - 9 < 2017 ? 2017 : $year - 9, $year);
                //     $model_name = 'App\Penyaluran\\' . $array_unit[$unit - 3];
                //     $metering = $model_name::select(
                //         DB::raw('year(from_unixtime(`time@timestamp`)) as year'),
                //         DB::raw('(MAX(data_format_6) - MIN(data_format_6))*1000 as diff')
                //     )
                //         ->groupBy(DB::raw('year(from_unixtime(`time@timestamp`))'))
                //         ->get();

                //     foreach ($array_year as $row) {
                //         $total = $metering->firstWhere('year', $row);
                //         $data[] = [
                //             'date' => $row,
                //             'total' => $total ? $total->diff : 0,
                //         ];
                //     }
                // }
                break;
        }

        return $data;
    }
}
