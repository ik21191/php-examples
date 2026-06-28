<?php

class Post {
    public $title;
    public $content;
    public $tags;
    public $author;

    public function __construct($title, $content, $tags, $author) {
        $this->title = $title;
        $this->content = $content;
        $this->tags = $tags;
        $this->author = $author;
    }

    public static function all() {
        $postsArray = [
            ["title" => "First Blog Post", "content" => "This is the first blog post.", "tags" => "PHP, MVC", "author" => "John Doe"],
            ["title" => "Second Blog Post", "content" => "Another blog post here.", "tags" => "Web, PHP", "author" => "Jane Doe"]
        ];

        // Convert each array item to a Post object
        return array_map(function($post) {
            return new Post($post["title"], $post["content"], $post["tags"], $post["author"]);
        }, $postsArray);
    }
}
