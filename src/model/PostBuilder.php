<?php 

require_once("model/Post.php");

class PostBuilder {

    protected $data;
	protected $errors;
    
	public function __construct($data=null) {
		if ($data === null) {
			$data = array(
				"setup" => "",
				"punchline" => "",
                "type" => "",
			);
		}
		$this->data = $data;
		$this->errors = array();
	}

    public static function buildFromPost(Post $post) {
		return new PostBuilder(array(
			"setup" => $post->getSetup(),
			"punchline" => $post->getPunchline(),
            "type" => $post->getType(),
		));
	}

    public function isValid() {
		$this->errors = array();
        // setup
		if (!key_exists("setup", $this->data) || $this->data["setup"] === "") {
            $this->errors["setup"] = "Vous devez entrer un titre";
        }
		else if (mb_strlen($this->data["setup"], 'UTF-8') >= 200) {
            $this->errors["setup"] = "Le titre doit faire moins de 200 caractères";
        }
        // punchline
        if (!key_exists("punchline", $this->data) || $this->data["punchline"] === "") {
            $this->errors["punchline"] = "Vous devez entrer une histoire";
        }
		else if (mb_strlen($this->data["punchline"], 'UTF-8') >= 200) {
            $this->errors["setup"] = "L'histoire doit faire moins de 200 caractères";
        }
        // type
		if (!key_exists("type", $this->data) || $this->data["type"] === "") {
            $this->errors["type"] = "Vous devez sélectionner un type";
        }
		return count($this->errors) === 0;
	}

    public function getSetupRef() {
		return "setup";
	}

	public function getPunchlineRef() {
		return "punchline";
	}

    public function getTypeRef() {
        return "type";
    }

	public function getData($ref) {
		return key_exists($ref, $this->data) ? $this->data[$ref] : '';
	}

	public function getErrors($ref) {
		return key_exists($ref, $this->errors )? $this->errors[$ref] : null;
	}

	public function createPost() {
		if (!key_exists("setup", $this->data) || !key_exists("punchline", $this->data) || !key_exists("type", $this->data)) {
            throw new Exception("Missing fields for post creation");
        }
		return new Post($this->data["setup"], $this->data["punchline"], $this->data["type"], 1);
	}

	public function updatePost(Post $post) {
		if (key_exists("setup", $this->data)) {
            $post->setSetup($this->data["setup"]);
        }
		if (key_exists("punchline", $this->data)) {
            $post->setPunchline($this->data["punchline"]);
        }
        if (key_exists("type", $this->data)) {
            $post->setType($this->data["type"]);
        }
	}

}

?>