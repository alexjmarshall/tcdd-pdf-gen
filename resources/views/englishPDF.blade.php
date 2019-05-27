<!DOCTYPE html>
<html>
<head>
    <title>TEST</title>
</head>
<body>
    <h1>Course Catalog</h1>

    <h2>Contents</h2>
    <h3>Training Portal Courses</h3>
    @foreach ($moodleCourses as $moodleCourse)
        {{ $moodleCourse->fullname }} <a href="#moodle-{{ $moodleCourse->id }}">{{ $pageCount = ceil($loop->iteration / 3) + 2 }}</a><br />
    @endforeach
    <h3>COMET Courses</h3>
    @foreach ($cometCourses as $cometCourse)
        {{ $cometCourse->titleEn }} <a href="#comet-{{ $cometCourse->id }}">{{ ceil($loop->iteration / 3) + $pageCount }}</a><br />
    @endforeach
    <p style="page-break-before: always"></p>
    <h2>Training Portal Courses</h2>
    @foreach ($moodleCourses as $moodleCourse)
    <div id="moodle-{{ $moodleCourse->id }}">
        <h3>{{ $moodleCourse->fullname }}</h3>
        <ul>
            <p><strong>Publish date</strong>:</p>
            <p><strong>Estimated time to complete</strong>: {{ $moodleCourse->estimatedtime }}</p>
            <p><strong>Description</strong>: {{ $moodleCourse->description }}</p>
            <p><strong>Keywords</strong>: {{ $moodleCourse->keywords }}</p>
        </ul>
    </div>
    @if ($loop->iteration % 3 === 0)
    <p style="page-break-before: always"></p>
    @endif
    @endforeach

    <p style="page-break-before: always"></p>

    <h2>COMET Courses</h2>
    @foreach ($cometCourses as $cometCourse)
    <div id="comet-{{ $cometCourse->id }}">
        <h3>{{ $cometCourse->titleEn }}</h3>
        <ul>
            <p><strong>Publish date</strong>: {{ $cometCourse->publishDateEn }}</p>
            <p><strong>Estimated time to complete</strong>: {{ $cometCourse->completionTime }}</p>
            <p><strong>Description</strong>: {!! $cometCourse->descriptionEn !!}</p>
            <p><strong>Topics</strong>: {{ $cometCourse->topics }}</p>
        </ul>
    </div>
    @if ($loop->iteration % 3 === 0 && !$loop->last)
    <p style="page-break-before: always"></p>
    @endif
    @endforeach
</body>
</html>
