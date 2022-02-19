<?php 

require_once("model/Account.php");
require_once("model/AccountStorage.php");

class AccountStorageDB implements AccountStorage {

    // For XAMPP
    protected $db = "story";
    protected $host = "localhost";
    protected $user = "root";
    protected $password = "";

    // For Personal Server
    // protected $host = "mysql.info.unicaen.fr";
    // protected $db = "NUMETU_bd";
    // protected $user = "NUMETU";
    // protected $password = "";

    protected $pdo;

    public function __construct() {
        $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db;
        try {
            $this->pdo = new PDO($dsn, $this->user, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        }
        catch (PDOException $e) {
            echo "Nope, something happened. " . $e;
        }
    }

    public function create(Account $a) {
        $username = $a->getLogin();
        $password = $a->getPassword();
        $creationDate = $a->getDateCreated();
        try {
            $stmt = $this->pdo->prepare("SELECT EXISTS ( SELECT `Username` FROM `Users` WHERE Users.Username = ? )");
            $stmt->bindParam(1, $username);
            $stmt->execute();
            if ($stmt->fetchColumn()) {
                throw new Exception('Database Users.create query error');
            }
            $stmt = $this->pdo->prepare("INSERT INTO `Users` (`Username`, `Password`, `Creation_Date`) VALUES (?, ?, ?)");
            $stmt->bindParam(1, $username);
            $stmt->bindParam(2, $password);
            $stmt->bindParam(3, $creationDate);
            $stmt->execute();
            $stmt->closeCursor();
            return true;
        } catch(PDOException $e) {
            throw new Exception('Database Users.create query error');
        }
    }

    public function read($username) {
        try {
            $stmt = $this->pdo->prepare("SELECT EXISTS ( SELECT `Username` FROM `Users` WHERE Users.Username = ?)");
            $stmt->bindParam(1, $username);
            $stmt->execute();
            if (!$stmt->fetchColumn()) {
                throw new Exception('Database Users.read query error');
            }
            $stmt = $this->pdo->prepare("SELECT `User_id`,`Username`,`Creation_Date` FROM `Users` WHERE Users.Username = ?");
            $stmt->bindParam(1, $username);
            $stmt->execute();
            $res = $stmt->fetchAll();
            $stmt->closeCursor();
            return $res;
        } catch(PDOException $e) {
            throw new Exception('Database Users.read query error');
        }
    }

    public function checkAuth($login, $password) {
        try {
            $stmt = $this->pdo->prepare("SELECT EXISTS ( SELECT `Username` FROM `Users` WHERE Users.Username = ? )");
            $stmt->bindParam(1, $login);
            $stmt->execute();
            if(!$stmt->fetchColumn()) {
                throw new Exception('Database Users.checkAuth query error');
            }
            $stmt = $this->pdo->prepare("SELECT `Password` FROM `Users` WHERE Users.Username = ?");
            $stmt->bindParam(1, $login);
            $stmt->execute();
            if(!password_verify($password, $stmt->fetchColumn())) {
                throw new Exception('Specified wrong password');
            }
            $stmt->closeCursor();
            return true;
        } catch(PDOException $e) {
            throw new Exception('Database Users.checkAuth query error');
        }
    }

    public function update($id, Account $a) {
        try {
            $password = $a->getPassword();
            $stmt = $this->pdo->prepare("SELECT EXISTS ( SELECT `Username` FROM `Users` WHERE Users.User_id = ?)");
            $stmt->bindParam(1, $id);
            $stmt->execute();
            if (!$stmt->fetchColumn()) {
                throw new Exception('Database USers.update query error');
            }
            $stmt = $this->pdo->prepare("UPDATE `Users` SET `Password` = ? WHERE Users.User_id = ?");
            $stmt->bindParam(1, $password);
            $stmt->bindParam(2, $id);
            $stmt->execute();
            $stmt->closeCursor();
            return true;
        } catch(PDOException $e) {
            throw new Exception('Database Users.update query error');
        }
    }

    public function getUserFromId($id){
        try {
            $stmt = $this->pdo->prepare("SELECT EXISTS ( SELECT `User_id` FROM `Users` WHERE Users.User_id = ?)");
            $stmt->bindParam(1, $id);
            $stmt->execute();
            if (!$stmt->fetchColumn()) {
                throw new Exception('Database Users.getUserFromId query error');
            }
            $stmt = $this->pdo->prepare("SELECT `Username` FROM `Users` WHERE Users.User_id = ?");
            $stmt->bindParam(1, $id);
            $stmt->execute();
            $res = $stmt->fetchAll();
            $stmt->closeCursor();
            return $res;
        } catch(PDOException $e) {
            throw new Exception('Database Users.getUserFromId query error');
        }
    }

    public function delete($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT EXISTS ( SELECT `Username` FROM `Users` WHERE Users.User_id = ?)");
            $stmt->bindParam(1, $id);
            $stmt->execute();
            if (!$stmt->fetchColumn()) {
                throw new Exception('Database Users.delete query error');
            }
            $stmt = $this->pdo->prepare("DELETE FROM `Users` WHERE Users.User_id = ?");
            $stmt->bindParam(1, $id);
            $stmt->execute();
            $stmt->closeCursor();
            return true;
        } catch(PDOException $e) {
            throw new Exception('Database Users.delete query error');
        }
    }

}

?>
