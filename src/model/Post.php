<?php

class Post {

    protected $setup;
    protected $punchline;
    protected $type;
    protected $userID;
    protected $dateCreated;
    protected $dateModified;

    public function __construct($setup, $punchline, $type, $userID, $dateCreated = null, $dateModified = null) {
        $this->setup = $setup;
        $this->punchline = $punchline;
        $this->type = $type;
        $this->userID = $userID;
        $this->dateCreated = $dateCreated !== null ? $dateCreated : new DateTime('NOW');
        $this->dateModified = $dateModified !== null ? $dateModified : new DateTime('NOW');
    }

    // Getters
    public function getSetup() {
        return $this->setup;
    }

    public function getPunchline() {
        return $this->punchline;
    }

    public function getType() {
        return $this->type;
    }

    public function getUserId() {
        return $this->userID;
    }

    public function getDateCreated() {
        return $this->dateCreated->format('Y-m-d H:i:s');
    }

    public function getDateModified() {
        return $this->dateModified->format('Y-m-d H:i:s');
    }

    // Setters
    public function setSetup($setup) {
        $this->setup = $setup;
        $this->dateModified = new DateTime('NOW');
    }

    public function setType($type) {
        $this->type = $type;
        $this->dateModified = new DateTime('NOW');
    }

    public function setPunchline($punchline) {
        $this->punchline = $punchline;
        $this->dateModified = new DateTime('NOW');
    }

    // Methods
    public function isSetupValid($setup) {
        return mb_strlen($setup, 'UTF-8') < 200 && $setup !== "";
    }

    public function isTypeValid($type) {
        return mb_strlen($type, 'UTF-8') < 20 && $type !== "" && preg_match("/^[a-zA-Z]+$/i", $type);
    }

    public function isPunchlineValid($punchline) {
        return mb_strlen($punchline, 'UTF-8') < 200 && $punchline !== "";
    }

}

?>