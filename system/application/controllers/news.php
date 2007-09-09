<?php

class News extends Controller
{
    function News()
    {
        parent::Controller();
        $this->load->model('News_model','News');
    }

    function index($start = 0)
    {
        $items_per_page = 5;

        $this->load->library ('pagination');
        $config['base_url'] = '/news/index/';
        $config['total_rows'] = $this->News->get_total();
        $config['per_page'] = $items_per_page;
        $this->pagination->initialize($config);

        $data['news'] = $this->News->page_list ($items_per_page, $start);
        $data['pagination'] = $this->pagination->create_links();
        $this->layout->buildPage('news/list', $data);
    }
}

?>
