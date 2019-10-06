<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Classes\GoogleDriveAPIS;

class TrashController extends Controller
{
    //
    function AutoDeleteGd()
    {
        $googledrive = new GoogleDriveAPIS();
        $datass = \App\Trash::take(10)->get();
        if (!is_null($datass)) {
            foreach ($datass as $datass) {
                $idcopy = $datass->idcopy;
                $tokens = $datass;
                if (!is_null($idcopy) && !is_null($tokens)) {
                    if ($googledrive->deletegd($idcopy, $tokens)) {
                       $datass->delete();
                    }
                }
            }
            return "OK";
        }
        return "Failed";
    }
}
