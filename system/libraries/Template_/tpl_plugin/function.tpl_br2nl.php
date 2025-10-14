<?php

/* TEMPLATE PLUGIN FUNCTION EXAMPLE */

function tpl_br2nl($string)
{
    return preg_replace('/\<br(\s*)?\/?\>/i', "\n", $string);
}
?>