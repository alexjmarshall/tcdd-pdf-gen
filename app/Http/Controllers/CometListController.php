<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CometListController extends Controller
{
    function index () {
        // return all COMET courses (just english for now) that are not already in blacklist
        $englishCometCourses = collect(DB::connection('mysql2')
            ->select("SELECT *
            FROM `curltest`.`comet_english` ce
            INNER JOIN `mdl_course_modules` cm on c.id = cm.course
            INNER JOIN `mdl_course_categories` ca on c.category = ca.id
            INNER JOIN `mdl_badge` b on b.courseid = c.id
            WHERE c.category = {$categoryId}
            AND c.visible != 0
            AND b.id IN (44,45,8,22,11,12,27,28,34,31,43,42)
            GROUP BY c.id"));
    }

    function store () {
        // store new blacklist
    }
}
