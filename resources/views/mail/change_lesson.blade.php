<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Change Lesson</title>
    <link rel="stylesheet" href="{{ asset('mail.css') }}">
</head>
<body>
    <h2>Người chỉnh sửa buổi học: <a href="{{$content['teacher_email']}}" target="_blank">{{$content['teacher_name']}}</a></h2>
    <table class="container">
        <thead>
            <tr>
                <th></th>
                <th><h1>Ngày</h1></th>
                <th><h1>Phòng học</h1></th>
                <th><h1>Thời gian buổi học</h1></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Chi tiết thời gian buổi học cũ</td>
                <td>{{$content['before_date']}}</td>
                <td>{{$content['before_location']}}</td>
                <td>{{$content['before_start_time']}} đến {{$content['before_end_time']}}</td>
            </tr>
            <tr>
                <td>Chi tiết thời gian buổi học mới</td>
                <td>{{$content['after_date']}}</td>
                <td>{{$content['after_location']}}</td>
                <td>{{$content['after_start_time']}} đến {{$content['after_end_time']}}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>