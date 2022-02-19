<?php 

require_once("model/Account.php");

class AccountBuilder {
    
    protected $data;
    protected $errors;

    public function __construct($data=null) {
        if($data === null) {
            $data = array(
                "login" => "",
                "password" => "",
            );
        }
        $this->data = $data;
        $this->errors = array();
    }

    public static function buildFromAccount(Account $account) {
        return new AccountBuilder(array(
            "login" => $account->getLogin(),
            "password" => $account->getPassword(),
        ));
    }

    public function isValid() {
        $this->errors = array();
        if (!key_exists("login", $this->data) || $this->data["login"] === "") {
            $this->errors["login"] = "Vous devez entrer un login";
        } else if (mb_strlen($this->data["login"], 'UTF-8') >= 32) {
            $this->errors["login"] = "Le login doit faire moins de 32 caractères";
        } else if (preg_match("/^[0-9a-zA-Z]$/i", $this->data["login"])) {
            $this->errors["login"] = "Le login ne doit pas contenir de symbole.";
        } else if (!key_exists("password", $this->data) || $this->data["password"] === "") {
            $this->errors["password"] = "Vous devez entrer un mot de passe";
        } else if (mb_strlen($this->data["password"], 'UTF-8') <= 6) {
            $this->errors["password"] = "Le mot de passe doit faire plus de 6 caractères";
        }
        return count($this->errors) === 0;
    }

    public function getLoginRef() {
        return "login";
    }

    public function getPasswordRef() {
        return "password";
    }

    public function getData($ref) {
        return key_exists($ref, $this->data) ? $this->data[$ref] : '';
    }

    public function getErrors($ref) {
        return key_exists($ref, $this->errors) ? $this->errors[$ref] : null;
    }

    public function createAccount() {
        if (!key_exists("login", $this->data) || !key_exists("password", $this->data)) {
            throw new Exception("Missing fields for account creation");
        }
        return new Account($this->data["login"], $this->data["password"]);
    }

    public function updateAccount(Account $account) {
        if (key_exists("login", $this->data)) {
            $account->setLogin($this->data["login"]);
        }
        if (key_exists("password", $this->data)) {
            $account->setPassword($this->data["password"]);
        }
    }
}

?>