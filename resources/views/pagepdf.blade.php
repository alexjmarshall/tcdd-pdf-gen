<!DOCTYPE html>
<html>
<head>
    <title>TEST</title>
</head>
<body>
    <h1>Course Catalog</h1>
    <h2>Training Portal Courses</h2>
    @foreach ($moodleCourses as $moodleCourse)
        <h3>{{ $moodleCourse->fullname }}</h3>
        <ul>
            <p><strong>Keywords</strong>: {{ $moodleCourse->keywords }}</p>
            <p><strong>Estimated time to complete</strong>: {{ $moodleCourse->estimatedtime }}</p>
            <p><strong>Description</strong>: {{ $moodleCourse->description }}</p>
            <p><strong>Objectives</strong>:</p>
            {!! $moodleCourse->objectives !!}
        </ul>
    @endforeach
        
    <h2>COMET Courses</h2>
    @foreach ($cometCourses as $cometCourse)
        @if ($lang === "fr")
            <h3>{{ $cometCourse->titleFr }}&euro;</h3>
            <ul>
                <p><strong>Publish date</strong>: {{ $cometCourse->publishDateFr }}&euro;</p>
                <p><strong>Topics</strong>: {{ $cometCourse->topics }}</p>
                <p><strong>Estimated time to complete</strong>: {!! $cometCourse->completionTime !!}</p>
                <p><strong>Description</strong>: {!! $cometCourse->descriptionFr !!}</p>
            </ul>
        @elseif ($lang === "en")
        <h3>{{ $cometCourse->titleEn }}</h3>
            <ul>
                <p><strong>Publish date</strong>: {{ $cometCourse->publishDateEn }}</p>
                <p><strong>Topics</strong>: {{ $cometCourse->topics }}</p>
                <p><strong>Estimated time to complete</strong>: {!! $cometCourse->completionTime !!}</p>
                <p><strong>Description</strong>: {!! $cometCourse->descriptionEn !!}</p>
            </ul>
        @endif
    @endforeach
</body>
</html>
