<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Goutte;

class ItemController extends Controller
{
    public function index()
    {

        $saunaInfo = $this->getPageData();

        return view('Item.index', compact('saunaInfo'));
    }

    public function downloadSaunaInfo()
    {
        $saunaInfo = $this->getPageData();

        $header = array(
            '施設名',
            '都道府県',
            'URL'
        );

        header('Content-Type: application/octet-stream');
        header("Content-Disposition: attachment; filename=" . time() . ".csv");

        mb_convert_variables('SJIS', 'UTF-8', $header);
        mb_convert_variables('SJIS', 'UTF-8', $saunaInfo);

        $stream = fopen('php://output', 'w');

        fputcsv($stream, $header);

        foreach ($saunaInfo as $val) {
            fputcsv($stream, $val);
        }
    }

    public function getPageData(){
        $facilityArray = array();
        $prefectureArray = array();
        $prefectureUrlArray = array();
        $url = 'https://sauna-ikitai.com/posts?page=';
        $page = 2;
        for ($i = 1; $i <= $page; $i++) {
            sleep(1);
            $crawler = Goutte::request('GET', $url . $i);

            //施設名
            $crawler->filter('.p-postCard_facility a')->each(function ($node) use (&$facilityArray) {
                $facilityArray[] = $node->text();
            });

            //施設URL
            $crawler->filter('.p-postCard_facility a')->each(function ($node) use (&$prefectureUrlArray) {
                $prefectureUrlArray[] = $node->attr("href");
            });

            //都道府県
            $crawler->filter('.p-postCard_facility p')->each(function ($node) use (&$prefectureArray) {
                $prefectureArray[] = $node->text();
            });
        }

        $saunaInfo = array();

        for ($i = 0; $i < count($facilityArray); $i++) {
            if ($facilityArray[$i] === "" && $prefectureArray[$i] === "") {
                continue;
            }
            $saunaInfo[] = [$facilityArray[$i], $prefectureArray[$i], $prefectureUrlArray[$i]];
        }

        return $saunaInfo;
    }
}
