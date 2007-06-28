<?php

class Main extends Controller
{
    function Main()
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
		$data['show_middle_bar'] = true;

        // Build the thing
        $this->layout->buildPage('main/home', $data);
    }
}

?>
