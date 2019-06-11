<?php

namespace App\Http\Controllers;

use App\CourseFormatter;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
    * Generates and downloads pdf file of training portal and COMET courses.
    *
    * @return array response initiates download of pdf file
    *
    * @api
    */
    public function generatePDF()
    {
        $lang = "fr";

        $courseFormatter = new CourseFormatter();

        $moodleCourses = $this->getMoodleCourses();
        $formattedMoodleCourses = $courseFormatter->formatMoodleCourses($lang, $moodleCourses);
        $cometCourses = $this->getCometCourses($lang);
        $formattedCometCourses = $courseFormatter->formatCometCourses($lang, $cometCourses);

        $data = ['lang' => $lang,
            'moodleCourses' => $formattedMoodleCourses,
            'cometCourses' => $formattedCometCourses];

        if($lang === 'fr') {
            $pdf = \PDF::loadView('frenchCoursesByCategoryPDF', $data);
        } else if($lang === 'en') {
            $pdf = \PDF::loadView('englishCoursesByCategoryPDF', $data);
        }
  
        return $pdf->download('test.pdf');
        // $lang = "fr";

        // $courseFormatter = new CourseFormatter();

        // $moodleCourses = $this->getMoodleCourses();
        // $formattedMoodleCourses = $courseFormatter->formatMoodleCourses($lang, $moodleCourses);
        // $cometCourses = $this->getCometCourses($lang);
        // $formattedCometCourses = $courseFormatter->formatCometCourses($lang, $cometCourses);

        // $data = ['lang' => $lang,
        //     'moodleCourses' => $formattedMoodleCourses,
        //     'cometCourses' => $formattedCometCourses];

        // if($lang === 'fr') {
        //     $pdf = \PDF::loadView('frenchPDF', $data);
        // } else if($lang === 'en') {
        //     $pdf = \PDF::loadView('englishPDF', $data);
        // }
  
        // return $pdf->download('test.pdf');
    }

    /**
    * Generates HTML view of training portal and COMET courses.
    *
    * @return array response includes HTML view with course data passed to it
    *
    * @api
    */
    public function pdfview()
    {
        $lang = "fr";

        $courseFormatter = new CourseFormatter();

        $moodleCourses = $this->getMoodleCourses();
        $formattedMoodleCourses = $courseFormatter->formatMoodleCourses($lang, $moodleCourses);
        
        $cometCourses = $this->getCometCourses($lang);
        $formattedCometCourses = $courseFormatter->formatCometCourses($lang, $cometCourses);

        $data = ['lang' => $lang,
            'moodleCourses' => $formattedMoodleCourses,
            'cometCourses' => $formattedCometCourses];

        if($lang === 'fr') {
            return view('frenchCoursesByCategoryPDF', $data);
        } else if($lang === 'en') {
            return view('englishCoursesByCategoryPDF', $data);
        }

        // $lang = "en";

        // $courseFormatter = new CourseFormatter();

        // $moodleCourses = $this->getMoodleCourses();
        // $formattedMoodleCourses = $courseFormatter->formatMoodleCourses($lang, $moodleCourses);
        // $cometCourses = $this->getCometCourses($lang);
        // $formattedCometCourses = $courseFormatter->formatCometCourses($lang, $cometCourses);

        // $data = ['lang' => $lang,
        //     'moodleCourses' => $formattedMoodleCourses,
        //     'cometCourses' => $formattedCometCourses];

        // if($lang === 'fr') {
        //     return view('frenchPDF', $data);
        // } else if($lang === 'en') {
        //     return view('englishPDF', $data);
        // }
    }

    /**
    * Returns training portal course information from database.
    *
    * Query returns courses that issue a completion badge, are visible, and are not archived
    *
    * @return array response includes array of training portal courses
    *
    * @api
    */
    private function getMoodleCourses() {
        $moodleCourseCategories = collect(DB::connection('mysql')
            ->select("SELECT c.id, c.name
            FROM `mdl_course_categories` c
            WHERE c.id NOT IN (10, 28, 29)
            ORDER BY c.name desc"));

        

        $moodleCoursesByCategory = $moodleCourseCategories->map(function ($category) {
            $categoryId = $category->id;
            $category = (array)$category;
            $category['courses'] = collect(DB::connection('mysql')
                ->select("SELECT c.id, ca.id as 'category', c.fullname, c.summary as 'keywords', c.summary as 'estimatedtime', c.timecreated as 'timecreated', max(cm.added) as 'lastmodified', c.summary as 'description', c.summary as 'objectives'
                FROM `mdl_course` c
                INNER JOIN `mdl_course_modules` cm on c.id = cm.course
                INNER JOIN `mdl_course_categories` ca on c.category = ca.id
                INNER JOIN `mdl_badge` b on b.courseid = c.id
                WHERE c.category = {$categoryId}
                AND c.visible != 0
                AND b.id IN (44,45,8,22,11,12,27,28,34,31,43,42)
                GROUP BY c.id"));
            $category = (object)$category;

            return $category;
        });

        return $moodleCoursesByCategory;
        // $moodleCourses = Cache::rememberForever('moodleCourses', function () {
        //     return collect(DB::connection('mysql')
        //     ->select("SELECT c.id, c.fullname, c.summary as 'keywords', c.summary as 'estimatedtime', c.timecreated as 'timecreated', max(cm.added) as 'lastmodified', c.summary as 'description', c.summary as 'objectives'
        //     FROM `mdl_badge` b
        //     INNER JOIN `mdl_course` c ON b.courseid = c.id
        //     INNER JOIN `mdl_course_modules` cm on c.id = cm.course 
        //     WHERE b.id IN (44,45,8,22,11,12,27,28,34,31,43,42)
        //     AND c.category != 29
        //     AND c.visible != 0
        //     GROUP BY c.id"));
        // });
        // return $moodleCourses;
    }

    /**
    * Returns COMET course information from database.
    *
    * Query returns courses that are funded by MSC
    *
    * @param string $lang is the language of the courses (English or French)
    *
    * @return array response includes array of MSC-funded COMET courses
    *
    * @api
    */
    private function getCometCourses($lang) {
        if($lang === "fr") {
            $cometCourses = Cache::rememberForever('cometCoursesFr', function () {
                return collect(DB::connection('mysql')->select("SELECT ct.id, ct.title as 'longTitle', ct.title as 'shortTitle', ct.publish_date as 'publishDate', ct. last_updated as 'lastUpdated', ct.completion_time as 'completionTime', ct.description as 'description', ct.topics, ct.url as 'URL'
                FROM `curltest`.`comet_modules` ct
                WHERE ct.include_in_catalog = TRUE
                AND ct.language = 'french'
                ORDER BY ct.title"));
            });
        } else if ($lang === "en") {
            $cometCourses = Cache::rememberForever('cometCoursesEn', function () {
                return collect(DB::connection('mysql')->select("SELECT ct.id, ct.title as 'longTitle', ct.title as 'shortTitle', ct.publish_date as 'publishDate', ct. last_updated as 'lastUpdated', ct.completion_time as 'completionTime', ct.description as 'description', ct.topics, ct.url as 'URL'
                FROM `curltest`.`comet_modules` ct
                WHERE ct.include_in_catalog = TRUE
                AND ct.language = 'english'
                ORDER BY ct.title"));
            });
        }
        return $cometCourses;
    }
}
