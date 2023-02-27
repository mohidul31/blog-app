<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    public function test_get_all_article()
    {
        $user = User::find(1);
        $response = $this->actingAs($user, 'api')->get('/api/articles')
            ->assertStatus(200)
            ->assertJsonStructure(
                [
                    'message',
                    'data' =>  [
                        '*' => [
                            "id",
                            "title",
                            "description",
                            "created_at",
                            "updated_at",
                        ],
                    ],
                ]
            );
    }
}
