<?php

/* TEMPLATE PLUGIN FUNCTION */

//스트링 컷 함수
//string_cut(변환할 문자, 길이, ...앞에 덧붙일 인자, ...??안씀)
function string_cut($string, $cut_size=40, $divide="",$direction="")
{
    $str_out='';
    $line = 1;
    for($i=0; $i < strlen($string); $i++)
    {
        if($line < $cut_size+1)
        {
            if(ord($string[$i]) <= 127)
            { $str_out .= $string[$i]; } else  { $str_out .= $string[$i].$string[++$i];  $line ++;  }
        }
        else
        {
            $i --;
             
            $line = 0;
             
            if(!$divide)
            {  $str_out .= "...";   break;  }
            else
            { $str_out .= $divide."...";  }
        }

        $line ++;

    }


    return $str_out;
}

?>