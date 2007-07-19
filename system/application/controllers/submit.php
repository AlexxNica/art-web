<?php 

class Submit extends Controller {
	function Submit(){
		parent::Controller();
		
		$this->load->model('Artwork_model','Artwork');
		$this->load->model('License_model','License');
		$this->load->model('Category_model','Category');
		$this->load->model('Download_model','Download');
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
			
			
			$this->load->library('upload');
			
			if ($this->validation->run() && $this->upload->do_upload()){
				$upload_data = $this->upload->data();
				$new_filename = time().'_'.$this->validation->name.'_by_'.$this->authentication->get_username().$upload_data['file_ext'];
				
				// rename file 
				rename($upload_data['full_path'],$upload_data['file_path'].$new_filename);
				
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
					'state'			=> 1,
					'date_added'	=> time()
				);
				
				$artwork_id = $this->Artwork->add($fields);
				
				// process newly added artwork
				if ($this->input->post('backgrounds')){
					
					$config['image_library'] = 'GD';
					$config['source_image'] = $upload_data['file_path'].$new_filename;
					$config['create_thumb'] = FALSE;
					$config['maintain_ratio'] = TRUE;

					$this->load->library('gallery');
					$resolutions_available = array(
													array('800','600'),
													array('1024','768'),
													array('1280','1024'),
													array('1440','900')
												);
												
					$file['path'] = $upload_data['file_path'];
					$file['name'] = $new_filename;
					$resolutions_created = $this->gallery->create_size_variations($file,$resolutions_available);
					
					// next step: add images has downloads
					$this->Download->create_by_resolution($artwork_id,$resolutions_created);
					
				} else {
					// process the other type of artwork
				}
				
				redirect('submit/step3','refresh');
			}
			
			$data['error'] = $this->upload->display_errors();
		}
		
		
		if ($this->input->post('backgrounds')){
			$data['type'] = "backgrounds";
			$type = 1;
			$categories = $this->Category->find_by_parent($type);

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