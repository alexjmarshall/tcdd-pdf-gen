<?php
$numOfToCPages = 3;
$coursesPerPage = 4;
$courseCount = 0;
$page = 0;
?>
<!DOCTYPE html>
<html>
<head>
    <title>TEST</title>
    <style>
        a {
            text-decoration: none;
            color: #000;
            cursor: pointer;
        }
        .table-of-contents {
            width: 100%;
            margin-bottom: .25rem;
        }
        .table-of-contents tr td:last-child {
            text-align: right;
            width:1%;
            white-space:nowrap;
        }
        .table-of-contents tr td:nth-child(2) {
            border-bottom: 1px dotted #000;
        }
        .table-of-contents tr td:first-child {
            width:1%;
            white-space:nowrap;
        }
    </style>
</head>
<body>
    <h1>Course Catalog</h1>
    <h2>Contents</h2>
    <h3>Training Portal Courses</h3>
    @foreach ($moodleCourses as $category)
        @if(count($category->courses) > 0)
            <table class="table-of-contents">
                <tbody>
                    <tr>
                        <td style="text-align:left"><a href="#moodleCat-{{ $category->id }}">{{ $category->name }}</a></td>
                    </tr>
                </tbody>
            </table>
            @foreach ($category->courses as $course)
                <?php
                $courseCount++;
                ?>
                <table class="table-of-contents">
                    <tbody>
                        <tr>
                            <td><a href="#moodle-{{ $course->id }}">&nbsp;&nbsp;&nbsp;&nbsp;{{ $course->fullname }}</a></td>
                            <td></td>
                            <td><a href="#moodle-{{ $course->id }}">{{ $page = ceil($courseCount / $coursesPerPage) + $numOfToCPages }}</a></td>
                        </tr>
                    </tbody>
                </table>
            @endforeach
        @endif
    @endforeach

    <h3>COMET Courses</h3>
    @foreach ($cometCourses as $cometCourse)
        <table class="table-of-contents">
            <tbody>
                <tr>
                    <td><a href="#comet-{{ $cometCourse->id }}">{{ $cometCourse->shortTitle }}</a></td>
                    <td></td>
                    <td><a href="#comet-{{ $cometCourse->id }}">{{ ceil($loop->iteration / $coursesPerPage) + (!$page ? $numOfToCPages : $page) }}</a></td>
                </tr>
            </tbody>
        </table>
    @endforeach
    <?php 
    $courseCount = 0;
    ?>
    <p style="page-break-before: always"></p>

    <h2>Training Portal Courses</h2>
    @foreach ($moodleCourses as $category)
        @if(count($category->courses) > 0)
            <div id="moodleCat-{{ $category->id }}">
                <h3 style="margin-bottom: 0rem;">{{ $category->name }}</h3>
                @foreach($category->courses as $course)
                    <?php 
                    $courseCount++;
                    ?>
                    <div id="moodle-{{ $course->id }}">
                        <table style="width: 100%">
                            <tbody>
                                <tr>
                                    <td colspan="2"><h4 style="margin-bottom: .25rem;"><a href="http://msc-educ-smc.cmc.ec.gc.ca/moodle/course/view.php?id={{ $course->id }}">{{ $course->fullname }}</a></h4></td>
                                </tr>
                                <tr>
                                    @if($course->lastmodified > $course->timecreated)
                                        <td><strong>Last modified</strong>: {{ $course->lastmodified }}</td>
                                    @else
                                        <td><strong>Date published</strong>: {{ $course->timecreated }}</td>
                                    @endif
                                    <td style="text-align: right;"><strong>Estimated time to complete</strong>: {{ $course->estimatedtime }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2"><p style="margin-top: .25rem;"><strong>Description</strong>: {{ $course->description }}</p></td>
                                </tr>
                            </tbody>
                        </table>
                        @if($courseCount % $coursesPerPage !== 0 && !$loop->last)
                            <div style="margin: 1rem 0 1rem 0;">
                                <div style="margin: 0 auto; width: 30%; height: 1px; border-top: 1px solid #000;"></div>
                            </div>
                        @endif
                    </div>
                    @if ($courseCount % $coursesPerPage === 0)
                        <p style="page-break-before: always"></p>
                    @endif
                @endforeach
            </div>
        @endif
    @endforeach

    <p style="page-break-before: always"></p>

    <h2>COMET Courses</h2>
    @foreach ($cometCourses as $cometCourse)
        <div id="comet-{{ $cometCourse->id }}">
            <table style="width: 100%">
                <tbody>
                    <tr>
                        <td colspan="2"><h4 style="margin-bottom: .25rem;"><a href="{{ $cometCourse->URL }}">{{ $cometCourse->longTitle }}</a></h4></td>
                        </tr>
                    <tr>
                        @if($cometCourse->lastUpdated > $cometCourse->publishDate)
                            <td><strong>Last modified</strong>: {{ $cometCourse->lastUpdated }}</td>
                        @else
                            <td><strong>Date published</strong>: {{ $cometCourse->publishDate }}</td>
                        @endif
                        <td style="text-align: right;"><strong>Estimated time to complete</strong>: {{ $cometCourse->completionTime }}</td>
                    </tr>
                    <tr>
                        <td colspan="2"><p style="margin-top: .25rem;"><strong>Description</strong>: {!! $cometCourse->description !!}</p></td>
                    </tr>
                </tbody>
            </table>
            @if ($loop->iteration % $coursesPerPage !== 0 && !$loop->last)
                <div style="margin: 1rem 0 1rem 0;">
                    <div style="margin: 0 auto; width: 30%; height: 1px; border-top: 1px solid #000;"></div>
                </div>
            @endif
        </div>
        @if ($loop->iteration % $coursesPerPage === 0 && !$loop->last)
            <p style="page-break-before: always"></p>
        @endif
    @endforeach
</body>
</html>
