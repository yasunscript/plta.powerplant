<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PenyaluranController extends Controller
{
    public function getMeteringData()
    {
        if (request()->unit <= 4) {
            $model_name = 'App\Penyaluran\Pln\Metering' . request()->unit;
        } else {
            $model_name = 'App\Penyaluran\Nonpln\Metering' . (string) (request()->unit - 6);
        }
        $periode = request()->periode;

        $data = [];
        if ($periode == 1) {
            # Harian code...
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
                AVG(data_format_6) as data_6,
                AVG(data_format_7) as data_7,
                AVG(data_format_8) as data_8,
                AVG(data_format_9) as data_9,
                AVG(data_format_10) as data_10'))
                ->where(DB::raw('date(from_unixtime(`time@timestamp`))'), 'LIKE', '%' . $filter . '%')
                ->groupBy(DB::raw('date(from_unixtime(`time@timestamp`))'))
                ->get();

            foreach ($array_date as $row) {
                $f_date = strlen($row) == 1 ? 0 . $row : $row;
                $date = $filter . '-' . $f_date;
                $total = $metering->firstWhere('date', $date);

                $data[] = [
                    'date' => $f_date,
                    'data_0' => $total ? $total->data_0 : 0, //Vll_ab_Kosambi1
                    'data_1' => $total ? $total->data_1 : 0, //Vll_bc_Kosambi1
                    'data_2' => $total ? $total->data_2 : 0, //Vll_ac_Kosambi1
                    'data_3' => $total ? $total->data_3 : 0, //Ia_Kosambi1
                    'data_4' => $total ? $total->data_4 : 0, //Ib_Kosambi1
                    'data_5' => $total ? $total->data_5 : 0, //Ic_Kosambi1
                    'data_6' => $total ? $total->data_6 : 0, //kW_tot_Kosambi1
                    'data_7' => $total ? $total->data_7 : 0, //kVAR_tot_Kosambi1
                    'data_8' => $total ? $total->data_8 : 0, //PF_sign_tot_Kosambi1
                    'data_9' => $total ? $total->data_9 : 0, //Freq_Kosambi1
                    'data_10' => $total ? $total->data_10 : 0, //kWh_del_Import_Kosambi1
                ];
            }
        } elseif ($periode == 2) {
            # Bulanan code...
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
                AVG(data_format_6) as data_6,
                AVG(data_format_7) as data_7,
                AVG(data_format_8) as data_8,
                AVG(data_format_9) as data_9,
                AVG(data_format_10) as data_10'))
                ->where(DB::raw('YEAR(from_unixtime(`time@timestamp`))'), 'LIKE', '%' . $year . '%')
                ->groupBy(DB::raw('CONCAT(YEAR(from_unixtime(`time@timestamp`)),"/",MONTH(from_unixtime(`time@timestamp`)))'))
                ->get();

            foreach ($array_year as $row) {
                $year_month = $year . '/' . $row;
                $total = $metering->firstWhere('tahun_bulan', $year_month);

                $data[] = [
                    'date' => $array_bulan[$row - 1],
                    'data_0' => $total ? $total->data_0 : 0, //Vll_ab_Kosambi1
                    'data_1' => $total ? $total->data_1 : 0, //Vll_bc_Kosambi1
                    'data_2' => $total ? $total->data_2 : 0, //Vll_ac_Kosambi1
                    'data_3' => $total ? $total->data_3 : 0, //Ia_Kosambi1
                    'data_4' => $total ? $total->data_4 : 0, //Ib_Kosambi1
                    'data_5' => $total ? $total->data_5 : 0, //Ic_Kosambi1
                    'data_6' => $total ? $total->data_6 : 0, //kW_tot_Kosambi1
                    'data_7' => $total ? $total->data_7 : 0, //kVAR_tot_Kosambi1
                    'data_8' => $total ? $total->data_8 : 0, //PF_sign_tot_Kosambi1
                    'data_9' => $total ? $total->data_9 : 0, //Freq_Kosambi1
                    'data_10' => $total ? $total->data_10 : 0, //kWh_del_Import_Kosambi1
                ];
            }
        } else {
            # Tahunan code...
            $month = request()->month;
            $year = request()->year;
            $array_year = range($year - 9 < 2017 ? 2017 : $year - 9, $year);
            $metering = $model_name::select(DB::raw('
            year(from_unixtime(`time@timestamp`)) as year,
                AVG(data_format_0) as data_0,
                AVG(data_format_1) as data_1,
                AVG(data_format_2) as data_2,
                AVG(data_format_3) as data_3,
                AVG(data_format_4) as data_4,
                AVG(data_format_5) as data_5,
                AVG(data_format_6) as data_6,
                AVG(data_format_7) as data_7,
                AVG(data_format_8) as data_8,
                AVG(data_format_9) as data_9,
                AVG(data_format_10) as data_10'))
                // ->where(DB::raw('year(from_unixtime(`time@timestamp`))'), 'LIKE', '%' . $year . '%')
                ->groupBy(DB::raw('year(from_unixtime(`time@timestamp`))'))
                ->get();

            foreach ($array_year as $row) {
                $total = $metering->firstWhere('year', $row);
                $data[] = [
                    'date' => $row,
                    'data_0' => $total ? $total->data_0 : 0, //Vll_ab_Kosambi1
                    'data_1' => $total ? $total->data_1 : 0, //Vll_bc_Kosambi1
                    'data_2' => $total ? $total->data_2 : 0, //Vll_ac_Kosambi1
                    'data_3' => $total ? $total->data_3 : 0, //Ia_Kosambi1
                    'data_4' => $total ? $total->data_4 : 0, //Ib_Kosambi1
                    'data_5' => $total ? $total->data_5 : 0, //Ic_Kosambi1
                    'data_6' => $total ? $total->data_6 : 0, //kW_tot_Kosambi1
                    'data_7' => $total ? $total->data_7 : 0, //kVAR_tot_Kosambi1
                    'data_8' => $total ? $total->data_8 : 0, //PF_sign_tot_Kosambi1
                    'data_9' => $total ? $total->data_9 : 0, //Freq_Kosambi1
                    'data_10' => $total ? $total->data_10 : 0, //kWh_del_Import_Kosambi1
                ];
            }
        }

        return $data;
    }
}
