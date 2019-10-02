<?php

namespace App\Classes;

use App\Classes\FEmbed as FEmbed;
use App\Classes\RapidVideo as RapidVideo;
use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Cache;
use Exception;

class SmmFunction
{
    public function sendError($errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $errorMessages,
            'status' => 'error'
        ];
        if (!empty($errorMessages)) {
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
    public function getMirror($data, $mirror)
    {
        switch ($mirror) {
            case "fembed.com":
                try {
                    return $this->fembedCopy($data, $mirror);
                } catch (Exception $e) {
                    return null;
                }
                break;
            case "rapidvideo.com":
                try {
                    return $this->RapidVideo($data, $mirror);
                } catch (Exception $e) {
                    return null;
                }
                break;
            case "openload.com":
                try {
                    return $this->openload($data, $mirror);
                } catch (Exception $e) {
                    return null;
                }
                break;
            case "drive.google.com":
                try {
                    return $this->googledrive($data, $mirror);
                } catch (Exception $e) {
                    return null;
                }
                break;
        }
    }
    function getProviderStatus($data, $mirror)
    {
        return \App\mirrorkey::join('master_mirrors', 'master_mirrors.id', '=', 'mirrorkeys.master_mirror_id')->where(['mirrorkeys.cmp_id' => $data->cmp_id])->where(['master_mirrors.name' => $mirror])->inRandomOrder()->first();
    }
    function GetIdDrive($urlVideoDrive)
    {
        if (preg_match('@https?://(?:[\w\-]+\.)*(?:drive|docs)\.google\.com/(?:(?:folderview|open|uc)\?(?:[\w\-\%]+=[\w\-\%]*&)*id=|(?:folder|file|document|presentation)/d/|spreadsheet/ccc\?(?:[\w\-\%]+=[\w\-\%]*&)*key=)([\w\-]{28,})@i', $urlVideoDrive, $id)) {
            return $id[1];
        } else {
            return false;
        }
    }
    function fembedCopy($data, $mirror)
    {
        $response = [];
        $ClientID = $this->getProviderStatus($data, $mirror);
        if ($ClientID != null) {
            $fembed = new FEmbed();
            $copies  = \App\Mirrorcopy::where(['drive' => $data['link']])->where(['provider' => $mirror])->first();
            if ($copies) {
                $url = "";
                if ($copies['status'] == "Task is completed") {
                    $url =  $copies['url'];
                    Cache::remember(md5($copies['url']), 3600 * 48, function () use ($url, $data, $mirror, $fembed, $copies) {
                        $keys = $fembed->getKey($this->getProviderStatus($data, $mirror), $mirror) . "&file_id=" . $url;
                        $dataCheck = $fembed->fembedFile($keys);

                        if ($dataCheck['data']['status'] != 'Live') {
                            $copies->delete();
                        }
                    });
                } else {
                    // Cache::remember("test", 60, function () use ($url, $data, $mirror, $fembed, $copies) {
                    $apikey = $fembed->getKey($this->getProviderStatus($data, $mirror), $mirror);
                    $dataCurl = $fembed->fembedCheck($apikey);
                    if ($dataCurl['success']) {
                        foreach ($dataCurl['data'] as $a => $b) {
                            if ($b['status'] == 'Task is completed') {
                                $apikeys = $fembed->getKey($this->getProviderStatus($data, $mirror), $mirror) . "&task_id=" . $b['id'];
                                $dataMirror =  \App\Mirrorcopy::where('apikey', 'like', '%' . $apikeys . '%')->first();
                                if ($dataMirror) {
                                    $dataMirror->url = $b['file_id'];
                                    $dataMirror->status = $b['status'];
                                    $dataMirror->save();
                                    $arrayid = array();
                                    array_push($arrayid, $b['id']);
                                    $apikeyremove = $dataMirror->apikey . "&remove_ids=" . json_encode($arrayid);
                                    $dataCurl = $fembed->fembedCheck($apikeyremove);
                                }
                                // $apikeyremove = $apikey . "&remove_ids=" . json_encode($arrayid);
                                // $dataCurl = $fembed->fembedCheck($apikeyremove);
                                if ($apikeys == $copies['apikey']) {
                                    $url = $b['file_id'];
                                }
                            }
                        }
                    }
                    // });
                }
                return "https://www.fembed.com/v/" . $url;
            } else {
                if ($ClientID['status'] == "Up") {
                    $urlDownload = [];
                    $nameVideo =  md5($data['link']) . "-" . $data['kualitas'];
                    $driveId = $this->GetIdDrive($data['link']);
                    $severDownload = $this->getProviderStatus($data, "ServerDownload");
                    $urlDownload[] = array("link" => $severDownload['keys'] . "/" . $driveId . "/" . $nameVideo . ".mp4", "headers" => "");
                    $datacurl = $fembed->getKey($this->getProviderStatus($data, $mirror), $mirror) . "&links=" . json_encode($urlDownload);
                    $resultCurl = $fembed->fembedUpload($datacurl);
                    if ($resultCurl['success']) {
                        $mirrorcopies  = new \App\Mirrorcopy();
                        $mirrorcopies->url = null;
                        $mirrorcopies->status = "uploaded";
                        $mirrorcopies->drive = $data['link'];
                        $mirrorcopies->provider = $mirror;
                        $mirrorcopies->apikey = $fembed->getKey($this->getProviderStatus($data, $mirror), $mirror) . "&task_id=" . $resultCurl['data'][0];
                        $mirrorcopies->save();
                        // return $this->sendError("Uploaded....");
                        return "";
                    } else {
                        return "";
                    }
                } else {
                    // return $this->sendError("API " . $mirror . " Sedang " . $ClientID['status']);
                    return "";
                }
            }
        } else {
            // $response = $this->sendError("Belum Setting Akun");
            // return $response;
            return "";
        }

        // $fembed = new FEmbed();
        // return $fembed->fembedAccount($data, $mirror);
    }
    // function ServerDownload($data, $mirror)
    // {
    //     $data = \App\mirrorkey::join('master_mirrors', 'master_mirrors.id', '=', 'mirrorkeys.master_mirror_id')->where(['mirrorkeys.cmp_id' => $data->cmp_id])->where(['master_mirrors.name' => $mirror])->first();
    //     return $data['keys'];
    // }

    function RapidVideo($data, $mirror)
    {
        $ClientID = $this->getProviderStatus($data, $mirror);
        if (is_null($ClientID)) {
            return null;
        } else {
            $rapidvideo = new RapidVideo();
            $copies  = \App\Mirrorcopy::where(['drive' => $data['link']])->where(['provider' => $mirror])->first();
            if (is_null($copies)) {
                if ($ClientID['status'] == "Up") {
                    $nameVideo = md5($data['link']) . "-" . $data['kualitas'];
                    $driveId = $this->GetIdDrive($data['link']);
                    $severDownload = $this->getProviderStatus($data, "ServerDownload");
                    $urlDownload =  $severDownload['keys'] . "/" . $driveId . "/" . $nameVideo . ".mp4";
                    $datacurl = $rapidvideo->getKey($this->getProviderStatus($data, $mirror), $mirror) . "&url=" . $urlDownload;
                    $resultCurl = $rapidvideo->RapidVideoUpload($datacurl);
                    if ($resultCurl['status'] == "OK") {
                        $mirrorcopies  = new \App\Mirrorcopy();
                        $mirrorcopies->url = null;
                        $mirrorcopies->status = "uploaded";
                        $mirrorcopies->drive = $data['link'];
                        $mirrorcopies->provider = $mirror;
                        $mirrorcopies->apikey = $rapidvideo->getKey($this->getProviderStatus($data, $mirror), $mirror) . "&id=" . $resultCurl['id'];
                        $mirrorcopies->save();
                    }
                    return null;
                }

                return "";
            } else {
                $urlID = "";
                if ($copies['status'] == "uploaded") {
                    $checkResult = $rapidvideo->RapidVideoStatus($copies['apikey']);
                    if ($checkResult['msg'] == "OK") {
                        foreach ($checkResult['result'] as $a => $b) {
                            if ($b['status'] == 'finished') {
                                $copies->url = $b['extid'];
                                $copies->status = "Task is completed";
                                $copies->save();
                                $urlID = "https://www.rapidvideo.com/v/" . $b['extid'];
                            }
                        }
                    }
                    return $urlID;
                }
                return "https://www.rapidvideo.com/v/" . $copies['url'];
            }
        }
    }
    function openload($data, $mirror)
    {
        $ClientID = $this->getProviderStatus($data, $mirror);
        if (is_null($ClientID)) {
            return null;
        } else {
            $openload = new \App\Classes\Openload();
            $copies  = \App\Mirrorcopy::where(['drive' => $data['link']])->where(['provider' => $mirror])->first();
            if (is_null($copies)) {
                if ($ClientID['status'] != "Up") {
                    return null;
                }
                $nameVideo = md5($data['link']) . "-" . $data['kualitas'];
                $driveId = $this->GetIdDrive($data['link']);
                $severDownload = $this->getProviderStatus($data, "ServerDownload");
                $urlDownload =  $severDownload['keys'] . "/" . $driveId . "/" . $nameVideo . ".mp4";
                $datacurl = $openload->getKey($this->getProviderStatus($data, $mirror), $mirror) . "&url=" . $urlDownload;
                $resultCurl = $openload->OpenloadUpload($datacurl);
                if ($resultCurl['msg'] != "OK") {
                    return "";
                }
                $mirrorcopies  = new \App\Mirrorcopy();
                $mirrorcopies->url = null;
                $mirrorcopies->status = "uploaded";
                $mirrorcopies->drive = $data['link'];
                $mirrorcopies->provider = $mirror;
                $mirrorcopies->apikey = $openload->getKey($this->getProviderStatus($data, $mirror), $mirror) . "&id=" . $resultCurl['id'];
                $mirrorcopies->save();
                return "";
            } else {
                $urlID = "";
                if ($copies['status'] == "uploaded") {
                    $checkResult = $openload->OpenloadStatus($copies['apikey']);
                    if ($checkResult['msg'] == "OK") {
                        foreach ($checkResult['result'] as $a => $b) {
                            if ($b['status'] == 'finished') {
                                $copies->url = $b['extid'];
                                $copies->status = "Task is completed";
                                $copies->save();
                                $keys = $openload->getKey($this->getProviderStatus($data, $mirror), $mirror) . "&id=" . $resultCurl['id'];
                                if ($copies['apikey'] == $keys) {
                                    $urlID = "http://oload.stream/f/" . $b['extid'];
                                }
                            }
                        }
                    }
                    return $urlID;
                }
                return "http://oload.stream/f/" . $copies['url'];
            }
        }
    }
    function googledrive($data, $mirror)
    {
        $mytime = \Carbon\Carbon::now();
        $ClientID = $this->getProviderStatus($data, $mirror);
        if (is_null($ClientID)) {
            return "";
        } else {
            $googledrive = new \App\Classes\GoogleDriveAPIS();
            $copies  = \App\Mirrorcopy::where(['drive' => $data['link']])->where(['provider' => $mirror])->first();
            if (!is_null($copies)) {
                return $this->GetPlayer($copies['url']);
            } else {
                if ($ClientID['status'] != "Up") {
                    return null;
                }
                $keys = $this->getProviderStatus($data, $mirror);
                $driveId = $this->GetIdDrive($data['link']);
                 $copyID = $googledrive->GDCopy($driveId, $keys );
               
                if (is_null($copyID) || isset($copyID['error'])) {
                    return "";
                };
                $mirrorcopies  = new \App\Mirrorcopy();
                $mirrorcopies->url = $copyID;
                $mirrorcopies->status = "Task is completed";
                $mirrorcopies->drive = $data['link'];
                $mirrorcopies->provider = $mirror;
                $mirrorcopies->apikey = $keys['keys'];
                $mirrorcopies->save();
                return $this->GetPlayer($copyID);
            }
        }
    }
   
    function GetPlayer($urlDrive)
    {
        $googledrive = new \App\Classes\GoogleDriveAPIS();
        return "http://localhost:8000/embed.php?id=".$urlDrive;
        //return $googledrive->viewsource("https://gd.nontonindrama.com/Player-Script/json.php?url=https://drive.google.com/open?id=" . $urlDrive);
    }
}
