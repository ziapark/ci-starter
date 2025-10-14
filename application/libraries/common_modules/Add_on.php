<?php
/**
 * Header
 * 
 * @author junhan Lee <junes@gmail.com>
 */
class Add_on
{
    private $ci = NULL;
    
    public function __construct()
    {
        $this->ci =& get_instance();

        $this->start();
    }

    private function start()
    {
        $this->ci->template_->viewDefine('lnb', 'common/layout_lnb.tpl');
        $this->ci->template_->viewAssign(array(
        ));
    }
}