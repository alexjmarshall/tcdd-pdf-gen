<?php

namespace App;

class CourseFormatter {
    public function formatMoodleCourses($lang, $collection) {
        if($lang === 'fr') { //abstract into method
            $frenchFormattedCollection = $collection->each(function ($row) {
                $summaryArr = preg_split("/<p>{mlang} /", $row->keywords);
                $frenchSummary = $summaryArr[1];
                
                $original = $row->fullname;
                $row->fullname = trim(preg_replace("/<span lang=\"en\" class=\"multilang\">[\s\S]*<\/span> <span lang=\"fr\" class=\"multilang\">|<\/span>/", "", $row->fullname));
                if($original === $row->fullname) { //only run the second preg_replace if the first did nothing
                    $row->fullname = trim(preg_replace("/{mlang en}[\s\S]*{mlang}[\s\S]*{mlang fr}|{mlang}[\s\S]*/", "", $row->fullname));
                }

                $row->keywords = preg_replace('~[\s\S]*Mots-clés</span>:\s?<span class="value">|</span></div>\s*<div id="estimatedtime">[\s\S]*~', "", $frenchSummary);
                $row->estimatedtime = preg_replace('~[\s\S]*urée estimée</span>:\s?<span class="value">|</span></div>\s*<div id="objectives">[\s\S]*~', "", $frenchSummary);
                $row->description = preg_replace('~[\s\S]*<div id="description-content">|</div>[\s\S]*~', "", $frenchSummary);
                $row->description = $this->truncate($row->description);
                $row->objectives = preg_replace('~[\s\S]*<div id="objectives-content">|</div>\s*<div id="description">[\s\S]*~', "", $frenchSummary);
            });
            return $frenchFormattedCollection;
        }
        else if($lang === 'en') {
            $englishFormattedCollection = $collection->each(function ($row) {
                $summaryArr = preg_split("/<p>{mlang} /", $row->keywords);
                $englishSummary = $summaryArr[0];
                
                $original = $row->fullname;
                $row->fullname = trim(preg_replace("/<span lang=\"en\" class=\"multilang\">|<\/span> <span lang=\"fr\" class=\"multilang\">(.*)<\/span>/", "", $row->fullname));
                if($original === $row->fullname) { // only run the second preg_replace if the first did nothing
                    $row->fullname = trim(preg_replace("/{mlang en}|{mlang}{mlang fr}(.*){mlang}|{mlang} {mlang fr}(.*){mlang}/", "", $row->fullname));
                }

                $row->keywords = preg_replace('~[\s\S]*eywords</span>:\s?<span class="value">|</span></div>\s*<div id="estimatedtime">[\s\S]*~', "", $englishSummary);
                $row->estimatedtime = preg_replace('~[\s\S]*stimated time to complete</span>:\s?<span class="value">|</span></div>\s*<div id="objectives">[\s\S]*~', "", $englishSummary);
                $row->description = preg_replace('~[\s\S]*<div id="description-content">|</div>[\s\S]*~', "", $englishSummary);
                $row->description = $this->truncate($row->description);
                $row->objectives = preg_replace('~[\s\S]*<div id="objectives-content">|</div>\s*<div id="description">[\s\S]*~', "", $englishSummary);
            });
            return $englishFormattedCollection;
        }
    }

    public function formatCometCourses($lang, $collection) {
        $formattedCollection = $collection->each(function ($row) use ($lang) {
            $row->completionTime = preg_replace("~h~", "", $row->completionTime);
            $plusFlag = false;

            if(preg_match("~\+~", $row->completionTime)) {
                preg_replace("~\+~", "", $row->completionTime);
                $plusFlag = true;
            }
            $row->completionTime = rtrim($row->completionTime);

            $timeArr = preg_split("~ - ~", $row->completionTime);
            $minTime = $this->getCompletionTime($lang, $timeArr[0]);
            $row->completionTime = $minTime;

            if(count($timeArr) > 1) {
                $maxTime = $this->getCompletionTime($lang, $timeArr[1]);
                if($lang === 'fr') {
                    $row->completionTime = $row->completionTime . " &agrave; " . $maxTime;
                } else if($lang === 'en') {
                    $row->completionTime = $row->completionTime . " - " . $maxTime;
                }
            }
            $row->completionTime = rtrim($row->completionTime);
            if($plusFlag) {
                $row->completionTime = $row->completionTime . "+";
            }
            $row->descriptionEn = $this->truncate($row->descriptionEn);
            $row->descriptionFr = $this->truncate($row->descriptionFr);

            $row->topics = $this->truncate($row->topics, 70);
        });
        return $formattedCollection;
    }

    private function getCompletionTime($lang, $completionTimeStr) {
        $hours = '';
        $minutes = '';

        $completionTimeStrArr = preg_split("~\.~", $completionTimeStr);
        if(count($completionTimeStrArr) > 1) {
            $completionTimeStrArr[1] = rtrim($completionTimeStrArr[1], "0");
        }

        if($lang === 'fr') {
            if($completionTimeStrArr[0] !== "0" && $completionTimeStrArr[0] !== "") {
                $hours = $completionTimeStrArr[0];
                $minutes = "." . (count($completionTimeStrArr) > 1 ? $completionTimeStrArr[1] : "");
                $completionTimeStr = $hours . " h ";
            } else {
                $minutes = $completionTimeStr;
                $completionTimeStr = "";
            }
            if($minutes > 0) {
                $minutes = (float) $minutes;
                $minutes = (int) floor($minutes * 60);
                if($completionTimeStrArr[0] !== "0" && $completionTimeStrArr[0] !== "") {
                    $completionTimeStr = $completionTimeStr . $minutes;
                } else {
                    $completionTimeStr = $completionTimeStr . $minutes . " minutes";
                }
            }
            if($completionTimeStr === '') {
                $completionTimeStr = "0";
            }
            return $completionTimeStr;
        } else if ($lang === 'en'){
            if($completionTimeStrArr[0] !== "0" && $completionTimeStrArr[0] !== "") {
                $hours = $completionTimeStrArr[0];
                $minutes = "." . (count($completionTimeStrArr) > 1 ? $completionTimeStrArr[1] : "");
                $completionTimeStr = $hours . "h ";
            } else {
                $minutes = $completionTimeStr;
                $completionTimeStr = "";
            }
            if($minutes > 0) {
                $minutes = (float) $minutes;
                $minutes = (int) floor($minutes * 60);
                $completionTimeStr = $completionTimeStr . $minutes . "m";
            }
            if($completionTimeStr === '') {
                $completionTimeStr = "0";
            }
            return $completionTimeStr;
        }
    }

    private function truncate($string, $length=250, $append="...") {
        $string = trim($string);
      
        if(strlen($string) > $length) {
          $string = wordwrap($string, $length);
          $string = explode("\n", $string, 2);
          $string = $string[0] . $append;
        }
        return $string;
    }
}