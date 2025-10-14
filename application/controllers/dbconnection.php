<?php
    function dbconn(){
        $host_name="localhost";
        $db_user_id ="root";
        $db_user_pw ="";
        $db_name="ci_board";
        $connect=@mysql_connect($host_name, $db_user_id, $db_user_pw, $db_name);
        mysql_query("set names utf8", $connect);
        mysql_select_db($connect);
        if(!$connect)die("연결에 실패".mysql_error());
        return $connect;
    }

    function Error($msg){
        echo"
            <script>
            window.alert('$msg');
            history.back(1);
            </script>
        ";
        exit;
    }
?>