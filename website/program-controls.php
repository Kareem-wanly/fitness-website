<?php
session_start();
include "init.php";
?>
<link rel="stylesheet" href="<?php echo $css; ?>user-info.css">
<link rel="stylesheet" href="<?php echo $css; ?>forms.css">
<link rel="stylesheet" href="<?php echo $css; ?>posts.css">
<?php
include $tpl . "navbar.inc";
if (isset($_SESSION['trainerID'])) {
    $control = isset($_GET['control']) ? $_GET['control'] : 'view';
    if ($control == "view") {
        $tpID = $_GET['tpID'];
        $stmt = $conn->prepare("SELECT * FROM trainerprograms WHERE tpID=?");
        $stmt->execute(array($tpID));
        $row = $stmt->fetch(); ?>
        <div class="container">
            <div class="main-title-sec">
                <h2> <?php echo $row['programTitle']; ?> program info:</h2>
            </div>
            <div class="content">
                <div class="content-info">
                    <div class="label name">
                        <span>program title:</span><span><?php echo $row['programTitle']; ?></span>
                    </div>
                    <div class="label fullname">
                        <span>program made date:</span><span><?php echo $row['programDate']; ?></span>
                    </div>
                    <div class="label email">
                        <span>program end date:</span><span><?php echo $row['programEndDate']; ?></span>
                    </div>
                </div>
            </div>
            <div class="main-title-sec">
                <h2> program exercises:</h2>
                <a href="?control=addExercise&tpID=<?php echo $row['tpID']; ?>" class="add-btn">add new exercise</a>
            </div>
            <?php
                $tpID = $_GET['tpID'];
        $stmt = $conn->prepare("SELECT * FROM trainerprograms
                                                INNER JOIN programdetails
                                                ON trainerprograms.tpID=programdetails.tpID
                                                INNER JOIN weekdays
                                                ON  weekdays.dayID=programdetails.dayID
                                                WHERE trainerprograms.tpID=?");
        $stmt->execute(array($tpID));
        $rows = $stmt->fetchAll();
        $count = 0; ?>
            <div class="table-responsive">
                <table class="bills-table text-center">
                    <thead class="">
                        <th class="col-1">#</th>
                        <th class="col-1">Program</th>
                        <th class="col-2">exercise day</th>
                        <th class="col-2">exercise muscle</th>
                        <th class="col-6">exercise</th>
                        <th class="col-2">controls</th>
                    </thead>
                    <?php
                    foreach ($rows as $row) {
                        $count++;
                        echo'<tbody>
                        <tr>
                            <td>'.$count.'</td>
                            <td>'.$row['programTitle'].'</td>
                            <td>'.$row['day'].'</td>
                            <td>'.$row['muscle'].'</td>
                            <td>'.$row['exercise'].'</td>
                            <td><a href="program-controls.php?control=edit-exercises&programDetailsID=' . $row['programDetailsID'] . '" class="btn btn-outline-primary">edit</a></td>
                        </tr>
                    </tbody>';
                    } ?>
                </table>
            </div>
        </div>

    <?php
    }elseif($control == 'edit-exercises') {
        $programDetailsID = $_GET['programDetailsID'];
        $stmt = $conn->prepare("SELECT * FROM programdetails WHERE programDetailsID=?");
        $stmt->execute(array($programDetailsID));
        $row = $stmt->fetch();
        ?>
         <div class="container">
            <div class="main-title-sec">
                <h2>Edit Exercises:</h2>
            </div>
            <form action="?control=update-exercises" method="POST" class="form profile-form">
                <div class="input-field">
                    <label for="" class="label">Muscle:</label>
                    <input type="text" name="muscle" value="<?php echo $row['muscle'] ?>" class="input">
                </div>
                <div class="input-field">
                    <label for="" class="label">Exercise:</label>
                    <input type="text" value="<?php echo $row['exercise'] ?>" name="exercise" class="input">
                </div>
                <div class="input-field">
                    <input type="text" hidden value="<?php echo $row['programDetailsID'] ?>" name="programDetailsID" class="input">
                </div>
              
                <div class="submit-field">
                    <input type="submit" name="edit-exercise" class="input submit-btn">
                </div>
            </form>
        </div>
        <?php
    } elseif($control == 'update-exercises'){
        if(isset($_POST['edit-exercise'])) {
            $muscle = $_POST['muscle'];
            $exercise = $_POST['exercise'];
            $programDetailsID = $_POST['programDetailsID'];
            $stmt = $conn->prepare("UPDATE programdetails SET   muscle=?,
                                                                exercise=?
                                    WHERE programDetailsID=?");
            $stmt->execute(array($muscle,$exercise,$programDetailsID));
            $row = $stmt->rowCount();
            if($row > 0) {
                $TheMessages = '<div class="container"><div class="alert alert-success">Recored Updated </div></div>';
                redirecthome($TheMessages, 'profile.php?control=pers-trainees');
            }
        }
    } elseif($control == 'edit') {

        $tpID = $_GET['tpID'];
        $stmt = $conn->prepare("SELECT * FROM trainerprograms WHERE tpID=?");
        $stmt->execute(array($tpID));
        $row = $stmt->fetch();
    ?>
          <div class="container">
            <div class="main-title-sec">
                <h2>Edit <?php echo $row['programTitle'] ?> program:</h2>
            </div>
            <form action="?control=update" method="POST" class="form profile-form">
                <div class="input-field">
                    <label for="" class="label">program Title:</label>
                    <input type="text" name="programTitle" value="<?php echo $row['programTitle'] ?>" class="input">
                </div>
                <div class="input-field">
                    <label for="" class="label">program date:</label>
                    <input type="date" value="<?php echo $row['programDate'] ?>" name="programDate" class="input">
                </div>
                <div class="input-field">
                    <input type="text" hidden value="<?php echo $row['tpID'] ?>" name="tpID" class="input">
                </div>
                <div class="input-field">
                    <label for="" class="label">program end date:</label>
                    <input type="date" name="programEndDate" value="<?php echo $row['programEndDate'] ?>" class="input">
                </div>
                <div class="submit-field">
                    <input type="submit" name="edit-program" class="input submit-btn">
                </div>
            </form>
        </div>
    <?php
    }elseif($control == 'update') {
        if(isset($_POST['edit-program'])) {
            
            $programTitle = $_POST['programTitle'];
            $programDate = $_POST['programDate'];
            $programEndDate = $_POST['programEndDate'];
            $tpID = $_POST['tpID'];
            $stmt = $conn->prepare("UPDATE trainerprograms SET 
                                                                programTitle=?,
                                                                programDate=?,
                                                                programEndDate=?
                                    WHERE tpID=?");
            $stmt->execute(array($programTitle,$programDate,$programEndDate,$tpID));
            $row = $stmt->rowCount();
            if($row > 0) {
                $TheMessages = '<div class="container"><div class="alert alert-success">Recored Updated </div></div>';
                redirecthome($TheMessages, 'profile.php?control=pers-trainees');
            }
        }
    } elseif ($control == "add") {
        $trainerID = $_GET['traineeID'];
        echo $_GET['traineeID']; ?>

        <div class="container">
            <div class="main-title-sec">
                <h2>add new program:</h2>
            </div>
            <form action="?control=insert" method="POST" class="form profile-form">
                <div class="input-field">
                    <label for="" class="label">program Title:</label>
                    <input type="text" name="programTitle" class="input">
                </div>
                <div class="input-field">
                    <input type="text" hidden name="traineeID" value="<?php echo $_GET['traineeID']; ?>" class="input">
                </div>
                <div class="input-field">
                    <label for="" class="label">program date:</label>
                    <input type="date" name="programDate" class="input">
                </div>
                <div class="input-field">
                    <label for="" class="label">program end date:</label>
                    <input type="date" name="programEndDate" class="input">
                </div>
                <div class="submit-field">
                    <input type="submit" name="add-program" class="input submit-btn">
                </div>
            </form>
        </div>
    <?php
    } elseif ($control == 'insert') {
        if (isset($_POST['add-program'])) {
            $traineeID = $_POST['traineeID'];
            $programTitle = $_POST['programTitle'];
            $programDate = $_POST['programDate'];
            $programEndDate = $_POST['programEndDate'];
            $trainerID = $_SESSION['trainerID'];
            $stmt = $conn->prepare("INSERT INTO trainerprograms(programTitle,programDate,programEndDate,trainerID,traineeID)
                                VALUES(?,?,?,?,?)");
            $stmt->execute(array($programTitle, $programDate, $programEndDate, $trainerID, $traineeID));
            $row = $stmt->rowCount();
            if ($row > 0) {
                $TheMessages = '<div class="container"><div class="alert alert-success">Recored Inserted </div></div>';
                redirecthome($TheMessages, 'profile.php?control=pers-trainees');
            }
        } ?>
    <?php
    } elseif ($control == 'addExercise') {
        $tpID = $_GET['tpID']; ?>

        <div class="container">
            <div class="main-title-sec">
                <h2>add new exercise:</h2>
            </div>
            <form action="program-controls.php?control=insertExercise" method="POST" class="form profile-form">
                <div class="input-field">
                    <label for="" class="label">exercise muscle:</label>
                    <input type="text" name="muscle" class="input">
                </div>
                <div class="input-field">
                    <label for="" class="label">exercise:</label>
                    <input type="text" name="exercise" class="input">
                </div>
                <div class="input-field">
                    <label for="" class="label">exercise day:</label>
                    <select class="input select-input" name="days">
                        <option value="0">...</option>
                        <?php
                        $stmt = $conn->prepare("SELECT * FROM weekdays");
            $stmt->execute();
            $days = $stmt->fetchAll();
            foreach ($days as $day) {
                echo '<option value="' .  $day['dayID'] . '">' . $day['day'] . '</option>';
            } ?>
                    </select>
                </div>
                <div class="input-field">
                    <input type="text" hidden name="tpID" value="<?php echo $_GET['tpID']; ?>" class="input">
                </div>
                <div class="submit-field">
                    <input type="submit" name="Exercise" class="input submit-btn">
                </div>
            </form>
        </div>
    <?php
    } elseif ($control == 'insertExercise') {
        if (isset($_POST['Exercise'])) {
            $muscle = $_POST['muscle'];
            $exercise = $_POST['exercise'];
            $days = $_POST['days'];
            $tpID = $_POST['tpID'];
            $stmt = $conn->prepare("INSERT INTO programdetails(muscle,exercise,dayID,tpID)
                            VALUES(?,?,?,?)");
            $stmt->execute(array($muscle,$exercise,$days,$tpID));
            $row = $stmt->rowCount();
            if ($row > 0) {
                $TheMessages = '<div class="container"><div class="alert alert-success">Recored Inserted </div></div>';
                redirecthome($TheMessages, 'profile.php?control=pers-trainees');
            }
        }
    }
} elseif(isset($_SESSION['nutritionistID'])){
    $control = isset($_GET['control']) ? $_GET['control'] : 'view';
    if ($control == "view") {
        $npID = $_GET['npID'];
        $stmt = $conn->prepare("SELECT * FROM nutritionistsprograms WHERE npID=?");
        $stmt->execute(array($npID));
        $row = $stmt->fetch(); 

        // fetch all current program details 
        $n_p_details = $conn->prepare("SELECT * FROM `n_p_details` 
                                        inner join `nutritionistsprograms`
                                        ON `n_p_details`.`programID` = `nutritionistsprograms`.`npID`
                                        where `n_p_details`.`programID` = ?");
        $n_p_details->execute(array($npID));
        $n_p_details_rows = $n_p_details->fetchAll();
        $n_p_details_counts = $n_p_details->rowCount();
        ?>
        <div class="container">
            <div class="main-title-sec">
                <h2> <?php echo $row['programTitle']; ?> program info:</h2>
            </div>
            <div class="content">
                <div class="content-info">
                    <div class="label name">
                        <span>program title:</span><span><?php echo $row['programTitle']; ?></span>
                    </div>
                    <div class="label fullname">
                        <span>program made date:</span><span><?php echo $row['programDate']; ?></span>
                    </div>
                    <div class="label email">
                        <span>program end date:</span><span><?php echo $row['programEndDate']; ?></span>
                    </div>
                </div>
            </div>
            <div class="main-title-sec">
                <h2> program details:</h2>
                <?php if($n_p_details_counts == 0) {?>
                    <a href="?control=addDetails&npID=<?php echo $row['npID']; ?>" class="add-btn">add details</a>
                <?php
                 } else { ?>
                    <a href="?control=editDetails&npID=<?php echo $row['npID']; ?>" class="add-btn">edit details</a>
                <?php
                 } ?>
            </div>
            <?php
            // get the days
            $get_days = $conn->prepare("SELECT * FROM weekdays");
            $get_days->execute();
            $get_days_rows = $get_days->fetchAll();
            $get_days_count = $get_days->rowCount();

            // check if there is already a program details or not
            if($n_p_details_counts > 0) {
            ?>
            <div class="table-responsive">
            <table class="bills-table" style="width: 100%;">
                        <thead>
                            <td class="text-center fw-bold">days</td>
                            <th>breakfast</th>
                            <th>lunch</th>
                            <th>dinner</th>
                            <th>pre-workout meal</th>
                            <th>post-workout meal</th>
                        </thead>
                        <tbody>
                        <?php
                            $counter = 0;
                            while ($counter < 7) {
                                    ?>
                                    <tr>
                                        <th><?php echo $get_days_rows[$counter][1] ?></th>
                                        <td><?php echo  $n_p_details_rows[$counter][1] ?></td>
                                        <td><?php echo  $n_p_details_rows[$counter][2] ?></td>
                                        <td><?php echo  $n_p_details_rows[$counter][3] ?></td>
                                        <td><?php echo  $n_p_details_rows[$counter][4] ?></td>
                                        <td><?php echo  $n_p_details_rows[$counter][5] ?></td>
                                    </tr>
                                <?php
                                $counter++;
                            }
                            ?>
                        </tbody>
                    </table>
            </div>
            <?php
            } else {
                echo '<div class="alert alert-info">no details yet</div>' ;
            }?>
        </div>

    <?php
    } elseif ($control == "add") {
        $trainerID = $_GET['traineeID'];
        echo $_GET['traineeID']; ?>

        <div class="container">
            <div class="main-title-sec">
                <h2>add new program:</h2>
            </div>
            <form action="?control=insert" method="POST" class="form profile-form">
                <div class="input-field">
                    <label for="" class="label">program Title:</label>
                    <input type="text" name="nutritionistProgram" class="input">
                </div>
                <div class="input-field">
                    <input type="text" hidden name="traineeID" value="<?php echo $_GET['traineeID']; ?>" class="input">
                </div>
                <div class="input-field">
                    <label for="" class="label">program date:</label>
                    <input type="date" name="programDate" class="input">
                </div>
                <div class="input-field">
                    <label for="" class="label">program end date:</label>
                    <input type="date" name="programEndDate" class="input">
                </div>
                <div class="submit-field">
                    <input type="submit" name="add-program" class="input submit-btn">
                </div>
            </form>
        </div>
    <?php
    } elseif ($control == 'insert') {
        if (isset($_POST['add-program'])) {
            $traineeID = $_POST['traineeID'];
            $nutritionistProgram = $_POST['nutritionistProgram'];
            $programDate = $_POST['programDate'];
            $programEndDate = $_POST['programEndDate'];
            $nutritionistID = $_SESSION['nutritionistID'];
            $stmt = $conn->prepare("INSERT INTO nutritionistsprograms(programTitle,programDate,programEndDate,nutritionistID,traineeID)
                                VALUES(?,?,?,?,?)");
            $stmt->execute(array($nutritionistProgram, $programDate, $programEndDate, $nutritionistID, $traineeID));
            $row = $stmt->rowCount();
            if ($row > 0) {
                $TheMessages = '<div class="container"><div class="alert alert-success">Recored Inserted </div></div>';
                redirecthome($TheMessages, 'profile.php?control=pers-trainees');
            }
        } ?>
    <?php
    }elseif($control == 'addDetails') {
        $programID = $_GET['npID'];
        $stmt = $conn->prepare("SELECT * FROM weekdays");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $count2 = $stmt->rowCount();
        ?>
          <div class="container">
            <div class="main-title-sec">
                <h2>add program details:</h2>
            </div>
            <div class="table-responsive" style="width: 100%;">
                <form action="" method="POST">
                    <table class="bills-table" style="width: 100%;">
                        <thead>
                            <td class="text-center fw-bold">days</td>
                            <th>breakfast</th>
                            <th>lunch</th>
                            <th>dinner</th>
                            <th>pre-workout meal</th>
                            <th>post-workout meal</th>
                        </thead>
                        <tbody>
                        <?php
                            foreach($rows as $row) { ?>
                                <tr>
                                    <th scope=""><?php echo $row['day']; ?></td>
                                    <td><input class="input table-input" type="text" name="breakfast<?php echo $row['dayID'];?>"></td>
                                    <td><input class="input table-input" type="text" name="lunch<?php echo $row['dayID'];?>"></td>
                                    <td><input class="input table-input" type="text" name="dinner<?php echo $row['dayID'];?>"></td>
                                    <td><input class="input table-input" type="text" name="pre-lunch<?php echo $row['dayID'];?>"></td>
                                    <td><input class="input table-input" type="text" name="post-lunch<?php echo $row['dayID'];?>"></td>
                                </tr>
                            <?php
                            }
                        ?>
                        </tbody>
                    </table>
                    <div class="input-field">
                        <input name="addDetails" type="submit" class="input submit-btn" value="add" style="margin: 0 auto;">
                    </div>
                </form>
            </div>
            
        <?php
        if(isset($_POST['addDetails'])) {
            $count = 1;
            while($count <= 7) {
                $stmt = $conn->prepare("INSERT INTO 
                                        n_p_details(breakfast, lunch, Dinner, preWorkout, postWorkout, programID, dayID)
                                        VALUES(?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute(array(
                    $_POST['breakfast'. $count],
                    $_POST['lunch'. $count],
                    $_POST['dinner'. $count],
                    $_POST['pre-lunch'. $count],
                    $_POST['post-lunch'. $count],
                    $programID,
                    $count
                ));
                $count3 = $stmt->rowCount();
                if($count3 > 0) {
                    echo $count3;
                } 
                $count++;
            }
        }
    } elseif($control == 'editDetails') {

        $npID = $_GET['npID'];
        // get the days 
        $get_days = $conn->prepare("SELECT * FROM weekdays");
        $get_days->execute();
        $get_days_rows = $get_days->fetchAll();
        $get_days_count = $get_days->rowCount();
        // fetch all current program details 
        $n_p_details = $conn->prepare("SELECT * FROM `n_p_details` 
                                    inner join `nutritionistsprograms`
                                    ON `n_p_details`.`programID` = `nutritionistsprograms`.`npID`
                                    where `n_p_details`.`programID` = ?");
        $n_p_details->execute(array($npID));
        $n_p_details_rows = $n_p_details->fetchAll();
        $n_p_details_counts = $n_p_details->rowCount();
        ?>
          <div class="container">
            <div class="main-title-sec">
                <h2>add program details:</h2>
            </div>
            <div class="table-responsive" style="width: 100%;">
                <form action="" method="POST">
                    <table class="bills-table" style="width: 100%;">
                        <thead>
                            <td class="text-center fw-bold">days</td>
                            <th>breakfast</th>
                            <th>lunch</th>
                            <th>dinner</th>
                            <th>pre-workout meal</th>
                            <th>post-workout meal</th>
                        </thead>
                        <tbody>
                        <?php
                            $counter = 0;
                            while ($counter < 7) { ?>
                                <tr>
                                    <th>
                                        <?php echo $get_days_rows[$counter][1] ?>
                                        <input class="input table-input" hidden type="text" name="N_P_ID<?php echo $get_days_rows[$counter][0]?>" value="<?php echo  $n_p_details_rows[$counter][0] ?>">
                                    </th>
                                    <td>
                                        <input class="input table-input" type="text" name="breakfast<?php echo $get_days_rows[$counter][0]?>" value="<?php echo  $n_p_details_rows[$counter][1] ?>">
                                    </td>
                                    <td>
                                        <input class="input table-input" type="text" name="lunch<?php echo $get_days_rows[$counter][0]?>" value="<?php echo  $n_p_details_rows[$counter][2] ?>">
                                    </td>
                                    <td>
                                        <input class="input table-input" type="text" name="dinner<?php echo $get_days_rows[$counter][0]?>" value="<?php echo  $n_p_details_rows[$counter][3] ?>">
                                    </td>
                                    <td>
                                        <input class="input table-input" type="text" name="pre-lunch<?php echo $get_days_rows[$counter][0]?>" value="<?php echo  $n_p_details_rows[$counter][4] ?>">
                                    </td>
                                    <td>
                                        <input class="input table-input" type="text" name="post-lunch<?php echo $get_days_rows[$counter][0]?>" value="<?php echo  $n_p_details_rows[$counter][5] ?>">
                                    </td>
                                </tr>
                                <?php
                                $counter++;
                            }
                        ?>
                        </tbody>
                    </table>
                    <div class="input-field">
                        <input name="editDetails" type="submit" class="input submit-btn" value="edit" style="margin: 0 auto;">
                    </div>
                </form>
            </div>
            
        <?php
        if(isset($_POST['editDetails'])) {
            $count = 1;
            while($count <= 7) {
                $stmt = $conn->prepare("UPDATE `n_p_details` SET 
                                        `breakfast` = ?,
                                        `lunch` = ?,
                                        `Dinner` = ?, 
                                        `preWorkout` = ?, 
                                        `postWorkout` = ? 
                                        WHERE programID = ? 
                                        AND N_P_ID = ?");
                $stmt->execute(array(
                    $_POST['breakfast'. $count],
                    $_POST['lunch'. $count],
                    $_POST['dinner'. $count],
                    $_POST['pre-lunch'. $count],
                    $_POST['post-lunch'. $count],
                    $npID,
                    $_POST['N_P_ID' . $count]
                ));
                $count3 = $stmt->rowCount();
                $count++;
            }
            
        }
    }
}
    include $tpl . "footer.inc";

?>