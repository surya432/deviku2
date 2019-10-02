<?php

namespace App\Classes;

use GuzzleHttp\Client as GuzzleClient;
use Guzzle\Http\Exception\ClientErrorResponseException;
use GuzzleHttp\Psr7\Request;
use Auth;
use Exception;

class FEmbed
{
   function getKey($data, $mirror){
      try{
         $keys = explode(":::", $data['keys']);
         $data = "client_id=$keys[0]&client_secret=$keys[1]";
         return $data;
      }catch(Exception $e){
         return $e->getMessage();
      }
   }
   function clientCurl($urlEndpoint, $data)
   {
     
      $curl = curl_init();
      curl_setopt_array($curl, array(
         CURLOPT_URL => 'https://www.fembed.com/api' . $urlEndpoint,
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_MAXREDIRS => 10,
         CURLOPT_TIMEOUT => 30,
         CURLOPT_SSL_VERIFYHOST => FALSE,
         CURLOPT_SSL_VERIFYPEER => FALSE,
         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
         CURLOPT_CUSTOMREQUEST => "POST",
         CURLOPT_POSTFIELDS => $data,
         CURLOPT_HTTPHEADER => array(
            "Cache-Control: no-cache",
            "Content-Type: application/x-www-form-urlencoded",
         ),
      ));
      $response = curl_exec($curl);
      $err = curl_error($curl);
      curl_close($curl);
      if ($err) {
         return $err;
      } else {
         return $response;
      }
   }

   function fembedUpload($data)
   {
      $response = $this->clientCurl("/download", $data);
      return json_decode($response,true);
   }
   function fembedCheck($data)
   {
      $response = $this->clientCurl("/downloading", $data);
      return json_decode($response, true);
   }
   function fembedFile($data)
   {
      $response = $this->clientCurl("/file", $data);
      return json_decode($response, true);
   }
   function fembedAccount($data, $mirror)
   {
      $ClientID = \App\mirrorkey::join('master_mirrors', 'master_mirrors.id', '=', 'mirrorkeys.master_mirror_id')->where(['mirrorkeys.cmp_id' => $data->cmp_id])->where(['master_mirrors.name' => $mirror])->first();
      if ($ClientID != null) {
         if ($ClientID['status'] == "Up") {
            $data = $this->getKey($data, $mirror);
            $response = $this->clientCurl("/account", $data);
            return response()->json(['data' => $response]);
         }else if($ClientID['status']=="Down"){
            return response()->json(['API DOWN']);
         }else{
            return response()->json(['API Maintance']);
         }
      }
      return false;
   }
}
