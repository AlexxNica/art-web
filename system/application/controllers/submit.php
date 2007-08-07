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
	
	function _handle_backgrounds($info){
		
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
				
				/* add artwork to the DB */
				$artwork_id = $this->Artwork->add($fields);
			
				/* process newly added artwork **/
				$thumb_name = 'thumb_'.$artwork_id;
				$this->gallery->create_thumbnail($thumb_name,$artwork_downloads[0]);
				
				/* add new entries to the download table */			
				$this->Download->create_by_resolution($artwork_id,$artwork_downloads);
				
				/* Add background to the moderation Queue */
				$this->Moderation->add_to_queue($artwork_id);
				
				redirect('submit/step3','refresh');
	
			}
		}
		
		$data['error'] = $this->upload->display_errors();
	
		return $data;
	}
	
	function _handle_themes($info){
		$data = array();
		$this->load->library('Upload');
		
		$userfiles = array();
		foreach($_FILES as $key => $file){
			if ($file['name'] != null){
				$userfiles[] = $key;
			}
		}
		
		$config['allowed_types'] = "";
		$this->upload->config_this($config);
		
		if ($this->validation->run() && $this->upload->do_multiple_upload($userfiles)){
			$upload_data = $this->upload->multiple_data();
			$failed = FALSE;

			$this->load->library('gallery');

			$info['name'] = $this->validation->name;
			$info['username'] = $this->authentication->get_username();
			$info['category_data'] = $this->Category->find($info['category']); 

			/* validate the file submited ... */
			if (!$this->gallery->process_theme($upload_data[0],$info)){
				$this->upload->set_error('upload_theme_not_valid');
				$failed = TRUE;
			} else {
				$artwork_downloads = $this->gallery->data();
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
				
				/* add artwork to the DB */
				$artwork_id = $this->Artwork->add($fields);
			
				
				/* add new entries to the download table */			
				$this->Download->create($artwork_id,$artwork_downloads[0]);
				
				/* Add background to the moderation Queue */
				$this->Moderation->add_to_queue($artwork_id);
				
				redirect('submit/step3','refresh');
	
			}
		}
		
		$data['error'] = $this->upload->display_errors();
	
		return $data;
	}
	
	// enter all the info related to the artwork
	function step2(){
		$this->load->library('validation');
		
		if ($this->input->post('upload')){
			
			$info['category'] = $this->input->post('category');
			$info['license'] = $this->input->post('license');
			$info['original'] = $this->input->post('original');
			$info['description'] = htmlspecialchars($this->input->post('description'));
			
			$data['info'] = $info;
			
			$this->validation->set_error_delimiters('<div class="error">', '</div>');
			
			$rules['name']	= "trim|required|xss_clean";
			$rules['keywords'] = "trim|required";
			
			$fields['name'] = 'Title';
			$fields['keywords'] = 'Keywords';
			
			if ($this->input->post('backgrounds')){
				
				$rules['version'] = "trim|required";
				$fields['version'] = 'Version';
				
				$this->validation->set_rules($rules);
				$this->validation->set_fields($fields);
				
				$type = "backgrounds";
				
				$handled_info = $this->_handle_backgrounds($info);
				
			} elseif($this->input->post('themes')){
				
				$rules['version'] = "trim|required";
				$fields['version'] = 'Version';
				
				$this->validation->set_rules($rules);
				$this->validation->set_fields($fields);
				
				$type = "themes";
				
				$handled_info = $this->_handle_themes($info);
				
			} elseif($this->input->post('screenshots')){
				$type = "screenshots";
			} elseif($this->input->post('contest')){
				$type = "contests";
			} else {
				$type = -1;
			}
			
			$data = array_merge($handled_info,$data);
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
		$data['categories'] = $this->_prepare_for_listdown($categories);
		
		// Get the original artwork names
		$original_list = $this->Artwork->find_originals($this->authentication->get_uid());
		$data['originals'] = $this->_prepare_for_listdown($original_list,false);
		
		// get and prepare the license list 
		$license_list = $this->License->find_all();
		$data['licenses'] = $this->_prepare_for_listdown($license_list);
		
		
		$this->layout->buildPage("submit/step2", $data);
	}
	
	
	function step3(){
		
		$data['step3'] = null;
		
		$this->layout->buildPage("submit/step3", $data);
	}
	
	
	function _prepare_for_listdown($info, $empty=true){
		$tmp_array = array();
		if (!$empty) $tmp_array['-1'] = '-- n/a --';
		if ($info){
			foreach($info as $unit){
				$tmp_array[$unit->id] = $unit->name;
			}
			return $tmp_array;
		}
		return $tmp_array;
	}
}

?>