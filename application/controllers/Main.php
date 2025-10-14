<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Main extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->load->view('boardList');
    }

    public function boardList()
    {
        $this->load->view('boardList');
    }

    public function boardDetail()
    {
        $this->load->view('boardDetail');
    }

    public function login()
    {
        $this->load->view('login');
    }

    public function sign()
    {
        $this->load->view('sign');
    }

    public function boardAdd()
    {
        $this->load->view('boardAdd');
    }
}
?>