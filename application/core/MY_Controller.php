<?php

class MY_Controller extends CI_Controller
{
    # Parameter reference
    public $params = array();

    public $cookies = array();

    public function __construct()
    {
        parent::__construct();
        # Parameter
        $this->params = $this->getParams();
        $this->cookies = $this->getCookies();

        $this->load->model('Board_model');
        $this->load->model('Comment_model');
        $this->load->model('Category_model');
        $this->load->model('User_model');
    }

    private function getParams()
    {

        $aParams = array_merge($this->doGet(), $this->doPost());

        //$this->sql_injection_filter($aParams);

        return $aParams;
    }


    private function getCookies()
    {

        return $this->doCookie();
    }


    private function doGet()
    {
        $aGetData = $this->input->get(NULL, TRUE);
        return (empty($aGetData)) ? array() : $aGetData;
    }

    private function doPost()
    {
        $aPostData = $this->input->post(NULL, TRUE);
        return (empty($aPostData)) ? array() : $aPostData;
    }

    private function doCookie()
    {
        $aCookieData = $this->input->cookie(NULL, TRUE);

        return (empty($aCookieData)) ? array() : $aCookieData;
    }

    public function js($file, $v = '')
    {
        if (is_array($file)) {
            foreach ($file as $iKey => $sValue) {
                $this->optimizer->setJs($sValue, $v);
            }
        } else {
            $this->optimizer->setJs($file, $v);
        }
    }

    public function externaljs($file)
    {
        if (is_array($file)) {
            foreach ($file as $iKey => $sValue) {
                $this->optimizer->setExternalJs($sValue);
            }
        } else {
            $this->optimizer->setExternalJs($file);
        }
    }

    public function css($file, $v = '')
    {
        if (is_array($file)) {
            foreach ($file as $iKey => $sValue) {
                $this->optimizer->setCss($sValue, $v);
            }
        } else {
            $this->optimizer->setCss($file, $v);
        }
    }

    /**
     *  변수 셋팅
     */
    public function setVars($arr = array())
    {
        foreach ($arr as $val) {
            $aVars;
        }

        $this->load->vars($aVars);
    }

    /**
     *  공통 전역 변수 셋팅
     */
    public function setCommonVars()
    {
        $aVars = array();

        $aVars['test'] = array("test1" => "test1");

        $this->load->vars($aVars);
    }

    /**
     *  페이지네이션
     */
    public function pagination($total_rows, $current_page, $per_page, $limit_page_links){
        
        $pagination_data = [];

        $pagination_data['offset'] = ($current_page - 1) * $per_page;

        $total_pages = ceil($total_rows / $per_page);
        if($total_pages < 1){
            $total_pages = 1;
        }

        $end_page = ceil($current_page / $limit_page_links) * $limit_page_links;
        if($total_pages < $end_page){
            $end_page = $total_pages;
        }

        $start_page = ($end_page - $limit_page_links) + 1;
        if($start_page < 1){
            $start_page = 1;
        }

        $pagination_data['prev'] = ($current_page == 1) ? false : true;
        $pagination_data['next'] = ($current_page == $total_pages || $total_rows  == 0) ? false : true;

        $pagination_data['total_pages'] = $total_pages;
        $pagination_data['current_page'] = $current_page;
        $pagination_data['start_page'] = $start_page;
        $pagination_data['end_page'] = $end_page;

        return $pagination_data;
    }
}
