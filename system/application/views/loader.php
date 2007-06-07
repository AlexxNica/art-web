<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Loader File
 *
 * @package		YATS -- The Layout Library
 * @subpackage	Views
 * @category	Template
 * @author		Mario Mariani
 * @copyright	Copyright (c) 2006-2007, mariomariani.net All rights reserved.
 * @license		http://svn.mariomariani.net/yats/trunk/license.txt
 */

$this->load->view($data['settings']['views'] . $data['settings']['commons'] . "header", $data);
$this->load->view($data['settings']['views'] . $data['settings']['content'] . "$view",  $data);
$this->load->view($data['settings']['views'] . $data['settings']['commons'] . "footer", $data);

?>