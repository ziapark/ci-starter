<?php

/* TEMPLATE PLUGIN FUNCTION */

//��Ʈ�� �� �Լ�
//string_cut(��ȯ�� ����, ����, ...�տ� ������ ����, ...??�Ⱦ�)
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