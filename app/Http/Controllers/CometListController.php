<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CometListController extends Controller
{
    function index () {
        // return all COMET courses that are not already in blacklist
        $collection = collect(DB::connection('mysql2')
            ->select("SELECT *
            FROM `curltest`.`comet_modules`
            ORDER BY title"));

        return $collection;
        
        // ->each(function ($row) {
        //     $this->truncate($row->)
        // })
    }

    function update () {
        // store new blacklist
        $data = request()->all();
        foreach($data as $row) {
            DB::connection('mysql2')->table('curltest.comet_modules')
            ->where('id', $row['id'])
            ->update([
                // 'title' => $row['title'],
                // 'publish_date' => $row['publish_date'],
                // 'last_updated' => $row['last_updated'],
                // 'completion_time' => $row['completion_time'],
                // 'image_src' => $row['image_src'],
                // 'description' => $row['description'],
                // 'topics' => $row['topics'],
                // 'url' => $row['url'],
                'include_in_catalog' => $row['include_in_catalog'],
                //'language' => $row['language']
            ]);
        }
        return response('Successfully updated COMET module blacklist.', 200);
    }

    private function truncate($string, $length=250, $append="...") {
        $string = trim($string);
        $string = preg_replace("~\n~", " ", $string);
      
        if(strlen($string) > $length) {
          $string = wordwrap($string, $length);
          $string = explode("\n", $string, 2);
          $string = rtrim($string[0], " ,") . $append;
        }
        return $string;
    }
}
