<?php

class Welcome extends Controller
{
    function Welcome()
    {
        parent::Controller();
    }

    function index()
    {
        /* 
         * Do whatever you want and then...
         */

        // Set the template valiables
        $data['whatever'] = "If you can read me YATS The Layout Library is working correctly. Good job!";

        // Build the thing
        $this->layout->buildPage('welcome/home', $data);
    }
}

?>
