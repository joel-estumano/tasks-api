<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @OA\Tag(
 *     name="tasks",
 *     description="tasks related operations"
 * )
 */
class TasksController extends Controller
{
    /**
     * @OA\Post(
     *     path="tasks/add",
     *     summary="add a task",
     *     security={{"bearer_token":{}}},
     *     tags={"tasks"},
     *        @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  required={"name", "description" ,"time"},
     *                  @OA\Property(
     *                  property="name",
     *                  description="task name",
     *                  type="string",
     *                  example="Gym class"
     *                  ),
     *                  @OA\Property(
     *                  property="description",
     *                  description="task description",
     *                  type="string",
     *                  example="Practical exercises part 1"
     *                  ),
     *                  @OA\Property(
     *                  property="time",
     *                  description="task time",
     *                  type="string",
     *                  example="2024-03-30T17:27:11.00"
     *              ),
     *           ),
     *        ),
     *     ),
     *     @OA\Response(
     *          response=201,
     *          description="successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/TaskResponse"),
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="unauthorized",
     *          @OA\JsonContent(ref="#/components/schemas/UnauthorizedResponse"),
     *     )
     * )
     */
    public function add(Request $req)
    {
        $task = new Task;
        $task->name = $req->name;
        $task->description = $req->description;
        $task->time = $req->time;
        $task->user_id = $req->user->id;
        $task->completed = false;
        $task->save();
        return response()->json($task, Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="tasks/list",
     *     summary="list all tasks of a user",
     *     security={{"bearer_token":{}}},
     *     tags={"tasks"},
     *     @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/TasksResponse"),
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="unauthorized",
     *          @OA\JsonContent(ref="#/components/schemas/UnauthorizedResponse"),
     *     )
     * )
     */
    public function list(Request $req)
    {
        $user = $req->user();
        $tasks = Task::where('user_id', $user->id)->get();
        return response()->json($tasks, Response::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     path="tasks/list/{date}",
     *     summary="list tasks by date",
     *     security={{"bearer_token":{}}},
     *     tags={"tasks"},
     *     @OA\Parameter(
     *         name="date",
     *         in="path",
     *         description="task date",
     *         required=true,
     *         example="0000-00-00"
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/TasksResponse"),
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="unauthorized",
     *          @OA\JsonContent(ref="#/components/schemas/UnauthorizedResponse"),
     *     )
     * )
     */
    public function listByDate(Request $req, $date)
    {
        $user = $req->user();
        $tasks = Task::where('user_id', $user->id)->whereDate('time', $date)->get();
        return response()->json($tasks, Response::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     path="tasks/read/{id}",
     *     summary="read a task",
     *     security={{"bearer_token":{}}},
     *     tags={"tasks"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="task id",
     *         required=true,
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/TaskResponse"),
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="unauthorized",
     *          @OA\JsonContent(ref="#/components/schemas/UnauthorizedResponse"),
     *     )
     * )
     */
    public function read(Request $req, $id)
    {
        $user = $req->user();
        $task = $this->find($id, $user);
        if ($task) {
            return response()->json($task, Response::HTTP_OK);
        } else {
            return response()->json(['message' => 'not found'], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @OA\Patch(
     *     path="tasks/edit/{id}",
     *     summary="edit a task",
     *     security={{"bearer_token":{}}},
     *     tags={"tasks"},
     *       @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="task id",
     *         required=true,
     *        ),
     *        @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *              @OA\Property(
     *              property="name",
     *              description="task name",
     *              type="string",
     *              ),
     *              @OA\Property(
     *              property="description",
     *              description="task description",
     *              type="string",
     *              ),
     *              @OA\Property(
     *              property="time",
     *              description="task time",
     *              type="string",
     *              example="2024-03-30T17:27:11.00"
     *              ),
     *              @OA\Property(
     *              property="completed",
     *              description="task completed",
     *              type="boolean",
     *              example="true"
     *              ),
     *         ),
     *       ),
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/TaskResponse"),
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="unauthorized",
     *          @OA\JsonContent(ref="#/components/schemas/UnauthorizedResponse"),
     *     )
     * )
     */
    public function edit(Request $req, $id)
    {
        $req->validate([
            'name' => 'string',
            'description' => 'string',
            'time' => 'string',
            'completed' => 'boolean'
        ]);
        $user = $req->user();
        $task = $this->find($id, $user);
        if ($task) {
            $task->fill($req->all());
            $task->save();
            return response()->json($task, Response::HTTP_OK);
        } else {
            return response()->json(['message' => 'not found'], Response::HTTP_NOT_FOUND);
        }
    }

    private function find($id, $user)
    {
        return Task::where('user_id', $user->id)->find($id);
    }

    /**
     * @OA\Delete(
     *     path="tasks/delete/{id}",
     *     summary="delete a task",
     *     security={{"bearer_token":{}}},
     *     tags={"tasks"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="task id",
     *         required=true,
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/DefaultResponse"),
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="unauthorized",
     *          @OA\JsonContent(ref="#/components/schemas/UnauthorizedResponse"),
     *     )
     * )
     */
    public function delete(Request $req, $id)
    {
        $user = $req->user();
        $task = $this->find($id, $user);
        if ($task) {
            $task->delete();
            return response()->json(['message' => 'task successfully deleted'], Response::HTTP_OK);
        } else {
            return response()->json(['message' => 'not found'], Response::HTTP_NOT_FOUND);
        }
    }
}
