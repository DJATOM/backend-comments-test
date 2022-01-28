<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test comments index.
     *
     * @return void
     */
    public function testGetCommentIndex(): void
    {
        $response = $this->getJson('/api/comments');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => []
            ]);
    }

    /**
     * Test adding comment.
     *
     * @return void
     */
    public function testAddComment(): void
    {
        $response = $this->postJson('/api/comments', [
            'content' => 'Test Comment'
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => []
            ]);
    }

    /**
     * Test adding nested comment.
     *
     * @return void
     */
    public function testAddNestedComment(): void
    {
        $this->postJson('/api/comments', [
            'content' => 'Test Comment'
        ]);

        $response = $this->postJson('/api/comments', [
            'content' => 'Test Comment 2',
            'comment_id' => 1
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => []
            ]);
    }

    /**
     * Test adding empty comment.
     *
     * @return void
     */
    public function testAddEmptyComment(): void
    {
        $response = $this->postJson('/api/comments');

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors'
            ]);
    }

    /**
     * Test adding nested comment on inexistent upward comment.
     *
     * @return void
     */
    public function testAddNestedInexistentComment(): void
    {
        $response = $this->postJson('/api/comments', [
            'content' => 'Test Comment 3',
            'comment_id' => 3
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors'
            ]);
    }

    /**
     * Test comment editor.
     *
     * @return void
     */
    public function testEditComment(): void
    {
        $this->postJson('/api/comments', [
            'content' => 'Test Comment'
        ]);

        $response = $this->patchJson('/api/comments/1', [
            'content' => 'Test Comment Edited'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => []
            ]);
    }

    /**
     * Test editing of inexistent comment.
     *
     * @return void
     */
    public function testEditInexistentComment(): void
    {
        $response = $this->patchJson('/api/comments/5', [
            'content' => 'Test Comment 5 Edited'
        ]);

        $response->assertStatus(404)
            ->assertJsonStructure([
                'message'
            ]);
    }

    /**
     * Test comment deletion.
     *
     * @return void
     */
    public function testDeleteComment(): void
    {
        $this->postJson('/api/comments', [
            'content' => 'Test Comment'
        ]);

        $response = $this->deleteJson('/api/comments/1');

        $response->assertStatus(204);
    }

    /**
     * Test deletion of inexistent comment.
     *
     * @return void
     */
    public function testDeleteInexistentComment(): void
    {
        $response = $this->deleteJson('/api/comments/3');

        $response->assertStatus(404)
            ->assertJsonStructure([
                'message'
            ]);
    }
}
