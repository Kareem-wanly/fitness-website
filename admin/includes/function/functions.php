<?php
function getTitle(){
    global $TitlePage;
    if(isset($TitlePage)) {
        echo $TitlePage;
    } else {
        echo 'Default';
    }
}

function counts($item,$table, $condition = null, $value = null) {

    global $conn;

        if($condition == null && $value == null) {
            $stmt = $conn->prepare("SELECT COUNT($item) FROM $table");

            $stmt->execute();

        return $stmt->fetchColumn();
        } else {
            $stmt = $conn->prepare("SELECT COUNT($item) FROM $table WHERE $condition = $value");

            $stmt->execute();

        return $stmt->fetchColumn();
        }
    }

function getlist($select,$from,$order,$limit=3){
    global $conn;
    $stmt = $conn->prepare("SELECT $select FROM $from ORDER BY $order DESC LIMIT $limit");
    $stmt -> execute();
    $rows = $stmt->fetchAll();
    return $rows;
}

function redirecthome($TheMessages,$url = null, $time = 3) {

    if(!isset($_SERVER['HTTP_REFERER'])&& $url === null) {
        
        $url = 'index.php';

    }   else {

            if(isset($_SERVER['HTTP_REFERER'])&& $url =='') {

            $url = $_SERVER['HTTP_REFERER'];

            }   else {
                    $url = $url;
                }
        }   

    echo  $TheMessages;

   
    
    header("refresh: $time;url=$url");

    exit();
}

function check($select,$from,$value) {
    global $conn;
    $stmt = $conn->prepare("SELECT $select FROM $from WHERE $select = ?");
    $stmt->execute(array($value));
    $rows = $stmt->rowCount();
    return $rows;
}
?>