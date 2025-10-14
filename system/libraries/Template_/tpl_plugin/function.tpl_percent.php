<?php

/* TEMPLATE PLUGIN FUNCTION EXAMPLE */

function tpl_percent($target, $total, $round=2)
{
    if($total == 0){
        return 0;
    }else {
        return round($target / $total * 100, $round);
    }
}
?>