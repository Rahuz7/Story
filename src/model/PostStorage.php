<?php

require_once("Post.php");

interface PostStorage {
    public function create(Post $p);

    public function read($id); 

    public function readAll($reverse=true);

    public function readUser($id, $reverse=true);

    public function selectType($type, $reverse=true);

    public function update($id, Post $p);

    public function delete($id);
}

?>