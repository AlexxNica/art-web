<?php

class Main extends Controller
{
    function Main()
    {
        parent::Controller();

		$this->load->model('Artwork_model','Artwork');
    }

    function index()
    {
		$data['show_middle_bar'] = true;
		
		$data['latest_artwork'] = $this->Artwork->get_public(10,0);
		$data['top_rated'] = $this->Artwork->top_rated(null,10,0);

        // Build the thing
        $this->layout->buildPage('main/home', $data);
    }
}

?>
