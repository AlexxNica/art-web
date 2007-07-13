<?php 

class Submit extends Controller {
	function Submit(){
		parent::Controller();
		
		$this->load->model('Artwork_model','Artwork');
	}
	
	function index(){
		redirect('/submit/step1','refresh');
	}
	
	function step1(){
		$this->layout->buildPage("submit/step1");
	}
	
	function step2(){
		/**
		 * TODO:
		 * 	- get the categories available for each type of artwork
		 */
		if ($this->input->post('wallpaper')){
			$data['type'] = "wallpaper";
			$data['categories'] = array(
				'0' => 'GNOME',
				'1' => 'Abstract',
				'2' => 'Nature',
				'3' => 'Miscellaneous'
				);
		} else if ($this->input->post('screenshot')){
			$data['type'] = "screenshot";
		} else if ($this->input->post('theme')){
			$data['type'] = "theme";
			$data['categories'] = array(
				'0' => 'Applications',
				'1' => 'Desktop Theme',
				'2' => 'GTK+ Engines',
				'3' => 'Icon',
				'4' => 'Login Manager(gdm)',
				'5' => 'Splash Screen',
				'6' => 'Windows Border(Metacity)'
				);
		} else {
			redirect('submit/step1','refresh');
		}
		
		$data['originals'] = $this->Artwork->find_originals();
		
		$this->layout->buildPage("submit/step2", $data);
	}
}

?>