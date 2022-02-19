<?php 

require_once("model/Post.php");
require_once("model/PostStorage.php");


class PostStorageDB implements PostStorage {

    // For XAMPP
    protected $host = "localhost";
    protected $db = "story";
    protected $user = "root";
    protected $password = "";

    //  For Personal Server
    //   protected $host = "mysql.info.unicaen.fr";
    //   protected $db = "NUMETU_bd";
    //   protected $user = "NUMETU";
    //   protected $password = "";

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

    public function create(Post $p) {
        $user_id = $p->getUserId();
        $setup = $p->getSetup();
        $punchline = $p->getPunchline();
        $type = $p->getType();
        $creationDate = $p->getDateCreated();
        $modificationDate = $p->getDateModified();

        try {
            $stmt = $this->pdo->prepare("SELECT EXISTS ( SELECT `User_id` FROM `Users` WHERE Users.User_id = ? )");
            $stmt->bindParam(1, $user_id);
            $stmt->execute();
            if (!$stmt->fetchColumn()) {
                throw new Exception('Database Post.create query error');
            }
            $stmt = $this->pdo->prepare("INSERT INTO `Posts` (`User_id`, `Setup`, `Punchline`, `Type`, `Creation_Date`, `Modification_Date`) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bindParam(1, $user_id);
            $stmt->bindParam(2, $setup);
            $stmt->bindParam(3, $punchline);
            $stmt->bindParam(4, $type);
            $stmt->bindParam(5, $creationDate);
            $stmt->bindParam(6, $modificationDate);
            $stmt->execute();
            $stmt->closeCursor();
            return true;
        } catch (PDOException $e) {
            throw new Exception('Database Post.create query error');
        }
    }

    public function read($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT EXISTS ( SELECT `Post_id` FROM `Posts` WHERE Posts.Post_id = ? )");
            $stmt->bindParam(1, $id);
            $stmt->execute();
            if(!$stmt->fetchColumn()) {
                throw new Exception('Database Post.read query error');
            }
            $stmt = $this->pdo->prepare("SELECT * FROM `Posts` WHERE Posts.Post_id = ?");
            $stmt->bindParam(1, $id);
            $stmt->execute();
            $res = $stmt->fetchAll();
            $stmt->closeCursor();
            return $res;
        } catch (PDOException $e) {
            throw new Exception('Database Post.read query error');
        }
    }

    public function readAll($reverse=true) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM `Posts` ORDER BY `Creation_Date` DESC");
            if($reverse == false) {
                $stmt = $this->pdo->prepare("SELECT * FROM `Posts` ORDER BY `Creation_Date` ASC");
            }
            $stmt->execute();
            $res = $stmt->fetchAll();
            $stmt->closeCursor();
            return $res;
        } catch (PDOException $e) {
            throw new Exception('Database Post.readAll query error');
        }
    }

    public function readUser($username, $reverse=true) {
        try {
            $stmt = $this->pdo->prepare("SELECT EXISTS ( SELECT `Username` FROM `Users` WHERE Users.Username = ? )");
            $stmt->bindParam(1, $username);
            $stmt->execute();
            if(!$stmt->fetchColumn()) {
                throw new Exception('Database Post.readUser query error');
            }
            $stmt = $this->pdo->prepare("SELECT * FROM `Posts` WHERE Posts.User_id IN ( SELECT User_id FROM `Users` WHERE Users.Username = ?) ORDER BY `Creation_Date` DESC");
            if($reverse == false) {
                $stmt = $this->pdo->prepare("SELECT * FROM `Posts` WHERE Posts.User_id IN ( SELECT User_id FROM `Users` WHERE Users.Username = ?) ORDER BY `Creation_Date` ASC");
            }
            $stmt->bindParam(1, $username);
            $stmt->execute();
            $res = $stmt->fetchAll();
            $stmt->closeCursor();
            return $res;
        } catch (PDOException $e) {
            throw new Exception('Database Post.readUser query error');
        }
    }

    public function selectType($type, $reverse=true) {
        try {
            $stmt = $this->pdo->prepare("SELECT EXISTS ( SELECT `Type` FROM `Posts` WHERE Posts.Type = ? )");
            $stmt->bindParam(1, $type);
            $stmt->execute();
            if(!$stmt->fetchColumn()) {
                throw new Exception('Database Post.selectType query error');
            }
            $stmt = $this->pdo->prepare("SELECT * FROM `Posts` WHERE Posts.Type = ? ORDER BY `Creation_Date` DESC");
            if($reverse == false) {
                $stmt = $this->pdo->prepare("SELECT * FROM `Posts` WHERE Posts.Type = ? ORDER BY `Creation_Date` ASC");
            }
            $stmt->bindParam(1, $type);
            $stmt->execute();
            $res = $stmt->fetchAll();
            $stmt->closeCursor();
            return $res;
        } catch (PDOException $e) {
            throw new Exception('Database Post.selectType query error');
        }
    }

    public function update($id, Post $p) {
        $setup = $p->getSetup();
        $punchline = $p->getPunchline();
        $type = $p->getType();
        $modificationDate = $p->getDateModified();
        try {
            $stmt = $this->pdo->prepare("SELECT EXISTS ( SELECT `Post_id` FROM `Posts` WHERE Posts.Post_id = ? )");
            $stmt->bindParam(1, $id);
            $stmt->execute();
            if (!$stmt->fetchColumn()) {
                throw new Exception('Database Post.update query error');
            }
            $stmt = $this->pdo->prepare("UPDATE `Posts` SET `Setup` = ?, `Punchline` = ?, `Type` = ?, `Modification_Date` = ? WHERE Posts.Post_id = ?");
            $stmt->bindParam(1, $setup);
            $stmt->bindParam(2, $punchline);
            $stmt->bindParam(3, $type);
            $stmt->bindParam(4, $modificationDate);
            $stmt->bindParam(5, $id);
            $stmt->execute();
            $stmt->closeCursor();
            return true;
        } catch (PDOException $e) {
            throw new Exception('Database Post.update query error');
        }
    }

    public function delete($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT EXISTS ( SELECT `Post_id` FROM `Posts` WHERE Posts.Post_id = ?)");
            $stmt->bindParam(1, $id);
            $stmt->execute();
            if (!$stmt->fetchColumn()) {
                throw new Exception('Database Post.delete query error');
            }
            $stmt = $this->pdo->prepare("DELETE FROM `Posts` WHERE Posts.Post_id = ?");
            $stmt->bindParam(1, $id);
            $stmt->execute();
            $stmt->closeCursor();
            return true;
        } catch(PDOException $e) {
            throw new Exception('Database Post.delete query error');
        }
    }

}
?>
