<?php

/**
 * Optimizer
 *
 * CSS, JS 파일들의 최적화를 제공합니다.
 */
class Optimizer
{
    private $ci = NULL;

    /**
     * JS 파일 목록
     */
    private $aJs = array();

    /**
     * 외부 JS 파일 목록
     */
    private $aExternalJs = array();

    /**
     * CSS 파일 목록
     */
    private $aCss = array();

    public function __construct()
    {
        $this->ci =& get_instance();
    }

    /**
     * JS, CSS 태그 반환
     */
    public function makeOptimizerScriptTag()
    {
        $sJsTag = $this->_makeTagJs();

        $sCssTag = $this->_makeTagCss();

        return array('js_optimizer' => $sJsTag, 'css_optimizer' => $sCssTag);
    }

    /**
     * JS 태그
     *
     * @param $sJs
     * @return string
     */
    private function _makeTagJs()
    {
        if (empty($this->aJs)) return '';

        $sResult = '';
        foreach ($this->aJs as $iKey => $sValue) {
            // "../"로 접근할경우 RESOURCEPATH부터 경로를 시작한다!
            // 단! 상세 주소를 다 기재해줘야 한다.
            // ex) 기존:file.js   상대경로:../common/js/file.js
            $resource = substr($sValue, 0, 3) == "../" ? substr($sValue, 3) : APPFOLDER . "/js/" . $sValue;

            $sResult .= "<script type='text/javascript' src='/resource/{$resource}'></script>";
        }

        # 외부 JS 로드
        if (!empty($this->aExternalJs)) {
            foreach ($this->aExternalJs as $iKey => $sValue) {
                $sResult .= sprintf('<script type="text/javascript" src="%s"></script>', $sValue);
            }
        }

        return $sResult;
    }

    /**
     * CSS 태그
     *
     * @param $sCss
     * @return string
     */
    private function _makeTagCss()
    {
        if (empty($this->aCss)) return '';

        $sResult = '';
        foreach ($this->aCss as $iKey => $sValue) {
            $ext = '';
            $ext = substr(strrchr($sValue, "/"), 1);    //확장자앞 .을 제거하기 위하여 substr()함수를 이용
            $ext_pos = substr(strrpos($ext, "."), 0);
            $ext = substr($ext, 0, $ext_pos);

            // "../"로 접근할경우 RESOURCEPATH부터 경로를 시작한다!
            // 단! 상세 주소를 다 기재해줘야 한다.
            // ex) 기존:file.js   상대경로:../common/js/file.js
            $resource = substr($sValue, 0, 3) == "../" ? substr($sValue, 3) : APPFOLDER . "/css/" . $sValue;

            if ($ext == 'print') {
                $sResult .= "<link rel='stylesheet' type='text/css' href='/resource/{$resource}' media='print' />";
            } else {
                $sResult .= "<link rel='stylesheet' type='text/css' href='/resource/{$resource}' />";
            }
        }

        return $sResult;
    }

    public function setJs($file, $v = '')
    {
        if (empty($file)) {
            return;
        }

        // 단! 상세 주소를 다 기재해줘야 한다.
        // ex) 기존:file.js   상대경로:../common/js/file.js
        if (substr($file, 0, 3) == "../") {
            $fileFullPath = RESOURCEPATH . substr($file, 3);
        } else {
            $fileFullPath = RESOURCEPATH . APPFOLDER . '/js/' . $file;
        }

        if (file_exists($fileFullPath)) {
            if ($v) {
                $file = $file . "?v={$v}";
            }
            $this->aJs[] = $file;
        } else {
            echo "[ADMIN] OPTIMIZER_<label style='color:red;'>JS</label>_NULL - " . $fileFullPath . "<br>";
        }
    }

    public function setExternalJs($file)
    {
        if (empty($file)) {
            return;
        }

        $this->aExternalJs[] = $file;
    }

    public function setCss($file, $v = '')
    {
        if (empty($file)) {
            return;
        }

        // 단! 상세 주소를 다 기재해줘야 한다.
        // ex) 기존:file.css   상대경로:../common/css/file.css
        if (substr($file, 0, 3) == "../") {
            $fileFullPath = RESOURCEPATH . substr($file, 3);
        } else {
            $fileFullPath = RESOURCEPATH . APPFOLDER . '/css/' . $file;
        }

        if (file_exists($fileFullPath)) {
            if ($v) {
                $file = $file . "?v={$v}";
            }
            $this->aCss[] = $file;
        } else {
            echo "[ADMIN] OPTIMIZER_<label style='color:blue;'>CSS</label>_NULL - " . $fileFullPath . "<br>";
        }
    }
}
