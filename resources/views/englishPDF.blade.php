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
    @foreach ($moodleCourses as $moodleCourse)
        <table class="table-of-contents">
            <tbody>
                <tr>
                    <td><a href="#moodle-{{ $moodleCourse->id }}">{{ $moodleCourse->fullname }}</a></td>
                    <td></td>
                    <td><a href="#moodle-{{ $moodleCourse->id }}">{{ $pageCount = ceil($loop->iteration / 4) + 3 }}</a></td>
                </tr>
            </tbody>
        </table>
    @endforeach

    <h3>COMET Courses</h3>
    @foreach ($cometCourses as $cometCourse)
        <table class="table-of-contents">
            <tbody>
                <tr>
                    <td><a href="#comet-{{ $cometCourse->id }}">{{ $cometCourse->shortTitleEn }}</a></td>
                    <td></td>
                    <td><a href="#comet-{{ $cometCourse->id }}">{{ ceil($loop->iteration / 4) + $pageCount }}</a></td>
                </tr>
            </tbody>
        </table>
    @endforeach
    <p style="page-break-before: always"></p>

    <h2>Training Portal Courses</h2>
    @foreach ($moodleCourses as $moodleCourse)
    <div id="moodle-{{ $moodleCourse->id }}">
        <h3><a href="http://msc-educ-smc.cmc.ec.gc.ca/moodle/course/view.php?id={{ $moodleCourse->id }}">{{ $moodleCourse->fullname }}</a></h3>
        <table style="width: 100%">
            <tbody>
                <tr>
                    @if($moodleCourse->lastmodified > $moodleCourse->timecreated)
                        <td><strong>Last modified</strong>: {{ $moodleCourse->lastmodified }}</td>
                    @else
                        <td><strong>Date published</strong>: {{ $moodleCourse->timecreated }}</td>
                    @endif
                    <td style="text-align: right;"><strong>Estimated time to complete</strong>: {{ $moodleCourse->estimatedtime }}</td>
                </tr>
                <tr>
                    <td colspan="2"><p><strong>Description</strong>: {{ $moodleCourse->description }}</p></td>
                </tr>
            </tbody>
        </table>

        @if ($loop->iteration % 4 !== 0 && !$loop->last)
            <div style="margin: 1rem 0 1rem 0;">
                <div style="margin: 0 auto; width: 30%; height: 1px; border-top: 1px solid #000;"></div>
            </div>
        @endif
    </div>
    @if ($loop->iteration % 4 === 0)
    <p style="page-break-before: always"></p>
    @endif
    @endforeach

    <p style="page-break-before: always"></p>

    <h2>COMET Courses</h2>
    @foreach ($cometCourses as $cometCourse)
    <div id="comet-{{ $cometCourse->id }}">
    <h3><a href="{{ $cometCourse->enURL }}">{{ $cometCourse->longTitleEn }}</a></h3>
        <table style="width: 100%">
            <tbody>
                <tr>
                    @if($cometCourse->lastUpdatedEn > $cometCourse->publishDateEn)
                        <td><strong>Last modified</strong>: {{ $cometCourse->lastUpdatedEn }}</td>
                    @else
                        <td><strong>Date published</strong>: {{ $cometCourse->publishDateEn }}</td>
                    @endif
                    <td style="text-align: right;"><strong>Estimated time to complete</strong>: {{ $cometCourse->completionTime }}</td>
                </tr>
                <tr>
                    <td colspan="2"><p><strong>Description</strong>: {!! $cometCourse->descriptionEn !!}</p></td>
                </tr>
            </tbody>
        </table>
        @if ($loop->iteration % 4 !== 0 && !$loop->last)
            <div style="margin: 1rem 0 1rem 0;">
                <div style="margin: 0 auto; width: 30%; height: 1px; border-top: 1px solid #000;"></div>
            </div>
        @endif
    </div>
    @if ($loop->iteration % 4 === 0 && !$loop->last)
    <p style="page-break-before: always"></p>
    @endif
    @endforeach
</body>
</html>
