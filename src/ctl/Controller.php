<?php

    require_once("model/Post.php");
    require_once("model/Account.php");

    require_once("model/PostBuilder.php");
    require_once("model/AccountBuilder.php");

    require_once("model/PostStorageDB.php");
    require_once("model/AccountStorageDB.php");

    require_once("view/View.php");
    require_once("view/AuthView.php");

    class Controller{
        protected $view;
        protected $authView;
        protected $postDB;
        protected $accountDB;
        protected $postBuilder;
        protected $accountBuilder;

        public function __construct(View $view, AuthView $authView, PostStorageDB $postDB, AccountStorageDB $accountDB){
            
            $this->view = $view;
            $this->authView = $authView;

            $this->postDB = $postDB;
            $this->accountDB = $accountDB;

            $this->postBuilder = key_exists('postBuilder', $_SESSION) ? $_SESSION['postBuilder'] : null;
            $this->accountBuilder = key_exists('accountBuilder', $_SESSION) ? $_SESSION['accountBuilder'] : null;

        }

        public function __destruct(){
            $_SESSION['accountBuilder'] = $this->accountBuilder;
		    $_SESSION['postBuilder'] = $this->postBuilder;
        }

        public function homePage(){
            $this->view->makeHomePage();
        }

        public function aboutPage(){
            if($_SESSION['auth']){
                $this->authView->makeAboutPage();
            }
            $this->view->makeAboutPage();
        }

        public function newAccount(){
            if ($this->accountBuilder === null) {
                $this->accountBuilder = new AccountBuilder();
            }
            $this->view->makeSignUpPage($this->accountBuilder);
        }

        public function saveNewAccount(array $data){
            $this->accountBuilder = new AccountBuilder($data);
            if ($this->accountBuilder->isValid()) {
                $account = $this->accountBuilder->createAccount();
                $accountId = $this->accountDB->create($account);
                $this->AccountBuilder = null;
                $this->view->makeAccountCreatedPage();
            } else {
                $this->view->makeErrorPage("saveNewAccount() Error");
            }
        }

        public function loginPage(){
            if ($this->accountBuilder === null) {
                $this->accountBuilder = new AccountBuilder();
            }
            $this->view->makeLoginPage($this->accountBuilder);
        }

        public function login(array $data){
            if($this->accountDB->checkauth($data["login"], $data["password"])) {
                $userData = $this->accountDB->read($data["login"]);
                
                $_SESSION['auth'] = true;
                $_SESSION['id'] = $userData[0]->User_id;
                $_SESSION['username'] = $userData[0]->Username;
                
                $this->view->makeWelcomePage($userData[0]->Username);
            }
            else{
                $this->view->makeErrorPage();
            }
        }

        public function galleryPage(){
            if($_SESSION['auth']){
                $data = $this->postDB->readAll();
                $this->authView->makeAuthGalleryPage($data);
            }
            else{
                $data = $this->postDB->readAll();
                $this->view->makeGalleryPage($data);
            }
        }

        public function postPage($id){
            $post = $this->postDB->read(intval($id));
            if ($post === null) {
                $this->authView->makeErrorPage("Controller postPage()");
            } else {
                $username = $this->accountDB->getUserFromId(intval($post[0]->User_id));
                $this->authView->makePostPage($post, $username[0]->Username);
            }
        }

        public function profilePage(){
            $data = $this->postDB->readUser($_SESSION['username']);
            $this->authView->makeProfilePage($data);
        }

        public function newPost(){
            if ($this->postBuilder === null) {
                $this->postBuilder = new PostBuilder();
            }
            $this->authView->makeCreatePostPage($this->postBuilder);
        }

        public function saveNewPost(array $data){
            $this->postBuilder = new PostBuilder($data);

            if ($this->postBuilder->isValid()) {
                $post = $this->postBuilder->createPost();
                $postId = $this->postDB->create($post);
                $this->postBuilder = null;
                $this->authView->makePostCreatedPage();
            } else {
                $this->view->makeErrorPage("saveNewPost() Error");
            }
        }

        public function modifyPostPage($id){
            if ($this->postBuilder === null) {
                $this->postBuilder = new PostBuilder();
            }

            $arrayPost = $this->postDB->read(intval($id));
            $p = new Post($arrayPost[0]->Setup, $arrayPost[0]->Punchline, $arrayPost[0]->Type, $arrayPost[0]->Post_id);
            $builder = $this->postBuilder->buildFromPost($p);

            $this->authView->makeModifyPostPage($p);
            }
            
        public function updateModifyPost($postId, $data) {
            $p = new Post($data['Setup'], $data['Punchline'], $data['type'], $postId);
            $this->postDB->update($postId, $p);
            $this->postPage($postId);
            }

        public function deletePost($id){
            $this->postDB->delete($id);
            $this->authView->makePostDeletedPage();
        }

        public function search($data){
            try{
                $results = $this->postDB->readUser($data['search']);

                if($_SESSION['auth']){
                    $this->authView->makeAuthGalleryPage($results);
                }
                else{
                    $this->view->makeGalleryPage($results);
                }
            }
            catch (\Throwable $th) {
                if($_SESSION['auth']){
                    $this->authView->makeNotFoundPage();
                }
                else{
                    $this->view->makeNotFoundPage();
                }
            }
        }

        public function disconnect(){
            $_SESSION['auth'] = false;
            $this->authView->makeDisconnectedPage($_SESSION['username']);
        }
    }
?>