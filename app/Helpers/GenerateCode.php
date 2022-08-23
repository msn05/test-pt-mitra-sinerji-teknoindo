<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class GenerateCode
{

  public static function create($table, $prefix)
  {
    $Class = 'App\\Models\\' . trim(Str::ucfirst($table));
    $ClassName = new $Class();
    $lastData = $ClassName->latest()->first();
    if (is_null($lastData)) $lastCode = $prefix . '001';
    else {
      $lastCode = Str::substr($lastData->code, Str::length($prefix), (Str::length($lastData->code) - Str::length($prefix)));
      if ((int)$lastCode < 100)
        $lastCode =  $prefix . '00' . ((int)$lastCode + 1);
      else $lastCode = $prefix . ((int)$lastCode + 1);
    }
    return $lastCode;
  }
}
