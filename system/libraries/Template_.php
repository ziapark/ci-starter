<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'Template_/Template_.class.php';

class CI_Template_ extends Template_ {

    /**
     * Constructor
     * 
     * @access    public
     */
    public function __construct()
    {
        $this->initialize();

        log_message('debug', "Template Underscore Parser Class Initialized");
    }
    
    /**
     * Initialize Preferences
     * 각 프로젝트 별로 상속 받아 클래스(MY_)에서 재정의해서 쓰세요.
     *
     * @access    public
     */
    public function initialize()
    {
        $this->compile_check    = TRUE;
        $this->compile_dir     = "compile";
        $this->compile_ext     = 'php';
        $this->skin        = '';
        $this->notice       = FALSE;
        $this->path_digest     = FALSE;

        $this->template_dir    = "views";
        $this->prefilter      = '';
        $this->postfilter     = '';
        $this->permission     = 0755;
        $this->safe_mode      = FALSE;
        $this->auto_constant    = FALSE;

        $this->caching       = TRUE;
        $this->cache_dir      = "cache";
        $this->cache_expire    = 3600;

        $this->scp_        = '';
        $this->var_        = array(''=>array());
        $this->obj_        = array();
    }    

}