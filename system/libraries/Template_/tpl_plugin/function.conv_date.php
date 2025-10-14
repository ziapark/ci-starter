<?php

/* TEMPLATE PLUGIN FUNCTION */

function conv_date($type, $param_date='0')
{   
    //���_��ġ_��Ī
    switch ($type) {
        case "add_date":
            return add_date($param_date);          break;
        case "retrun_week_name":
            return retrun_week_name($param_date);       break;
        case "main_edu_date":
            return main_edu_date($param_date);       break;
        default:
            break;
    }
}
// (param_date + n)
function add_date($param_date='0'){
    if($param_date>=0){
        $param_date="+".$param_date;
    }
    
    $return_date = date("d",strtotime ($param_date." days"));
    if( $return_date < 10 ){
        $return_date = "&nbsp;".substr($return_date, 1,1)."&nbsp;";
    }
    return $return_date;
}

//���� ��ȯ
function retrun_week_name($param_date='0'){
    $week_name = array("��","��","ȭ","��","��","��","��");
    if($param_date>=0){
        $param_date="+".$param_date;
    }
    return $week_name[date("w",strtotime ($param_date))];
}

//���������� �������� ���� ��¥
function main_edu_date($slash_date){
    $exp_date = explode("/", $slash_date);
    
    $return_week = retrun_week_name($exp_date[0]);
    
    $return_date = date("m/d", strtotime($exp_date[0]));
    
    return $return_date."(".$return_week.")";
}



?>