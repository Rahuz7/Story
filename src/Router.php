<?php 

require_once("model/PostStorageDB.php");
require_once("model/AccountStorageDB.php");

require_once("view/View.php");
require_once("view/AuthView.php");

require_once("ctl/Controller.php");

class Router{
    
    public function __construct(PostStorage $postDB, AccountStorage $accountDB) {
        $this->postDB = $postDB;
        $this->accountDB = $accountDB;
    }

    public function main(){
        session_start();

        $auth = key_exists('auth', $_SESSION) ? $_SESSION['auth'] : $_SESSION['auth'] = false;

        $postId = key_exists('post', $_GET) ? $_GET['post'] : null;
        $accounttId = key_exists('account', $_GET) ? $_GET['account'] : null;
        $action = key_exists('action', $_GET) ? $_GET['action'] : null;

        $view = new View($this);
        $authView = new AuthView($this);
        $controller = new Controller($view, $authView, $this->postDB, $this->accountDB);

        if ($action == null) {
            $action = ($auth == false) ? 'home' : 'gallery';
        }

        try {
            switch ($action) {
                case 'home': 
                    $controller->homePage();
                    break;
                
                case 'gallery':
                    $controller->galleryPage();
                    break;
                
                case 'about': 
                    $controller->aboutPage();
                    break; 
                
                case 'unauthenticated':
                    $view->makeUnauthenticatedPage();
                    break;

                case 'newAccount':
                    $controller->newAccount();
                    break;
                
                case 'saveNewAccount':
                    $controller->saveNewAccount($_POST);
                    break;

                case 'login': 
                    $controller->login($_POST);
                    break;
                
                case 'loginPage': 
                    $controller->loginPage();
                    break;

                case 'postPage':
                    $controller->postPage($postId);
                    break;
                
                case 'newPost':
                    $controller->newPost();
                    break;
                    
                case 'saveNewPost':
                    $controller->saveNewPost($_POST);
                    break;
                
                case 'modifyPostPage':
                    $controller->modifyPostPage($postId);
                    break;

                case 'modifyPost': 
                    $controller->updateModifyPost($postId, $_POST);
                    break;

                case 'deletePost': 
                    if ($postId == null) {
                        $view->makeErrorPage("Router deletePost Error");
                    } else {
                        $controller->deletePost($postId);
                    }
                    break;
                
                case 'profile':
                    if($auth){
                        $controller->profilePage();

                    } else{
                        $view->makeUnauthenticatedPage();
                    }
                    break;
                
                case 'search':
                    $controller->search($_POST);
                    break;
                
                case 'disconnect':
                    $controller->disconnect();
                    break;

                default : 
                    $view->makeErrorPage("Router default error");
                    break;
            }
        } catch (Exception $e) {
            $view->makeErrorPage("Router ".$e);
        }

        if($auth){
            $authView->render();
        }
        else{
            $view->render();
        }
    }

    // URL Methods
    public function homePage() {
        return ".";
    }

    public function aboutPage() {
        return ".?action=about";
    }

    public function galleryPage() {
        return ".?action=gallery";
    }

    public function newAccount(){
        return ".?action=newAccount";
    }

    public function saveNewAccount(){
        return ".?action=saveNewAccount";
    }

    public function loginPage(){
        return ".?action=loginPage";
    }

    public function login(){
        return ".?action=login";
    }

    public function newPost(){
        return ".?action=newPost";
    }

    public function saveNewPost(){
        return ".?action=saveNewPost";
    }

    public function search(){
        return ".?action=search";
    }

    public function profilePage(){
        return ".?action=profile";
    }

    public function postPage($id) {
        return ".?post=$id&amp;action=postPage";
    }

    public function modifyPost($id) {
        return ".?post=$id&amp;action=modifyPostPage";
    }

    public function updateModifyPost($id, $postUserId) {
        if($_SESSION['id'] == $postUserId){
            return ".?post=$id&amp;action=modifyPost";
        }
        return ".?action=unauthenticated";
    }

    public function deletePost($id, $postUserId) {
        if($_SESSION['id'] == $postUserId){
            return ".?post=$id&amp;action=deletePost";
        }
        return ".?action=unauthenticated";
    }

    public function disconnect(){
        return ".?action=disconnect";
    }
}

?>