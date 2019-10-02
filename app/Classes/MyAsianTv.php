<?php

namespace App\Classes;

use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
use Guzzle\Http\Exception\ClientErrorResponseException;

class MyAsianTv
{
    function getDetailDrama($url)
    {
        try {
            $client = new Client();
            $guzzleclient = new GuzzleClient([
                'timeout' => 300,
                'verify' => false,
            ]);
            $client->setClient($guzzleclient);
            $crawler = $client->request('GET', $url);
            $getCrawler =[];
            //  $crawler->filter('.left >p:nth-of-type(1)')->each(function ($node){
            // $getCrawler =  $crawler->filter('.left >p')->each(function ($node) {
            //     return array(strtolower($node->filter('strong')->text())  => $node->filter('span')->text());
            // });
            $getCrawler['name'] =   $crawler->filter('.left >p>span')->eq(0)->text();
            $getCrawler['original'] =   $crawler->filter('.left >p>span')->eq(1)->text();
            $getCrawler['director'] =   $crawler->filter('.left >p>span')->eq(2)->text();
            $getCrawler['country'] =   $crawler->filter('.left >p>span')->eq(7)->text();
            $getCrawler['years'] =   $crawler->filter('.left >p>span')->eq(4)->text();
            $getCrawler['genre'] =   $crawler->filter('.left >p>span')->eq(9)->text();
            $getCrawler['actor'] =   $crawler->filter('.left >p>span')->eq(3)->text();
            $getCrawler['plot'] =   $crawler->filter('.info')->filter('p')->text();
            $getCrawler['sora_imdb'] =   $crawler->filter('.right>div')->filterXPath('//span[@itemprop="average"]')->text();
            $getCrawler['sora_cover'] = $crawler->filterXPath('//img[@class="poster"]')->attr('src');
            $getCrawler['sora_trailer'] =  $crawler->filterXPath('//iframe[@webkitallowfullscreen="true"]')->attr('src');
            return  $getCrawler;
        } catch (ClientErrorResponseException $exception) {
            return $exception->getResponse()->getBody(true);
        }
    }
}
