<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Classroom\CreateClassroomRequest;
use App\Http\Requests\Classroom\UpdateClassroomRequest;
use App\Services\BreadcrumbServices;
use App\Services\ClassroomServices;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    private $classroomServices;
    private $breadcrumbServices;

    public function __construct(ClassroomServices $classroomServices, BreadcrumbServices $breadcrumbServices)
    {
        $this->classroomServices = $classroomServices;
        $this->breadcrumbServices = $breadcrumbServices;
    }

    public function classroomsInSemester(Request $request)
    {
        $semester = $request->get('semester');
        $classroom = $this->classroomServices->classroomsInSemester($request->semester_id);

        return response([
            'data' => $classroom,
            'tree' => [$semester]
        ], 200);
    }

    public function store(CreateClassroomRequest $request)
    {
        $created = $this->classroomServices->store($request->input());

        if (!$created) {
            return response([
                'message' => 'Lớp học này đã tồn tại'
            ], 400);
        }

        return response([
            'message' => 'Tạo lớp học thành công'
        ], 201);
    }

    public function update(UpdateClassroomRequest $request)
    {
        $classroom = $request->get('classroom');

        $updated = $this->classroomServices->update($request->input(), $classroom);

        return response([
            'message' => 'Cập nhật lớp học thành công'
        ], 201);
    }

    public function destroy(Request $request)
    {
        $classroom = $request->get('classroom');

        $this->authorize('teacherOfClass', $classroom);

        $response = $this->classroomServices->destroy($classroom->id);

        return $response;
    }

    public function missingClasses()
    {
        $classrooms = $this->classroomServices->studentMissingClasses();

        return response([
            'data' => $classrooms,
        ], 200);
    }

    public function joinClass(Request $request)
    {
        $joined = $this->classroomServices->joinClass($request->classroom_id);

        return response([
            'message' => $joined ? 'Tham gia lớp học thành công' : 'Bạn không có trong danh sách lớp này',
        ], 200);
    }
}
