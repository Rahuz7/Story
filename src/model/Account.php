<?php 

class Account {

    protected $login;
    protected $password;
    protected $dateCreated;

    public function __construct($login, $password) {
        if(!self::isValidLogin($login)) {
            throw new Exception("Invalid Username");
        }
        $this->login = $login;
        if(!self::isValidPassword($password)) {
            throw new Exception("Invalid Password");
        }
        $this->password = password_hash($password, PASSWORD_BCRYPT);
        $this->dateCreated = $this->dateCreated !== null ? $dateCreated : new DateTime('NOW');
    }

    // Getters
    public function getLogin() {
        return $this->login;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getDateCreated() {
        return $this->dateCreated->format('Y-m-d H:i:s');
    }

    // Setters
    public function setLogin($login) {
        if(!self::isValidLogin($login)) {
            throw new Exception("Invalid Username");
        }
        $this->login = $login;
    }

    public function setPassword($password) {
        if(!self::isValidPassword($password)) {
            throw new Exception("Invalid Password");
        }
        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }

    // Methods
    public function isValidLogin($login) {
        return mb_strlen($login, 'UTF-8') <= 32 && $login !== "" && preg_match("/^[0-9a-zA-Z]+$/i", $login); 
    }

    public function isValidPassword($password) {
        return mb_strlen($password, 'UTF-8') > 6;
    }
}

?>