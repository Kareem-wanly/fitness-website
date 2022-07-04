<?php
include "connect.php";
    if(isset($_POST['sectionID']) && !empty($_POST['sectionID'])) {
        //we will store the nutritionists and trainers in this array 
        $array = array();
        //data stored will be like that =>
        // [
        //   "trainers": {[trainer1], [trainer2], [trainer3], ...},
        //   "nutritionists": {[nutritionist1], [nutritionist2], [nutritionist3], ...},
        //]
        $sectionID = $_POST['sectionID'];

        $stmt = $conn->prepare("SELECT trainerID, trainerName FROM trainer WHERE sectionID = ?");
        $stmt->execute(array($sectionID));
        $result = $stmt->fetchAll();
        $rowsCount = $stmt->rowCount();

        $stmt2 = $conn->prepare("SELECT nutritionistID, nutritionistName FROM nutritionists WHERE sectionID = ?");
        $stmt2->execute(array($sectionID));
        $result2 = $stmt2->fetchAll();
        $rowsCount2 = $stmt2->rowCount();

        if($rowsCount > 0) {
            $array['trainers'] = $result;
        }
        if($rowsCount2 > 0) {
            $array['nutritionists'] = $result2;
        }
        //to make sure that the ajax request getting the data correctly
        echo json_encode($array);
        header('Access-Control-Allow-Origin: *');
        header("Content-Type: application/json");
    } else {

    }
?>