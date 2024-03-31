<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * 
 * @OA\Server(
 *      url="https://sambli.com.br/api\\",
 *      description="API web server"
 * ),
 * 
 * @OA\Server(
 *      url="http://127.0.0.1:8000/api\\",
 *      description="API local server"
 * ),
 *
 * @OA\Info(
 *      title="Tasks API",
 *      version="0.1",
 *      @OA\Contact(
 *          email="admin@example.test",
 *          name="company",
 *          url="https://example.test"
 *      )
 * ),
 *
 * @OA\Schema(
 *     schema="Default",
 *     @OA\Property(
 *         property="message",
 *         type="string",
 *     ),
 * ),
 *
 * @OA\Schema(
 *     schema="DefaultResponse",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/Default"
 *     ),
 *     @OA\Property(
 *         property="timestamp",
 *         type="string",
 *         format="date-time"
 *     ),
 *     @OA\Property(
 *         property="path",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="integer"
 *     )
 * )
 * 
 *  * @OA\Schema(
 *     schema="UnauthorizedResponse",
 *     @OA\Property(
 *         property="message",
 *         type="string",
 *         default="Unauthenticated."
 *     ),
 * ),
 *
 * @OA\Schema(
 *     schema="User",
 *      @OA\Property(
 *         property="id",
 *         type="integer",
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *     ),
 *     @OA\Property(
 *         property="email_verified_at",
 *         type="string",
 *         default="null"
 *     ),
 *      @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time"
 *     ),
 *      @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time"
 *     ),
 * ),
 *
 * @OA\Schema(
 *     schema="UserResponse",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/User"
 *     ),
 *     @OA\Property(
 *         property="timestamp",
 *         type="string",
 *         format="date-time"
 *     ),
 *     @OA\Property(
 *         property="path",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="integer"
 *     )
 * ),
 *
 * @OA\Schema(
 *     schema="Task",
 *      @OA\Property(
 *         property="id",
 *         type="integer",
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *     ),
 *      @OA\Property(
 *         property="time",
 *         type="string",
 *         format="date-time"
 *     ),
 *      @OA\Property(
 *         property="user_id",
 *         type="integer",
 *     ),
 *     @OA\Property(
 *         property="completed",
 *         type="boolean",
 *         example="false"
 *     ),
 *      @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time"
 *     ),
 *      @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time"
 *     ),
 * ),
 *
 * @OA\Schema(
 *     schema="TaskResponse",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/Task"
 *     ),
 *     @OA\Property(
 *         property="timestamp",
 *         type="string",
 *         format="date-time"
 *     ),
 *     @OA\Property(
 *         property="path",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="integer"
 *     )
 * ),
 *
 * @OA\Schema(
 *    schema="TasksResponse",
 *    @OA\Property(
 *       property="data",
 *       type="array",
 *       @OA\Items(ref="#/components/schemas/Task")
 *   ),
 *     @OA\Property(
 *         property="timestamp",
 *         type="string",
 *         format="date-time"
 *     ),
 *     @OA\Property(
 *         property="path",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="integer"
 *     )
 * ),
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
