<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 * @OA\Schema(
 * @OA\Xml(name="Article"),
 * @OA\Property(property="id", type="integer"),
 * @OA\Property(property="title", type="string"),
 * @OA\Property(property="description", type="string"),
 * @OA\Property(property="user_id", type="integer" ),
 * @OA\Property(property="created_at", type="string", format="date-time", description="Initial creation timestamp", readOnly="true"),
 * @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp", readOnly="true"),
 * )
 */
class Article extends Model
{
    use HasFactory;
}
