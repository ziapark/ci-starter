<?php
    function unhtmlspecialchars( $string )
        {
            $string = str_replace ( '&amp;', '&', $string );
            $string = str_replace ( '&#039;', '\'', $string );
            $string = str_replace ( '&quot;', '\"', $string );
            $string = str_replace ( '&lt;', '<', $string );
            $string = str_replace ( '&gt;', '>', $string );

            return $string;
        }
?>