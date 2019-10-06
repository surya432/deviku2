<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Classes\SmmFunction;

class EmbedController extends Controller
{
    //
    public function getEmbed($url)
    {

        $data = \App\Content::where(['url' => $url])->with('links')->first();
        if (is_null($data)) {
            abort(404);
        }
        return view('embed.index', compact('data'));
    }
    function addToTrashes()
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
    function getPlayer(Request $request)
    {
        $this->addToTrashes();   
        $smmFunction = new SmmFunction();
        $data = \App\MetaLink::join('contents', "contents.id", "=", "meta_links.content_id")->where(['meta_links.id' => $request->input("player")])->where(['contents.url' => $request->input("videos")])->first();       
        return view('embed.link', [
            'url_fembed'   =>  $smmFunction->getMirror($data, "fembed.com"),
            'url_rapidvideo'  => $smmFunction->getMirror($data, "rapidvideo.com"),
            //'url_openload' =>  $smmFunction->getMirror($data, "openload.com"),
            'url_googledrive' =>  $smmFunction->getMirror($data, "drive.google.com"),
        ]);
    }
}
