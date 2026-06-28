<?php

require_once __DIR__ . '/../Models/Post.php';

class PostController {
    public function index() {
        // Get all posts from the model
        $posts = Post::all();

        require_once __DIR__ . '/../Views/posts.php';
    }
}
