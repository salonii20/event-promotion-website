<?php

//Include our HTML Page Class
require_once("oo_page.inc.php");

class MasterPage
{
    //-------FIELD MEMBERS----------------------------------------
    private $_htmlpage;     //Holds our Custom Instance of an HTML Page
    private $_dynamic_1;    //Field Representing our Dynamic Content #1
    private $_dynamic_2;    //Field Representing our Dynamic Content #2
    private $_dynamic_3;    //Field Representing our Dynamic Content #3
   
    
    //-------CONSTRUCTORS-----------------------------------------
    function __construct($ptitle)
    {
        $this->_htmlpage = new HTMLPage($ptitle);
        $this->setPageDefaults();
        $this->setDynamicDefaults(); 
       
    }
    
    //-------GETTER/SETTER FUNCTIONS------------------------------
    public function getDynamic1() { return $this->_dynamic_1; }
    public function getDynamic2() { return $this->_dynamic_2; } 
    public function getDynamic3() { return $this->_dynamic_3; }
    public function setDynamic1($phtml) { $this->_dynamic_1 = $phtml; }
    public function setDynamic2($phtml) { $this->_dynamic_2 = $phtml; } 
    public function setDynamic3($phtml) { $this->_dynamic_3 = $phtml; }
    public function getPage(): HTMLPage { return $this->_htmlpage; } 
    
    //-------PUBLIC FUNCTIONS-------------------------------------
                   
    public function createPage()
    {
       //Create our Dynamic Injected Master Page
       $this->setMasterContent();
       //Return the HTML Page..
       return $this->_htmlpage->createPage();
    }
    
    public function renderPage()
    {
       //Create our Dynamic Injected Master Page
       $this->setMasterContent();
       //Echo the page immediately.
       $this->_htmlpage->renderPage();
    }
    
    public function addCSSFile($pcssfile)
    {
        $this->_htmlpage->addCSSFile($pcssfile);
    }
    
    public function addScriptFile($pjsfile)
    {
        $this->_htmlpage->addScriptFile($pjsfile);
    }
    
    //-------PRIVATE FUNCTIONS-----------------------------------    
    private function setPageDefaults()
    {
        $this->_htmlpage->setMediaDirectory("css", "js", "fonts", "img", "data");
        $this->addCSSFile("https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"); // Include Bootstrap CSS
        $this->addScriptFile("https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"); // Include Bootstrap Bundle JS which includes Popper
    
        $this->addCSSFile("site.css"); // Your site's specific stylesheet
    }
    
    private function setDynamicDefaults()
    {
       
        //Set the Three Dynamic Points to Empty By Default.
        $this->_dynamic_1 = "";
        $this->_dynamic_2 = "";
        $this->_dynamic_3 = "";
    }
    
    private function setMasterContent()
    {
        $tmasterpage = <<<FORM
        <div id="wrapper" class="container">
        <header class="bg-light p-3 mb-3 border-bottom">
            <h1>Club Event Promotion Site</h1>
        </header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="Event.php">Events</a></li>
                        <li class="nav-item"><a class="nav-link" href="Artist.php">Artists</a></li>
                        <li class="nav-item"><a class="nav-link" href="Profile.php">Profile</a></li>
                        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="signup.php">Sign up</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    
        <!-- Dynamic Content -->
        <div class="row">
            <div class="col-md-12">
                {dynamic1}
            </div>
            <div class="col-md-12">
                {dynamic2}
            </div>
            <div class="col-md-12">
                {dynamic3}
            </div>
        </div>
        <!-- End of Dynamic Content -->
        
        <footer class="footer mt-4 py-3 bg-light">
            <div class="container">
                <ul class="list-unstyled d-flex justify-content-between">
                    <li><a href="about.php">About us</a></li>
                    <li><a href="contact.php">Contact us</a></li>
                    <li>Copyright &copy; 2024</li>
                </ul>
            </div>
        </footer>	
    </div>
    FORM;
    
        // Replace placeholders with actual dynamic content
        $tmasterpage = str_replace("{dynamic1}", $this->_dynamic_1, $tmasterpage);
        $tmasterpage = str_replace("{dynamic2}", $this->_dynamic_2, $tmasterpage);
        $tmasterpage = str_replace("{dynamic3}", $this->_dynamic_3, $tmasterpage);
    
        $this->_htmlpage->setBodyContent($tmasterpage); 
    }
    
}

?>