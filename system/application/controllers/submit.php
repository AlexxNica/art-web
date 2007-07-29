<?php 

class Submit extends Controller {
	function Submit(){
		parent::Controller();
		
		$this->load->model('Artwork_model','Artwork');
		$this->load->model('License_model','License');
		$this->load->model('Category_model','Category');
		$this->load->model('Download_model','Download');
		$this->load->model('Moderation_model','Moderation');
		
		$this->authentication->authenticate();
	}
	
	function index(){
		redirect('/submit/step1','refresh');
	}
	
	// choose what type of artwork submit
	function step1(){
		$this->layout->buildPage("submit/step1");
	}
	
	// enter all the info related to the artwork
	function step2(){
		$this->load->library('validation');
		if ($this->input->post('upload')){
			
			if ($this->input->post('backgrounds')){
				$type = "backgrounds";
			} elseif($this->input->post('themes')){
				$type = "themes";
			} elseif($this->input->post('screenshots')){
				$type = "screenshots";
			} else {
				$type = -1;
			}
			
			$info['category'] = $this->input->post('category');
			$info['license'] = $this->input->post('license');
			$info['original'] = $this->input->post('original');
			$info['description'] = htmlspecialchars($this->input->post('description'));
			
			$data['info'] = $info;
			
			$this->validation->set_error_delimiters('<div class="error">', '</div>');

			$rules['name']	= "trim|required|xss_clean";

			//if ($this->input->post('is_original') == 'no')
			$rules['version'] = "trim|required";
			
			$rules['keywords'] = "trim|required";
			$rules['is_original'] = "";

			$fields['name'] = 'Title';
			$fields['version'] = 'Version';
			$fields['keywords'] = 'Keywords';

			$this->validation->set_fields($fields);
			$this->validation->set_rules($rules);
			
			$this->load->library('Upload');
			
			$userfiles = array();
			foreach($_FILES as $key => $file){
				if ($file['name'] != null){
					$userfiles[] = $key;
				}
			}
			
			if ($this->validation->run() && $this->upload->do_multiple_upload($userfiles)){
				$upload_data = $this->upload->multiple_data();
				$failed = FALSE;
				switch($type){
					case "backgrounds": 
							$this->load->library('gallery');
							$resolutions_available = $this->Resolution->get_all();

							$info['name'] = $this->validation->name;
							$info['username'] = $this->authentication->get_username();

							if (!$this->gallery->process_images($resolutions_available,$upload_data,$info)){
								$this->upload->set_error('upload_images_resolution');
								$failed = TRUE;
							} else {
								$artwork_downloads = $this->gallery->data();
								
							}
							break;
					default:
							break;
					}
				
					if (!$failed){
					//--
					//	Add artwork to DB
					//--
					$fields = array(
						'user_id' => $this->authentication->get_uid(),
						'category_id' => $info['category'],
						'license_id' => $info['license'],
						'original_id' => $info['original'],
						'version'	=> $this->validation->version,
						'name'		=> $this->validation->name,
						'description'	=> $info['description'],
						'state'			=> 1
					);
				
					$artwork_id = $this->Artwork->add($fields);
				
					// process newly added artwork
					switch($type){
						case "backgrounds":
								
								$thumb_name = 'thumb_'.$artwork_id;
								$this->gallery->create_thumbnail($thumb_name,$artwork_downloads[0]);
								
								
								$this->Download->create_by_resolution($artwork_id,$artwork_downloads);
								break;
						default:
								break;
					}
					
					$this->Moderation->add_to_queue($artwork_id);
					
					redirect('submit/step3','refresh');
		
				}
			}
			
			$data['error'] = $this->upload->display_errors();
		}
		
		
		if ($this->input->post('backgrounds')){
			$data['type'] = "backgrounds";
			$type = 1;
			$categories = $this->Category->find_by_parent($type);
			$data['resolutions_available'] = $this->Resolution->get_all();

		} else if ($this->input->post('themes')){
			$data['type'] = "themes";
			$type = 2;
			$categories = $this->Category->find_by_parent($type);
			
		} else if ($this->input->post('screenshots')){
			$type = 3;
			$data['type'] = "screenshots";
		} else {
			redirect('submit/step1','refresh');
		}
		
		// Get the artwork categories
		$data['categories'] = $this->prepare_for_listdown($categories);
		
		// Get the original artwork names
		$original_list = $this->Artwork->find_originals();
		$data['originals'] = $this->prepare_for_listdown($original_list,false);
		
		// get and prepare the license list 
		$license_list = $this->License->find_all();
		$data['licenses'] = $this->prepare_for_listdown($license_list);
		
		
		$this->layout->buildPage("submit/step2", $data);
	}
	
	
	function step3(){
		
		$data['step3'] = null;
		
		$this->layout->buildPage("submit/step3", $data);
	}
	
	
	function prepare_for_listdown($info, $empty=true){
		$tmp_array = array();
		if (!$empty) $tmp_array['-1'] = '-- n/a --';
		if ($info){
			foreach($info as $unity){
				$tmp_array[$unity->id] = $unity->name;
			}
			return $tmp_array;
		}
		return $tmp_array;
	}
}

?>