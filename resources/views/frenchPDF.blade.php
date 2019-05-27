<!DOCTYPE html>
<html>
<head>
    <title>TEST</title>
</head>
<body>
    <h1>Catalogue de cours</h1>

    <h2>Contenu</h2>
    <h3>Portail de formation</h3>
    @foreach ($moodleCourses as $moodleCourse)
        {{ $moodleCourse->fullname }} <a href="#moodle-{{ $moodleCourse->id }}">{{ $pageCount = ceil($loop->iteration / 3) + 1 }}</a><br />
    @endforeach
    <h3>Cours COMET</h3>
    @foreach ($cometCourses as $cometCourse)
        {{ $cometCourse->titleEn }} <a href="#comet-{{ $cometCourse->id }}">{{ ceil($loop->iteration / 3) + $pageCount }}</a><br />
    @endforeach
    <p style="page-break-before: always"></p>
    <h2>Portail de formation</h2>
    @foreach ($moodleCourses as $moodleCourse)
    <div id="moodle-{{ $moodleCourse->id }}">
        <h3>{{ $moodleCourse->fullname }}</h3>
        <ul>
            <p><strong>Date de publication</strong>:</p>
            <p><strong>Dur&eacute;e estim&eacute;e</strong>: {{ $moodleCourse->estimatedtime }}</p>
            <p><strong>Description</strong>: {{ $moodleCourse->description }}</p>
            <p><strong>Mots-cl&eacute;s</strong>: {{ $moodleCourse->keywords }}</p>
        </ul>
    </div>
    @if ($loop->iteration % 3 === 0)
    <p style="page-break-before: always"></p>
    @endif
    @endforeach

    <p style="page-break-before: always"></p>

    <h2>Cours COMET</h2>
    @foreach ($cometCourses as $cometCourse)
    <div id="comet-{{ $cometCourse->id }}">
        <h3>{{ $cometCourse->titleFr }}</h3>
        <ul>
            <p><strong>Date de publication</strong>: {{ $cometCourse->publishDateFr }}</p>
            <p><strong>Dur&eacute;e estim&eacute;e</strong>: {!! $cometCourse->completionTime !!}</p>
            <p><strong>Description</strong>: {!! $cometCourse->descriptionFr !!}</p>
            <p><strong>Les sujets</strong>: {{ $cometCourse->topics }}</p>
        </ul>
    </div>
    @if ($loop->iteration % 3 === 0 && !$loop->last)
    <p style="page-break-before: always"></p>
    @endif
    @endforeach
</body>
</html>
