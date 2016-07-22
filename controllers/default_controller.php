<?php

class CI_default_controller extends CI_Controller {

        public function index()
        {
		$data['name'] = 'Dmitry';
		$this->view('index', $data);
        }
}

?>
