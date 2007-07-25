<?php

class Main extends Controller
{
    function Main()
    {
        parent::Controller();
    }

    function index()
    {
		$data['show_middle_bar'] = true;

        // Build the thing
        $this->layout->buildPage('main/home', $data);
    }
}

?>
