<?php

/* TEMPLATE PLUGIN FUNCTION EXAMPLE */

function tpl_arr_count($arr, $depth=1) {
    if (!is_array($arr) || !$depth) return 0;

    $res=count($arr);

    foreach ($arr as $in_ar)
        $res+=tpl_arr_count($in_ar, $depth-1);

    return $res;
}
?>