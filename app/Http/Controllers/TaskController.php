<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function createTask(Request $request)
    {
        Log::info("Creating task");
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|min:8',
                'description' => 'required',
            ]);

            if ($validator->fails()) {
                return response([
                    'success' => false,
                    'message' => $validator->messages()
                ], 400);
            }
            //Recuperar informacion del body
            $title = $request->input('title');
            $description = $request->input('description');

            //recuperar usuario a traves del token
            $userId = auth()->user()->id;

            //Guardar en bd la nueva tarea
            $task = new Task();
            $task->title = $title;
            $task->description = $description;
            $task->status = false;
            $task->user_id = $userId;
            $task->save();

            // response
            return response([
                "success" => true,
                "message" => "Task created successfuly",
                "data" => $task
            ], 200);
        } catch (\Throwable $th) {
            Log::error("Error creating tasks: ".$th->getMessage());
            return response([
                "success" => false,
                "message" => "Error creating task: " . $th->getMessage()
            ], 500);
        }
    }
}
