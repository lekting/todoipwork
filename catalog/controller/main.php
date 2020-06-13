<?php

class ControllerMain extends Controller {

    public function index($data = []) {

        //TODO: split files for more customizatins (head, footer etc... to difference files)
        $data['data'] = json_decode($this->load->controller('task@get'));

        return $this->load->view('main', $data);
    }

}