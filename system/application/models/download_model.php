<?php

class Download_model extends Model{
	
	function Download_model(){
		parent::Model();
		
		$this->load->model('Resolution_model','Resolution');
	}
	
	function find_by_artwork($artwork_id){
		$sql = 'Select download.*,resolution.width,resolution.height 
				From (	download left join download_resolution 
						on download.id = download_resolution.download_id) 
				left join resolution 
				on download_resolution.resolution_id = resolution.id
				where download.artwork_id = '.$this->db->escape($artwork_id);
		$query = $this->db->query($sql);
		
		return $query->result();
	}
	
	
	/**
	 * Create by Resolution
	 * 
	 * Receives an array with the resolutions that where generated
	 * for a background and the corresponding filename.
	 * 
	 * For each resolution a new entry is added to the download table.
	 */
	function create_by_resolution($artwork_id,$resolutions){
		
		$download = array(
			'artwork_id' => $artwork_id,
			'download_count' => 0
		);
		
		foreach($resolutions as $resolution){
			$download['file'] = $resolution[2];
			
			$download_id = $this->add($download);
			
			// associate download with a resolution
			$resolution = $this->Resolution->find_by_resolution($resolution[0],$resolution[1]);
			
			if ($resolution)
				$this->db->insert('download_resolution',array('download_id'=>$download_id,'resolution_id'=>$resolution->id));
			
		}
	}
	
	function add($fields){
		$this->db->insert('download',$fields);
		
		return $this->db->insert_id();
	}
}

?>