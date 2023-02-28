<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PDFMAKERContrller extends Controller
{
    //
    public function html_data($value,$data)
    {
        $data.str_replace("   [Head of the Inspection Unit]",$value,1);
        dd($data);
        return $data;
    }
}
