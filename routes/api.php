<?php

use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SemesterController;
use App\Http\Controllers\Api\ClassroomController;
use App\Http\Controllers\Api\ClassStudentController;
use App\Http\Controllers\Api\ExcelController;
use App\Http\Controllers\Api\LessonController;
use App\Http\Controllers\Api\MajorController;
use App\Models\Attendance;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('auth/user', [AuthController::class, 'getAuthDetail']);

Route::prefix('major')->group(function () {
    Route::get('get-all', [MajorController::class, 'index']);
    Route::middleware('admin')->group(function (){
        Route::post('store', [MajorController::class, 'store']);
        Route::middleware('existMajor')->group(function (){
            Route::get('show/{id}', [MajorController::class, 'show']);
            Route::put('update/{id}', [MajorController::class, 'update']);
        });
    });
});
// Route::prefix('user')->group(function () {
//     Route::get('get', [UserController::class, 'get']);
//     Route::middleware('existUser')->group((function () {
//         Route::get('show/{id}', [UserController::class, 'show']);
//         Route::put('update/{id}', [UserController::class, 'update']);
//     }));
// });
// Route::name('subject')->prefix('subject')->group(function () {
//     Route::get('get-all',[SubjectController::class, 'index']);
//     Route::middleware('existSubject')->group(function (){
//         Route::get('show/{id}',[SubjectController::class, 'show']);
//         Route::middleware('admin')->group(function (){
//             Route::put('update/{id}',[SubjectController::class, 'update']);
//             // Route::delete('destroy/{id}',[SubjectController::class, 'destroy']);
//         });
//     });
//     Route::middleware('admin')->group(function (){
//         Route::post('store',[SubjectController::class, 'store']);
//     });
// });
// Route::prefix('class-student')->group(function () {
//     Route::get('in-classroom/{id}', [ClassStudentController::class, 'classStudentsInClassroom'])->middleware('existClassroom');
//     Route::post('store', [ClassStudentController::class, 'store']);
//     Route::middleware('existClassStudent')->group((function () {
//         Route::delete('update/{id}', [ClassStudentController::class, 'update']);
//     }));
// });


// API FOR MANAGE
Route::prefix('semester')->group(function () {
    Route::get('get-all', [SemesterController::class, 'index']);
    Route::post('store', [SemesterController::class, 'store'])->middleware('admin');
    Route::middleware(['existSemester', 'admin'])->group(function () {
        Route::put('{semester_id}/update', [SemesterController::class, 'update']);
        Route::delete('{semester_id}/delete', [SemesterController::class, 'destroy']);
        Route::post('{semester_id}/import', [ExcelController::class, 'import']);

        Route::get('{semester_id}/classrooms', [ClassroomController::class, 'classroomsInSemester']);
    });
});

Route::prefix('classroom')->middleware('checkRoleTeacherOrAdmin')->group(function () {
    Route::post('store', [ClassroomController::class, 'store']);
    Route::middleware('existClassroom')->group(function () {
        Route::put('{classroom_id}/update', [ClassroomController::class, 'update'])->middleware('admin');
        Route::delete('{classroom_id}/delete', [ClassroomController::class, 'destroy']);

        Route::get('{classroom_id}/lessons', [LessonController::class, 'lessonsInClassroom']);
        Route::get('{classroom_id}/students', [ClassStudentController::class, 'studentsInClassroom']);
    });
});

Route::prefix('lesson')->middleware('checkRoleTeacherOrAdmin')->group(function () {
    Route::post('store', [LessonController::class, 'store']);
    Route::middleware('existLesson')->group(function () {
        Route::put('{lesson_id}/update', [LessonController::class, 'update']);
        Route::delete('{lesson_id}/delete', [LessonController::class, 'destroy']);
    });
});


// API FOR ATTENDANCE
Route::prefix('attendance')->group(function () {
    Route::get('classrooms', [AttendanceController::class, 'getListClass']);
    Route::get('classroom/{classroom_id}/lessons', [LessonController::class, 'lessonsInClassroom']);

    Route::middleware('existLesson')->group(function () {
        Route::get('lesson/{lesson_id}', [AttendanceController::class, 'attendanceDetail']);
        Route::put('lesson/{lesson_id}', [AttendanceController::class, 'update']);
    });
});

// API FOR STUDENT
Route::prefix('student')->group(function () {
    Route::get('schedule', [LessonController::class, 'studentSchedule']);
    Route::get('missing-classes', [ClassroomController::class, 'missingClasses']);
    Route::put('join-class/{classroom_id}', [ClassroomController::class, 'joinClass'])->middleware('existClassroom');
});
