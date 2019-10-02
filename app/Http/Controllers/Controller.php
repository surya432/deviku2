<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
     /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
   

    public function seoUrl($string)
    {
        $string = trim($string); // Trim String
        $string = strtolower($string); //Unwanted:  {UPPERCASE} ; / ? : @ & = + $ , . ! ~ * ' ( )
        $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);  //Strip any unwanted characters
        $string = preg_replace("/[\s-]+/", " ", $string); // Clean multiple dashes or whitespaces
        $string = preg_replace("/[\s_]/", "-", $string); //Convert whitespaces and underscore to dash
        return $string;
    }
    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($errorMessages = [], $code = 404)
    {
    	$response = [
            'success' => false,
            'message' => $errorMessages,
            'status'=> 'error'
        ];


        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }


        return response()->json($response, $code);
    }
    public function sendResponse($result, $message)
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
            'status' => 'success'

        ];


        return response()->json($response, 200);
    }
}
