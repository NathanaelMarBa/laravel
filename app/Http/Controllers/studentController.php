<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Client\ResponseSequence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class studentController extends Controller
{
    public function index(){
        $students = Student::all();

        if ($students->isEmpty()){
            $data = [
                'mesage' => 'No se encontraron estudiantes registrados',
                'status' => 200
            ];
            return response()->json($data, 200);
        }
        return response()->json($students, 200);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:student',
            'phone' => 'required|digits:10',
            'language' => 'required'
        ]);

        if($validator->fails()){
            $data = [
                'mesage' => 'Error en la validacion de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];

            return response()->json($data, 400);
        }
        $student = Student::create([
            'name' => $request-> name,
            'email' => $request->email,
            'phone' => $request->phone,
            'language' => $request->language
        ]);

        if(!$student){
            $data = [
                'message' => 'Error al crear el estudiante',
                'status' => 500
            ];
            return response()->json($data, 500);
        }
        $data = [
            'message' => $student,
            'status' => 201
        ];
        return response()->json($data, 201);
    }

    public function show ($id){
        $student = Student::find($id);

        if (!$student){
            $data = [
                'message' => 'Estudiante no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $data = [
            'student' => $student,
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    public function delete ($id){
        $student = Student::find($id);

        if (!$student){
            $data = [
                'message' => 'Estudiante no encontrado',
                'estatus' => 404
            ];
            return response()->json($data, 404);
        }

        $student->delete();

        $data = [
            'message' => 'Estudiante eliminado',
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function update(Request $request, $id){

        $student = Student::find($id);

        if (!$student){
            $data = [
                'message' => 'Estudiante no encontrado',
                'estatus' => 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:student',
            'phone' => 'required|digits:10',
            'language' => 'required'
        ]);

        if($validator->fails()){
            $data = [
                'mesage' => 'Error en la validacion de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];

            return response()->json($data, 400);
        }

        $student->name = $request->name;
        $student->email = $request->email;
        $student->phone = $request->phone;
        $student->language = $request->language;

        $student->save();

        $data = [
            'message' => 'Estudiante Actualizaado',
            'student' => $student,
            'status' => 200
        ];

        return response()->json($data, 200);
    }
}
