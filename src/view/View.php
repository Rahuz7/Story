<?php

require_once("model/Post.php");
require_once("Router.php");

    class View{
        protected $title;
        protected $content;
        protected $style;
        protected $router;

        public function __construct(Router $router){
            $this->router = $router;
            $this->style = "";
            $this->title = null;
            $this->content = null;
        }

        public function makeHomePage(){
            $this->title = "Home";

            $this->content = "<h1 class='title'>Tell a Story.</h1>";
            $this->content .= $this->makeCredButtons();
        }

        public function makeAboutPage(){
            $this->title = "About";

            $this->content = "
            <h1 class='title'>Project Idea</h1>
            <p>A platform where users can share short stories or jokes.
            The title of each post is the setup for the story or the joke, and the detailed page reveals the punch line.</p>
            
            <h1 class='title'>Group Members</h1>
            <ul>
                <li>MERZOUGUI Dhia</li>
                <li>MERCIER Julien</li>
            </ul>

            <h1 class='title'>Add-ons Developed</h1>
            <ul>
                <li>Website is responsive</li>
                <li>Website has a search function</li>
            </ul>
            ";
        }

        public function makeSignUpPage(AccountBuilder $builder){
            $this->title = "Sign Up";
            $this->content = "<h1 class='title'>Sign Up</h1>";
            $this->content .= $this->makeForm($builder, $this->router->saveNewAccount(), "Sign up");
        }

        public function makeLoginPage(AccountBuilder $builder){
            $this->title = "Login";
            $this->content = "<h1 class='title'>Login</h1>";
            $this->content .= $this->makeForm($builder, $this->router->login(), "Login");
        }

        public function makeGalleryPage($data){
            $this->title = "Gallery";

            $this->content = "";

            $this->content .= $this->makeSearchBar();

            $this->content .= "<div class='posts'>";
            foreach($data as $row){
                $borderColor = $this->getBorderColor($row);
                $this->content .= "<article class='post $borderColor'><p class='setup'>".$row->Setup."..."."</p><button class='readMoreButton'><a href='.?action=unauthenticated'>Read more</a></button></article>";
            }
            $this->content .= "</div>";
        }

        public function makeAccountCreatedPage(){
            $this->title = "Welcome!";

            $this->content = "<h1 class='title'>Account Created. Welcome to the website!</h1>";
            $this->content = "<button class='coloredTextButton homeButton'><a href='".$this->router->login()."'>Login</a></button>";
        }

        public function makeWelcomePage($username){
            $this->title = "My Profile";

            $this->content = "<h1 class='title'>Welcome, ".$username."!</h1>";
            $this->content .= "<button class='coloredBackgroundButton'><a href='".$this->router->galleryPage()."'>Browse Posts</a></button>";
        }

        public function makeUnauthenticatedPage(){
            $this->title = "Unauthenticated";

            $this->content = "<h1 class='title'>Unauthenticated.</h1>";
            $this->content .= "<p>Only authenticated people may read the punchline of the stories. Please sign up or login to see the rest.</p>";
            $this->content .= $this->makeCredButtons();
        }

        public function makeNotFoundPage(){
            $this->title = "Not found";

            $this->content = "<h1 class='title'>No user with this name was found.</h1>";
            $this->content .= $this->makeSearchBar();

        }

        public function makeErrorPage($errorLocation="Unknown"){
            $this->title = "Error";

            $this->content = "<h1 class='title'>Error Page.</h1><br>";
            $this->content .= "<p>Location: ".$errorLocation."</p>";
        }

        public function makeCredButtons(){
            return "<button class='coloredBackgroundButton homeButton'><a href='".$this->router->newAccount()."'>Sign up</a></button>
            <button class='coloredTextButton homeButton'><a href='".$this->router->loginPage()."'>Login</a></button>";
        }

        public function makeSearchBar(){
            return "
                <form action='".$this->router->search()."' method='POST'>
                    <input class='searchBox' type='text' name='search' value='' placeholder='Search Username'>
                </form>
        ";
        }

        public function makeForm(AccountBuilder $builder, $action, $text){
            return "
            <form action='".$action."' method='POST'>"
            .self::getAccountFormFields($builder).
            "<button class='homeButton coloredBackgroundButton'>".$text."</button>
            </form>
            ";
        }

        // ------------ Non-Page stuff ------------
        protected function getMenu() {
            return array(
                "Home" => $this->router->homePage(),
                "Browse Posts" => $this->router->galleryPage(),
                "About" => $this->router->aboutPage(),
            );
        }

        public function getBorderColor($row){
            $type = $row->Type;
            $borderColor = "";

            switch($type){
                case "Short story":
                    $borderColor = "shortStory";
                    break;
                case "Short horror story":
                    $borderColor = "shortHorrorStory";
                    break;
                case "Joke":
                    $borderColor = "joke";
                    break;
            }

            return $borderColor;
        }

        protected function getAccountFormFields(AccountBuilder $builder) {
            $loginRef = $builder->getLoginRef();
            $s = "";
    
            $s .= '<p><label>Username: <input type="text" name="'.$loginRef.'" value="" required>';
            $err = $builder->getErrors($loginRef);
            if ($err !== null)
                $s .= ' <span class="error">'.$err.'</span>';
            $s .="</label></p>\n";
    
            $passwordRef = $builder->getpasswordRef();
            $s .= '<p><label>Password: <input type="password" name="'.$passwordRef.'" value="" required>';
            $err = $builder->getErrors($passwordRef);
            if ($err !== null)
                $s .= ' <span class="error">'.$err.'</span>';
            $s .= '</label></p>'."\n";
            return $s;
        }

        public function render(){
            if ($this->title === null || $this->content === null) {
                $this->makeErrorPage("View Empty Error");
            }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="skin/style.css">
    <link rel="icon" href="skin/favicon2.png">

    <style>
        <?php echo $this->style; ?>
    </style>

    <title>
        <?php echo $this->title; ?>
    </title>
</head>
<body>

    <nav>
        <ul>
            <?php
                foreach ($this->getMenu() as $text => $link) {
                    echo "<li><a href=\"$link\">$text</a></li>";
                }
            ?>
        </ul>
    </nav>

    <main>
        <?php echo $this->content; ?>
    </main>

    <footer>
        <p>Groupe 3. All Rights Reserved.</p>
    </footer>
    
</body>
</html>

<?php 
    // Closing off the class and render() function
    }
}
?>