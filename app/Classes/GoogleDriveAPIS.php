<?php

namespace App\Classes;

use Cache;

use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
use Guzzle\Http\Exception\ClientErrorResponseException;

class GoogleDriveAPIS
{
    public function refresh_token($token, $apiUrl)
    {
        $tokenencode = urlencode($token);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://www.googleapis.com/oauth2/v4/token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_SSL_VERIFYHOST => FALSE,
            CURLOPT_SSL_VERIFYPEER => FALSE,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "$apiUrl&refresh_token=$tokenencode&grant_type=refresh_token",
            CURLOPT_HTTPHEADER => array(
                "Cache-Control: no-cache",
                "Content-Type: application/x-www-form-urlencoded",
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return null;
        } else {
            return $response;
        }
    }
    function get_token($tokens)
    {
        $keys = explode(":::", $tokens['keys']);
        $tokenmd5=md5($tokens['keys']);
        try {
            if (!Cache::has($tokenmd5)) {
                $keys = explode(":::", $tokens['keys']);
                $result_curl23 = $this->refresh_token($keys[1], $keys[3]);
                if ($result_curl23) {
                    $checklinkerror = json_decode($result_curl23, true);
                    if (isset($checklinkerror['access_token'])) {
                        $get_info23 = "Bearer " . $checklinkerror['access_token'];
                        Cache::put($tokens, $get_info23, now()->addMinutes(50));
                        return $get_info23;
                    } else {
                        return "Bearer ";
                    }
                } else {
                    return null;
                }
            } else {
                return Cache::get($tokenmd5);
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function copygd($driveId, $title, $token)
    {

        $keys = explode(":::", $token['keys']);
        $curl = curl_init();
        $folderid= $keys[2];
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://www.googleapis.com/drive/v3/files/$driveId/copy",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_SSL_VERIFYHOST => FALSE,
            CURLOPT_SSL_VERIFYPEER => FALSE,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\"name\":\"$title\",\"parents\":[\"$folderid\"]}",
            CURLOPT_HTTPHEADER => array(
                "Authorization: " . $this->get_token($token),
                "Cache-Control: no-cache",
                "Content-Type: application/json",
                "Accept: application/json",
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return null;
        } else {
            return json_decode($response,true);
        }
    }
    public function deletegd($id, $token)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://www.googleapis.com/drive/v3/files/$id",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_SSL_VERIFYHOST => FALSE,
            CURLOPT_SSL_VERIFYPEER => FALSE,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "DELETE",
            CURLOPT_HTTPHEADER => array(
                "Authorization: " . $this->get_token($token),
                "Cache-Control: no-cache",
                "Content-Type: application/json",
                "Accept: application/json",
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return false;
        } else {
            $this->emptytrash($token);
            return true;
        }
    }
    public function emptytrash($token)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://www.googleapis.com/drive/v3/files/trash",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_SSL_VERIFYHOST => FALSE,
            CURLOPT_SSL_VERIFYPEER => FALSE,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "DELETE",
            CURLOPT_HTTPHEADER => array(
                "Authorization: " . $this->get_token($token),
                "Cache-Control: no-cache",
                "Content-Type: application/json",
                "Accept: application/json",
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
    function GDCopy($driveId, $dataKey)
    {
        try {
            $copyid = $this->copygd($driveId,  md5($driveId), $dataKey);
            if (isset($copyid['id'])) {
                return $copyid['id'];
            } else {
                return null;
            }
        } catch (Exception $e) {
            return null;
        }
    }
    function GDMoveFolder($id, $uploadfolder)
    {
        $settingData = Setting::find(1);
        $oldFolder = $settingData->folderUpload;
        $tokenAdmin =  $settingData->tokenDriveAdmin;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.googleapis.com/drive/v3/files/' . $id . '?addParents=' . $uploadfolder . '&removeParents=' . $oldFolder);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{}");
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        $headers = array();
        $headers[] = 'Authorization: ' . $this->get_token($tokenAdmin);
        $headers[] = 'Accept: application/json';
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
    }
    function GDCreateFolder($title)
    {
        $settingData = Setting::find(1);
        $folderid = $settingData->folder720p;
        $tokenAdmin =  $settingData->tokenDriveAdmin;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.googleapis.com/drive/v3/files');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"name\": \"$title\",\"parents\": [\"$folderid\"],\"mimeType\": \"application/vnd.google-apps.folder\"}");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        $headers = array();
        $headers[] = 'Authorization: ' . $this->get_token($tokenAdmin);
        $headers[] = 'Accept: application/json';
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        $response = json_decode($result, true);
        return $response;
    }
    function GetIdDriveTrashed($urlVideoDrive)
    {
        if (preg_match('@https?://(?:[\w\-]+\.)*(?:drive|docs)\.google\.com/(?:(?:folderview|open|uc)\?(?:[\w\-\%]+=[\w\-\%]*&)*id=|(?:folder|file|document|presentation)/d/|spreadsheet/ccc\?(?:[\w\-\%]+=[\w\-\%]*&)*key=)([\w\-]{28,})@i', $urlVideoDrive, $id)) {
            return $id[1];
        } else {
            return false;
        }
    }
    function AutoDeleteGd()
    {
        $datass = Trash::take(10)->get();
        if ($datass) {
            foreach ($datass as $datass) {
                $idcopy = $datass->idcopy;
                $tokens = $datass->token;
                if (!is_null($idcopy) && !is_null($tokens)) {
                    if ($this->deletegd($this->GetIdDriveTrashed($idcopy), $tokens)) {
                        $datass->delete();
                    }
                } else {
                    $datass->delete();
                }
            }
        }
        return true;
    }
    function AutoBackupDrive()
    {
        $seconds = 1000 * 60 * 15;
        $value = Cache::remember('backupgd', $seconds, function () {
            $settingData = Setting::find(1);
            $this->AutoDeleteGd();
            $dataContent =  DB::table('contents')
                ->whereNotIn('url', DB::table('backups')->whereNotNull('f720p')->pluck('url'))
                ->where('f720p', 'NOT LIKE', '%picasa%')
                ->whereNotNull('f720p')
                ->inRandomOrder()
                ->take(5)
                ->get();
            foreach ($dataContent as $dataContent) {
                $f20p = $this->CheckHeaderCode($dataContent->f720p);
                if ($f20p) {
                    $content = array('url' => $dataContent->url, 'title' => $dataContent->title);
                    $datass = BackupFilesDrive::firstOrCreate($content);
                    $copyID = $this->copygd($this->GetIdDriveTrashed($dataContent->f720p), $settingData->folderbackup, $dataContent->url, $settingData->tokenDriveAdmin);
                    if (isset($copyID['id'])) {
                        //$datass = Content::where('title', $dataContents->title);
                        $datass->f720p = $copyID['id'];
                        $datass->save();
                    }
                } else {
                    $content = Content::find($dataContent->id);
                    $content->f720p = null;
                    $content->save();
                }
            }
        });
        return true;
    }
    function checkFilesDrive($id)
    {

        $curl = $this->viewsource("https://www.googleapis.com/drive/v2/files/" . $id . "?key=AIzaSyARh3GYAD7zg3BFkGzuoqypfrjtt3bJH7M&supportsTeamDrives=true");

        $data =  json_decode($curl, true);
        if (isset($data["shared"])) {
            return ($data["shared"] == 200) ? true : false;
        } else {
            return false;
        }
    }
    function getHeaderCode($url)
    {

        $url = 'https://drive.google.com/file/d/' . $url . '/view';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, true);    // we want headers
        curl_setopt($ch, CURLOPT_NOBODY, true);    // we don't need body
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 100);
        $output = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $httpcode == 200 ? true : false;
    }
    function viewsource($url)
    {
        $ch = @curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        $head[] = "Connection: keep-alive";
        $head[] = "Keep-Alive: 300";
        $head[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
        $head[] = "Accept-Language: en-us,en;q=0.5";
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.124 Safari/537.36');
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
        curl_setopt($ch, CURLOPT_REFERER, 'http://dldramaid.xyz/');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        $page = curl_exec($ch);
        curl_close($ch);
        return $page;
    }
}
