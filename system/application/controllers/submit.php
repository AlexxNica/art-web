<?php 

class Submit extends Controller {
	function Submit(){
		parent::Controller();
		
		$this->load->model('Artwork_model','Artwork');
		$this->load->model('License_model','License');
		$this->load->model('Category_model','Category');
		$this->load->model('Download_model','Download');
		$this->load->model('Moderation_model','Moderation');
		$this->load->model('Version_model','Version');
		
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
			$info['category_data'] = $this->Category->find($info['category']); 

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
					'version'	=> $this->validation->version,
					'name'		=> $this->validation->name,
					'description'	=> $info['description'],
					'state'			=> 1
				);
				
				/* add artwork to the DB */
				$artwork_id = $this->Artwork->add($fields);
				
				if ($info['is_original'] == 'yes'){
					$this->Version->add('',$artwork_id,$artwork_id);
				} else {
					$version = $this->Version->get($this->validation->original);
					$this->Version->add($version->path,$artwork_id,$version->tree_id);
				}
			
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
	
	function _handle_upload($type,$info){
		switch($type){
			case 'themes':
			case 'screenshots': break;
			default: die('Something went incredibly wrong!');
		}
		
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
			if ($type == 'themes' AND !$this->gallery->process_theme($upload_data[0],$info)){
				$this->upload->set_error('upload_theme_not_valid');
				$failed = TRUE;
			} elseif ($type == 'screenshots' AND !$this->gallery->process_screenshot($upload_data[0],$info)){
				$this->upload->set_error('upload_theme_not_valid');
				$failed = TRUE;	
			} else {
				$artwork_downloads = $this->gallery->data();
				
				//--
				//	Add artwork to DB
				//--
				$fields = array(
					'user_id' => $this->authentication->get_uid(),
					'category_id' => $info['category'],
					'name'		=> $this->validation->name,
					'description'	=> $info['description'],
					'state'			=> 1
				);
				
				if ($type=='themes'){
					$fields['license_id'] = $info['license'];
					$fields['version']	= $this->validation->version;
				}
				
				/* add artwork to the DB */
				$artwork_id = $this->Artwork->add($fields);
				
				if ($info['is_original'] == 'yes'){
					$this->Version->add('',$artwork_id,$artwork_id);
				} else {
					$version = $this->Version->get($this->validation->original);
					if (!$version){
						show_error('Oopps!! You better warn some admin!');
						die();
					}
					$this->Version->add($version->path,$artwork_id,$version->tree_id);
				}
				
				if (in_array($artwork_downloads[0]['file_ext'],array('.jpg','.png','.svg'))){
					/* process newly added artwork **/
					$thumb_name = 'thumb_'.$artwork_id;
					$this->gallery->create_thumbnail($thumb_name,$artwork_downloads[0]);
				}
			
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
			$info['is_original'] = $this->input->post('is_original');
			$info['description'] = htmlspecialchars($this->input->post('description'));
			
			$data['info'] = $info;
			
			$this->validation->set_error_delimiters('<div class="error">', '</div>');
			
			$rules['name']	= "trim|required|xss_clean";
			$rules['keywords'] = "trim|required";

			// set the rule of the parent_id validation
			if ($info['is_original']=='no'){
				$this->category_id = $info['category'];
				$rules['original'] = "trim|required|callback_parent_check";
			}
			$fields['name'] = 'Title';
			$fields['keywords'] = 'Keywords';
			$fields['original'] = 'Parend ID';
			
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
				
				$handled_info = $this->_handle_upload($type,$info);
				
			} elseif($this->input->post('screenshots')){
				$this->validation->set_rules($rules);
				$this->validation->set_fields($fields);
				
				$type = "screenshots";
				
				$handled_info = $this->_handle_upload($type,$info);
				
			} elseif($this->input->post('contest')){
				$type = "contests";
			} else {
				$type = -1;
				redirect('submit','refresh');
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
			$data['type'] = "screenshots";
			$type = 3;
			$categories = $this->Category->find_by_parent($type,'name desc');
		} else {
			redirect('submit/step1','refresh');
		}
		
		// Get the artwork categories
		$data['categories'] = $this->_prepare_for_listdown($categories);
		
		// Get the original artwork names
		/*$original_list = $this->Artwork->find_originals($this->authentication->get_uid(),$type);
		$data['originals'] = $this->_prepare_for_listdown($original_list,false);*/
		
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
	
	function parent_check($id){
		$artwork = $this->Artwork->find($id);
		if ($artwork->user_id != $this->authentication->get_uid()){
			$this->validation->set_message('parent_check', 'You can only submit updates of your own artwork!');
			return FALSE;
		}
		
		if ($artwork->category_id != $this->category_id){
			$this->validation->set_message('parent_check', 'The parent artwork isn\'t from the same category as the one you want to submit!');
			return FALSE;
		}
		
		if ($artwork->state != STATE_PUBLIC){
			$this->validation->set_message('parent_check', 'The parent needs to be already accepted!');
			return FALSE;
		}
		return TRUE;
	}
}

?>