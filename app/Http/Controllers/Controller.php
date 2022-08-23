<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function FormColumns($Columns)
    {
        $ColumnForm = [];
        $name = [];
        foreach ($Columns as  $value) {
            $name = [
                'name'  => $value[0],
                'icon'  => $value[2]
            ];

            if ($value[1] == 'select') {
                $type = [
                    'type'          => 'select',
                    'route'         => empty($value[3]) ? '' : "../$value[3]",
                    'value'         => empty($value[4]) ? '' : $value[4],
                    'size'          => empty($value[5]) ? '' : $value[5],
                    'global'        => empty($value[6]) ? '' : $value[6],
                    'placeholder'   => empty($value[7]) ? '' : $value[7],
                ];
                // }
            } elseif ($value[1] == 'file') {
                $type = [
                    'type'          => 'file-' . $value[2],
                    'placeholder'   => empty($value[3]) ? '' : $value[3]
                ];
            } else {
                $type = [
                    'type' => $value[1],
                    'placeholder'   => empty($value[4]) ? '' : $value[4],
                    'value'         => empty($value[3]) ? '' : $value[3],
                    'size'          => empty($value[5]) ? '' : $value[5],
                ];
            }

            array_push($ColumnForm, array_merge($name, $type));
        }
        return $ColumnForm;
    }
}
