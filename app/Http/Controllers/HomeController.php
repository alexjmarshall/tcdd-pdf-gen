<?php

namespace App\Http\Controllers;

use App\CourseFormatter;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function generatePDF()
    {
        $lang = "en";

        $courseFormatter = new CourseFormatter();

        $moodleCourses = $this->getMoodleCourses();
        $formattedMoodleCourses = $courseFormatter->formatMoodleCourses($lang, $moodleCourses);

        $cometCourses = $this->getCometCourses($lang);
        $formattedCometCourses = $courseFormatter->formatCometCourses($lang, $cometCourses);

        $data = ['lang' => $lang,
            'moodleCourses' => $formattedMoodleCourses,
            'cometCourses' => $formattedCometCourses];

        if($lang === 'fr') {
            $pdf = \PDF::loadView('frenchPDF', $data);
        } else if($lang === 'en') {
            $pdf = \PDF::loadView('englishPDF', $data);
        }
  
        return $pdf->download('test.pdf');
    }

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
            return view('frenchPDF', $data);
        } else if($lang === 'en') {
            return view('englishPDF', $data);
        }
    }

    private function getMoodleCourses() {
        return collect(DB::connection('mysql')
            ->select("SELECT c.id, c.fullname, c.summary as 'keywords', c.summary as 'estimatedtime', c.summary as 'description', c.summary as 'objectives'
            FROM `mdl_badge` b
            INNER JOIN `mdl_course` c ON b.courseid = c.id
            WHERE b.id IN (44,45,8,22,11,12,27,28,34,31,43,42)
            AND c.category != 29
            AND c.visible != 0
            GROUP BY c.id"));
    }

    private function getCometCourses($lang) {
        if($lang === "fr") {
            return collect(DB::connection('mysql')->select("SELECT ct.id, ct.titleEn, ct.publishDateEn, ct.publishDateFr, ct.completionTime, ct.descriptionEn, ct.titleFr, ct.descriptionFr, ct.topics
            FROM `curltest`.`test` ct
            INNER JOIN `curltest`.`msc_comet` msc ON TRIM(ct.titleEn) = TRIM(msc.titleEn)
            WHERE ct.titleFr != ''"));
        } else if ($lang === "en") {
            return collect(DB::connection('mysql')->select("SELECT ct.id, ct.titleEn, ct.publishDateEn, ct.publishDateFr, ct.completionTime, ct.descriptionEn, ct.titleFr, ct.descriptionFr, ct.topics
            FROM `curltest`.`test` ct
            INNER JOIN `curltest`.`msc_comet` msc ON TRIM(ct.titleEn) = TRIM(msc.titleEn)"));
        }
    }
}
