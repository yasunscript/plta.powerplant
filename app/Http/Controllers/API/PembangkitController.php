<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PembangkitController extends Controller
{
    public function getMeteringData()
    {
        if (request()->unit <= 6) {
            $model_name = 'App\Pembangkit\Plta\Metering' . request()->unit;
        } else {
            $model_name = 'App\Pembangkit\Minihydro\Metering' . (string) (request()->unit - 6);
        }
        $periode = request()->periode;

        $data = [];
        if ($periode == 1) {
            # harian code...
            $filter = request()->year . '-' . request()->month;
            $parse = Carbon::parse($filter);
            $array_date = range($parse->startOfMonth()->format('d'), $parse->endOfMonth()->format('d'));

            $metering = $model_name::select(DB::raw('date(from_unixtime(`time@timestamp`)) as date,
                AVG(data_format_0) as data_0,
                AVG(data_format_1) as data_1,
                AVG(data_format_2) as data_2,
                AVG(data_format_3) as data_3,
                AVG(data_format_4) as data_4,
                AVG(data_format_5) as data_5,
                AVG(data_format_6) as data_6'))
                ->where(DB::raw('date(from_unixtime(`time@timestamp`))'), 'LIKE', '%' . $filter . '%')
                ->groupBy(DB::raw('date(from_unixtime(`time@timestamp`))'))
                ->get();

            foreach ($array_date as $row) {
                $f_date = strlen($row) == 1 ? 0 . $row : $row;
                $date = $filter . '-' . $f_date;
                $total = $metering->firstWhere('date', $date);

                $data[] = [
                    'date' => $f_date,
                    'data_0' => $total ? $total->data_0 : 0, //DayaAktif(MW)
                    'data_1' => $total ? $total->data_1 : 0, //DayaReaktif(MVAR)
                    'data_2' => $total ? $total->data_2 : 0, //Tegangan(KV)
                    'data_3' => $total ? $total->data_3 : 0, //Arus(KA)
                    'data_4' => $total ? $total->data_4 : 0, //Frequency(Hz)
                    'data_5' => $total ? $total->data_5 : 0, //PowerFactor(CosPhi)
                    'data_6' => $total ? $total->data_6 : 0, //Energy(KWH)
                ];
            }
        } elseif ($periode == 2) {
            # bulanan code...
            $bulan = 'JAN FEB MAR APR MEI JUN JUL AUG SEP OKT NOV DES';
            $array_bulan = explode(' ', $bulan);
            $month = request()->month;
            $year = request()->year;

            $array_year = range(1, 12);
            $metering = $model_name::select(DB::raw('
            CONCAT(YEAR(from_unixtime(`time@timestamp`)),"/",MONTH(from_unixtime(`time@timestamp`))) as tahun_bulan,
                AVG(data_format_0) as data_0,
                AVG(data_format_1) as data_1,
                AVG(data_format_2) as data_2,
                AVG(data_format_3) as data_3,
                AVG(data_format_4) as data_4,
                AVG(data_format_5) as data_5,
                AVG(data_format_6) as data_6'))
                ->where(DB::raw('YEAR(from_unixtime(`time@timestamp`))'), 'LIKE', '%' . $year . '%')
                ->groupBy(DB::raw('CONCAT(YEAR(from_unixtime(`time@timestamp`)),"/",MONTH(from_unixtime(`time@timestamp`)))'))
                ->get();

            foreach ($array_year as $row) {
                $year_month = $year . '/' . $row;
                $total = $metering->firstWhere('tahun_bulan', $year_month);
                $data[] = [
                    'date' => $array_bulan[$row - 1],
                    'data_0' => $total ? $total->data_0 : 0, //DayaAktif(MW)
                    'data_1' => $total ? $total->data_1 : 0, //DayaReaktif(MVAR)
                    'data_2' => $total ? $total->data_2 : 0, //Tegangan(KV)
                    'data_3' => $total ? $total->data_3 : 0, //Arus(KA)
                    'data_4' => $total ? $total->data_4 : 0, //Frequency(Hz)
                    'data_5' => $total ? $total->data_5 : 0, //PowerFactor(CosPhi)
                    'data_6' => $total ? $total->data_6 : 0, //Energy(KWH)
                ];
            }
        } else {
            # tahunan code...
            $month = request()->month;
            $year = request()->year;
            $array_year = range($year - 9 < 2017 ? 2017 : $year - 9, $year);
            $metering = $model_name::select(DB::raw('year(from_unixtime(`time@timestamp`)) as year,
                AVG(data_format_0) as data_0,
                AVG(data_format_1) as data_1,
                AVG(data_format_2) as data_2,
                AVG(data_format_3) as data_3,
                AVG(data_format_4) as data_4,
                AVG(data_format_5) as data_5,
                AVG(data_format_6) as data_6'))
                // ->where(DB::raw('year(from_unixtime(`time@timestamp`))'), 'LIKE', '%' . $year . '%')
                ->groupBy(DB::raw('year(from_unixtime(`time@timestamp`))'))
                ->get();

            foreach ($array_year as $row) {
                $total = $metering->firstWhere('year', $row);
                $data[] = [
                    'date' => $row,
                    'data_0' => $total ? $total->data_0 : 0, //DayaAktif(MW)
                    'data_1' => $total ? $total->data_1 : 0, //DayaReaktif(MVAR)
                    'data_2' => $total ? $total->data_2 : 0, //Tegangan(KV)
                    'data_3' => $total ? $total->data_3 : 0, //Arus(KA)
                    'data_4' => $total ? $total->data_4 : 0, //Frequency(Hz)
                    'data_5' => $total ? $total->data_5 : 0, //PowerFactor(CosPhi)
                    'data_6' => $total ? $total->data_6 : 0, //Energy(KWH)
                ];
            }
        }

        return $data;
    }
}
