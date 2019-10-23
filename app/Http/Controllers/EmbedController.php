<?php

namespace App\Http\Controllers;

use App\Classes\SmmFunction;
use GeoIP;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class EmbedController extends Controller
{
    //
    public function getEmbed($url)
    {

        $data = \App\Content::where(['url' => $url])->with('links')->first();
        if (is_null($data)) {
            abort(404);
        }

        $blockCountry = \App\mirrorkey::join('master_mirrors', 'master_mirrors.id', '=', 'mirrorkeys.master_mirror_id')->where(['mirrorkeys.cmp_id' => $data->cmp_id])->where(['master_mirrors.name' => "blockCountry"])->inRandomOrder()->first();
        if (!is_null($blockCountry)) {
            $location = GeoIP::getLocation();
            $agent = new Agent();
            $country = $location->iso_code;
            if (in_array($country, explode(',', $blockCountry->keys)) || $country == "US" && !$agent->isMobile() || $country == "US" && !$agent->isTablet()) {
                abort(404);
            }
        }
        //return \Request::ip();

        return view('embed.index', compact('data'));
    }
    public function addToTrashes()
    {
        $mytime = \Carbon\Carbon::now();
        $dt = $mytime->subDays(3);
        $datas = \App\Mirrorcopy::where("created_at", '<=', date_format($dt, "Y/m/d H:i:s"))->where('provider', "drive.google.com")->take(20)->get();
        if ($datas) {
            foreach ($datas as $datass) {
                $trashes = new \App\Trash();
                $trashes->idcopy = $datass->url;
                $trashes->keys = $datass->apikey;
                $trashes->save();
                \App\Mirrorcopy::where('url', $datass->url)->delete();
            }
        }
    }
    public function getPlayer(Request $request)
    {
        $this->addToTrashes();
        $smmFunction = new SmmFunction();
        $data = \App\MetaLink::join('contents', "contents.id", "=", "meta_links.content_id")->where(['meta_links.id' => $request->input("player")])->where(['contents.url' => $request->input("videos")])->first();
        return view('embed.link', [
            'url_fembed' => $smmFunction->getMirror($data, "fembed.com"),
            'url_rapidvideo' => $smmFunction->getMirror($data, "rapidvideo.com"),
            //'url_openload' =>  $smmFunction->getMirror($data, "openload.com"),
            'url_googledrive' => $smmFunction->getMirror($data, "drive.google.com"),
        ]);
    }
}
