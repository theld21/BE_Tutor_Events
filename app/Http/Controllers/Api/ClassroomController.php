<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClassroomRequest;
use App\Services\ClassroomServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ClassroomController extends Controller
{
    private $classroomServices;

    public function __construct(ClassroomServices $classroomServices){
        $this->classroomServices = $classroomServices;
    }

    public function index()
    {
        $Subject = $this->classroomServices->index();
        return response([
            '$Subject' => $Subject
        ],200);
    }

    public function store(ClassroomRequest $request)
    {
        $classroom = $this->classroomServices->store($request->input());
        return response([
            'status' => true,
            'message' => 'Create Classroom successfully',
            'data' => $classroom
        ],201);
    }

    public function show(Request $request)
    {
        $classroom = $request->get('classroom');

        $subject = $this->classroomServices->show($classroom);
        
        return response([
            'status' => true,
            'data' => $subject
        ],200);
    }

    public function update(ClassroomRequest $request)
    {
        $classroom = $request->get('classroom');
        
        $this->authorize('updateClassroom', $classroom);

        $classroom = $this->classroomServices->update($request->input(), $classroom);

        if ($classroom) {
            return response([
                'message' => 'update Classroom successfully',
                'status' => true
            ],200);
        } else {
            return response([
                'message' => 'update Classroom failed',
                'status' => false
            ],400);
        }
    }

    public function destroy(Request $request)
    {
        $classroom = $request->get('classroom');
        $this->authorize('updateClassroom', $classroom);
        $checkDeleteSubject = $this->classroomServices->destroy($classroom);

        if ($checkDeleteSubject) {
            return response([
                'message' => 'delete classroom successfully',
                'status' => true
            ],200);
        } else {
            return response([
                'message' => 'delete classroom failed',
                'status' => false
            ],400);
        }
    }
}
