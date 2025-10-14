<?php

/**
 * Template_ 클래스를 확장합니다.
 */
class MY_Template_ extends CI_Template_
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Initialize Preferences
     * @access    public
     */
    public function initialize()
    {
        $this->compile_check = TRUE;
        $this->compile_dir = APPPATH . "cache/_compile";
        $this->compile_ext = 'php';
        $this->skin = '';
        $this->notice = FALSE;
        $this->path_digest = FALSE;

        $this->template_dir = APPPATH . "views";
        $this->prefilter = '';
        $this->postfilter = '';
        $this->permission = 0755;
        $this->safe_mode = FALSE;
        $this->auto_constant = FALSE;

        $this->caching = FALSE;
        $this->cache_dir = APPPATH . "cache/_cache";
        $this->cache_expire = 3600;

        $this->scp_ = '';
        $this->var_ = array('' => array());
        $this->obj_ = array();
    }

    public function viewDefine($id, $file)
    {
        $this->define(array($id => $file));
    }

    public function viewDefined($id)
    {
        return $this->defined($id);
    }

    public function viewAssign($key, $value = NULL)
    {
        if (is_array($key) && $value == NULL) {
            $this->assign($key);
        } else {
            $this->assign(array($key => $value));
        }

        $this->defaultAssign();         //기본값 선언
    }


    public function defaultAssign()
    {
        // 기본값(상수)선언해줌
        $this->assign("CONTROLLERS", _CONTROLLERS);
        $this->assign("METHOD", _METHOD);
        $this->assign("IS_MOBILE", _IS_MOBILE);
    }

    public function viewPrint($id)
    {
        if ($this->viewDefined($id)) {
            $this->print_($id);
        }
    }

    /**
     * 출력하지 않고 html 내용을 변수로 담을 수 있다.
     */
    public function viewFetch($fid)
    {
        if ($this->defined($fid)) {
            return $this->fetch($fid);
        }
    }
}