<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function getPembangkitTotal()
    {
        $data = [];
        $array_unit = ['PLTA Djuanda', 'Mini Hydro'];
        foreach ($array_unit as $arr) {
            if ($arr == 'PLTA Djuanda') {
                $array_metering = ['Metering1', 'Metering2', 'Metering3', 'Metering4', 'Metering5', 'Metering6'];
                $total = 0;
                $max = 187;
                foreach ($array_metering as $key => $value) {
                    $model_name = 'App\Pembangkit\Plta\\' . $value;
                    $metering = $model_name::orderBy('data_index', 'DESC')->limit(1)->get();
                    foreach ($metering as $row) {
                        $total = $total + ($row->data_format_0);
                    }
                }
            } else {
                $array_metering = ['Metering1', 'Metering2'];
                $total = 0;
                $max = 6;
                foreach ($array_metering as $key => $value) {
                    $model_name = 'App\Pembangkit\Minihydro\\' . $value;
                    $metering = $model_name::orderBy('data_index', 'DESC')->limit(1)->get();
                    foreach ($metering as $row) {
                        $total = $total + ($row->data_format_0);
                    }
                }
            }

            $data[] = [
                'unit' => $arr,
                'total' => $total ? $total : 0,
                'max' => $max
            ];
        }

        return $data;
    }

    public function getPembangkitPlta()
    {
        $data = [];
        $array_metering = ['Metering1', 'Metering2', 'Metering3', 'Metering4', 'Metering5', 'Metering6'];
        foreach ($array_metering as $key => $value) {
            $model_name = 'App\Pembangkit\Plta\\' . $value;
            $metering = $model_name::orderBy('data_index', 'DESC')->limit(1)->get();
            foreach ($metering as $row) {
                $total = $row->data_format_0;
            }
            $data[] = [
                'unit' => 'Unit-' . (string) ($key + 1),
                'total' => $total ? $total : 0,
            ];
        }

        return $data;
    }

    public function getPembangkitMiniHydro()
    {
        $data = [];
        $array_metering = ['Metering1', 'Metering2'];
        foreach ($array_metering as $key => $value) {
            $model_name = 'App\Pembangkit\Minihydro\\' . $value;
            $metering = $model_name::orderBy('data_index', 'DESC')->limit(1)->get();
            foreach ($metering as $row) {
                $total = $row->data_format_0;
            }
            $data[] = [
                'unit' => 'Unit-' . (string) ($key + 1),
                'total' => $total ? $total : 0,
            ];
        }

        return $data;
    }

    // PENYALURAN
    public function getPenyaluranTotal()
    {
        $data = [];
        $array_unit = ['PLN', 'Penugasan'];
        foreach ($array_unit as $arr) {
            if ($arr == 'PLN') {
                $array_metering = ['Metering1', 'Metering2', 'Metering3', 'Metering4'];
                $total = 0;
                foreach ($array_metering as $key => $value) {
                    $model_name = 'App\Penyaluran\Pln\\' . $value;
                    $metering = $model_name::orderBy('data_index', 'DESC')->limit(1)->get();
                    foreach ($metering as $row) {
                        $total = $total + ($row->data_format_6 / 1000);
                    }
                }
            } else {
                $array_metering = ['Metering1', 'Metering2'];
                $total = 0;
                foreach ($array_metering as $key => $value) {
                    $model_name = 'App\Penyaluran\Nonpln\\' . $value;
                    $metering = $model_name::orderBy('data_index', 'DESC')->limit(1)->get();
                    foreach ($metering as $row) {
                        $total = $total + ($row->data_format_6 / 1000);
                    }
                }
            }

            $data[] = [
                'unit' => $arr,
                'total' => $total ? $total : 0,
            ];
        }

        return $data;
    }

    public function getPenyaluranPln()
    {
        $data = [];

        $array_unit = ['PLN', 'Penugasan'];
        foreach ($array_unit as $arr) {
            if ($arr == 'PLN') {
                $array_metering = [
                    'Metering1' => 'Tata Jabar-1',
                    'Metering2' => 'Tata Jabar-2',
                    'Metering3' => 'Padalarang-1',
                    'Metering4' => 'Padalarang-2'
                ];
                foreach ($array_metering as $key => $value) {
                    $model_name = 'App\Penyaluran\Pln\\' . $key;
                    $metering = $model_name::orderBy('data_index', 'DESC')->limit(1)->get();
                    foreach ($metering as $row) {
                        $total = $row->data_format_6 / 1000;
                    }
                    $data[] = [
                        'unit' => $value,
                        'total' => $total ? $total : 0
                    ];
                }
            } else {
                $array_metering = ['Metering1' => 'Line Curug', 'Metering2' => 'Line Ciganea'];
                foreach ($array_metering as $key => $value) {
                    $model_name = 'App\Penyaluran\Nonpln\\' . $key;
                    $metering = $model_name::orderBy('data_index', 'DESC')->limit(1)->get();
                    foreach ($metering as $row) {
                        $total = $row->data_format_6 / 1000;
                    }
                    $data[] = [
                        'unit' => $value,
                        'total' => $total ? $total : 0
                    ];
                }
            }
        }

        return $data;
    }
}
