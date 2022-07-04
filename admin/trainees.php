<?php
session_start();
$TitlePage = 'Trainees Page';
include "init.php";

$control = isset($_GET['control']) ? $_GET['control'] : 'manage';
?>
<link rel="stylesheet" href="<?php echo $css; ?>dashboard.css">
<link rel="stylesheet" href="<?php echo $css; ?>user-info.css">
<link rel="stylesheet" href="<?php echo $css; ?>styling-forms.css">
<!-- frontend -->
<?php
include $tpl . "navbar.inc";


if ($control == "manage") {
?>
    <div class="container">
        <div class="header-sec">
            <div class="icon"><i class="fas fa-bars"></i></div>
            <div class="search-bar">
                <form class="search">
                    <input type="text" name="search" placeholder="search for anything" class="search-feild">
                    <button type="submit" class="search-btn"><i class="fas fa-search"></i></button>
                </form>
            </div>
            <a href="logout.php" class="logut-btn">
                logout<i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
        <div class="main-title-sec">
            <h2>all trainees:</h2>
            <a href="?control=add" class="add-btn"><span class="add-icon"><i class="fas fa-plus"></i></span>add new trainee </a>
        </div>
        <div class="panel-container">
            <div class="panel approved-trainees">
                <h3 class="trainees-panel-btn">all approved trainees:</h3>
                <div class="panel-content trainees-panel">
                    <?php

                    if (isset($_GET['search'])) {
                        $searchValue = $_GET['search'];
                        $stmt2 =  $stmt = $conn->prepare("SELECT * FROM trainees
                                                                WHERE concat(traineeName, traineeFullName) LIKE ?
                                                                AND groupID = 1");
                        $stmt->execute(array("%$searchValue%"));
                        $rows = $stmt->fetchAll();
                        $count = $stmt->rowCount();
                        if ($count > 0) {
                            foreach ($rows as $row) {
                                echo '<div class="panel-info">';
                                echo '
                                        <p>' . $row['traineeFullName'] . '</p>
                                        <a href="trainees.php?control=view&traineeID=' . $row['traineeID'] . '">view</a>
                                        <a href="trainees.php?control=disable&traineeID=' . $row['traineeID'] . '">disable</a>
                                        <a href="trainees.php?control=delete&traineeID=' . $row['traineeID'] . '">delete</a>';
                                echo '</div>';
                            }
                        } else {
                            echo '<div class="panel-info">
                                <p style="color: #f00">no records was found</p>
                                </div>';
                        }
                    } else {
                        $stmt = $conn->prepare("SELECT * 
                            FROM trainees 
                            WHERE groupID = 1");
                        $stmt->execute();
                        $rows = $stmt->fetchAll();

                        foreach ($rows as $row) {
                            echo '<div class="panel-info">';
                            echo '
                                    <p>' . $row['traineeFullName'] . '</p>
                                    <a href="trainees.php?control=view&traineeID=' . $row['traineeID'] . '">view</a>
                                    <a href="trainees.php?control=disable&traineeID=' . $row['traineeID'] . '">disable</a>
                                    <a href="trainees.php?control=delete&traineeID=' . $row['traineeID'] . '">delete</a>';
                            echo '</div>';
                        }
                    }

                    ?>
                </div>
            </div>
            <div class="panel">
                <h3 class="trainer-panel-btn">all pending trainees:</h3>
                <div class="panel-content trainer-panel active">
                    <?php
                    $stmt = $conn->prepare("SELECT * 
                                                FROM trainees 
                                                WHERE groupID = 0");
                    $stmt->execute();
                    $rows = $stmt->fetchAll();

                    foreach ($rows as $row) {
                        echo '<div class="panel-info">';
                        echo '
                                <p>' . $row['traineeName'] . '</p>
                                <a href="trainees.php?control=view&traineeID=' . $row['traineeID'] . '">view</a>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
<?php
} elseif ($control == "view") {
    $traineeID = $_GET['traineeID'];
    $stmt = $conn->prepare('SELECT * FROM trainees WHERE traineeID = ? LIMIT 1');
    $stmt->execute(array($traineeID));
    $row = $stmt->fetch();
    $groupID = $row['groupID'];
?>

    <div class="container">
        <div class="header-sec">
            <div class="icon"><i class="fas fa-bars"></i></div>
            <a href="logout.php" class="logut-btn">
                logout<i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
        <div class="main-title-sec">
                <h2><span class="member-name"><?php  echo $row['traineeName'];?></span> Information:</h2>
            </div>
        <div class="content">
            <div class="content-img">
                <img src="<?php echo $row['traineePic']; ?>" alt="hello">
            </div>
            <div class="content-info">
                <div class="label name">
                    <span>name:</span><span> <?php echo $row['traineeName']; ?></span>
                </div>
                <div class="label fullname">
                    <span>full name:</span><span> <?php echo $row['traineeFullName']; ?></span>
                </div>
                <div class="label email">
                    <span>email:</span><span> <?php echo $row['traineeEmail']; ?></span>
                </div>
                <div class="label mobile-num">
                    <span>mobile number:</span><span> <?php echo $row['traineeMobileNum']; ?></span>
                </div>
            </div>
        </div>
        <?php
        if ($groupID == 1) {
        ?>
            <div class="control-btn">
                <a href="trainees.php?control=delete&traineeID=<?php echo $row['traineeID']; ?>">delete</a>
                <a href="trainees.php?control=disable&traineeID=<?php echo $row['traineeID']; ?>">disable</a>

            </div>
            <div class="main-title-sec">
                <h2>all payments:</h2>
                <a href="add-payment.php?traineeID=<?php echo $traineeID; ?>" class="add-btn"><span class="add-icon"><i class="fas fa-plus"></i></span>add new payment </a>
            </div>
            <table class="bills-table">
                <?php
                $stmt = $conn->prepare("SELECT subscription_payments.*, adminName from subscription_payments
                                                    INNER JOIN admins
                                                    ON subscription_payments.adminID = admins.adminID
                                                    WHERE traineeID = ?");
                $stmt->execute(array($traineeID));
                $rows = $stmt->fetchAll();
                ?>
                <thead>
                    <th>payment maker</th>
                    <th>payment Date</th>
                    <th>full payment</th>
                    <th>Payment value</th>
                    <th>Rest of total</th>
                </thead>
                <?php
                foreach ($rows as $row) {
                    echo '
                                    <tr>
                                        <td> ' . $row['adminName'] . ' </td>
                                        <td> ' . $row['subsPaymentDate'] . ' </td>
                                        <td> ' . $row['totalPaymentVal'] . ' </td>
                                        <td> ' . $row['paymentValue'] . ' </td>
                                        <td> ' . $row['restOfTotal'] . ' </td>
                                    </tr>
                                    ';
                }
                ?>
            </table>
        <?php
        } else {
        ?>
            <div class="control-btn">
                <a href="trainees.php?control=delete&traineeID=<?php echo $row['traineeID']; ?>">delete</a>
                <a href="trainees.php?control=activite&traineeID=<?php echo $row['traineeID']; ?>">activite</a>
            </div>
        <?php
        }
        ?>
    </div>
<?php
} elseif ($control == "activite" || $control == "disable") {
    if ($control == "activite") {
        $traineeID = $_GET['traineeID'];
        $stmt = $conn->prepare('UPDATE trainees SET groupID = 1 WHERE traineeID = ?');
        $stmt->execute(array($traineeID));
        $rows = $stmt->rowCount();
        if ($rows > 0) {
            header("location:trainees.php?control=manage");
            exit();
        } else {
            echo "error";
        }
    } else {
        $traineeID = $_GET['traineeID'];
        $stmt = $conn->prepare('UPDATE trainees SET groupID = 0 WHERE traineeID = ?');
        $stmt->execute(array($traineeID));
        $rows = $stmt->rowCount();
        if ($rows > 0) {
            header("location:trainees.php?control=manage");
            exit();
        } else {
            echo "error";
        }
    }
} elseif ($control == "delete") {
    $traineeID = $_GET['traineeID'];
    $stmt = $conn->prepare("SELECT * FROM trainees WHERE traineeID=?");
    $stmt->execute(array($traineeID));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    extract($row);
    $traineePic = $row['traineePic'];
    if (file_exists($traineePic)) {
        $picState = unlink($traineePic);
    }
    $delete = $conn->prepare("DELETE FROM trainees WHERE traineeID=?");
    $delete->execute(array($traineeID));
    $TheMessages = '<div class="container"><div class="alert alert-success">Delete Successfully</div></div>';
    redirecthome($TheMessages, 'trainees.php');
} elseif ($control == "add") { ?>

    <div class="container form-container">
        <div class="header-sec">
            <div class="icon"><i class="fas fa-bars"></i></div>
            <a href="logout.php" class="logut-btn">
                logout<i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
        <div class="form add-form">
            <h2 class="login">add trainee</h2>
            <form action="trainees.php?control=insert" method="POST" enctype="multipart/form-data">
                <div class="input-field">
                    <label for="">Trainee Name:</label>
                    <input class="input text-input" type="text" name="traineeName" autocomplete="off" placeholder="name must be higher than 6 characters">
                </div>
                <div class="input-field">
                    <label for="">trainee FullName:</label>
                    <input class="input text-input" type="text" name="traineeFullName" autocomplete="off" placeholder="name must be higher than 6 characters">
                </div>
                <div class="input-field">
                    <label for="">Trainee Email:</label>
                    <input class="input text-input" type="text" name="traineeEmail" autocomplete="off" placeholder="name must be higher than 6 characters">
                </div>
                <!-- <div class="name-error-message">
                    </div> -->
                <div class="input-field">
                    <label for="">password:</label>
                    <input class="input pass-input" type="password" name="traineePassword" autocomplete="new-password" placeholder="password must be higher than 10 characters">
                    <!-- <ion-icon class="eye" name="eye-outline"></ion-icon>
                        <ion-icon class="close-eye eye active" name="eye-off-outline"></ion-icon> -->
                </div>
                <!-- <div class="pass-error-message">
                    </div> -->
                <div class="input-field">
                    <label for="">Trainee Mobile Number:</label>
                    <input class="input pass-input" type="text" name="traineeMobileNum" autocomplete="off" placeholder="Mobile Phone">
                </div>
                <div class="input-field">
                    <label for="">Image:</label>
                    <input class="fileInput" type="file" name="files[]" multiple>
                    <!-- <ion-icon class="eye" name="eye-outline"></ion-icon>
                        <ion-icon class="close-eye eye active" name="eye-off-outline"></ion-icon> -->
                </div>
                <!-- <div class="pass-error-message">
                    </div> -->
                <div class="input-field">
                    <label for="">sectionName:</label>
                    <select class="select-input" name="section">
                        <option value="0">...</option>
                        <?php
                        $stmt = $conn->prepare("SELECT * FROM section");

                        $stmt->execute();

                        $sections = $stmt->fetchAll();

                        foreach ($sections as $section) {

                            echo '<option value="' .  $section['sectionID'] . '">' . $section['sectionName'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="input-field">
                    <label for="">trainerName:</label>
                    <select class="select-input" name="Trainers">
                        <option value="0">...</option>
                        <?php
                        $stmt = $conn->prepare("SELECT * FROM trainer");

                        $stmt->execute();

                        $trainers = $stmt->fetchAll();

                        foreach ($trainers as $trainer) {

                            echo '<option value="' .  $trainer['trainerID'] . '">' . $trainer['trainerName'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="input-field">
                    <label for="">nutritionistName:</label>
                    <select class="select-input" name="nutritionists">
                        <option value="0">...</option>
                        <?php
                        $stmt = $conn->prepare("SELECT * FROM nutritionists");

                        $stmt->execute();

                        $nutritionists = $stmt->fetchAll();

                        foreach ($nutritionists as $nutritionist) {

                            echo '<option value="' .  $nutritionist['nutritionistID'] . '">' . $nutritionist['nutritionistName'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="input-field">
                    <input class="submit-btn" type="submit" name="add-trainee">
                </div>
            </form>
        </div>
    </div>
<?php
} elseif ($control == 'insert') {
    if (isset($_POST['add-trainee'])) {
        $trainneName      = $_POST['traineeName'];
        $trainneFullName  = $_POST['traineeFullName'];
        $traineeEmail     = $_POST['traineeEmail'];
        $traineePassword  = $_POST['traineePassword'];
        $traineeMobileNum = $_POST['traineeMobileNum'];
        $Trainers         = $_POST['Trainers'];
        $nutritionists    = $_POST['nutritionists'];
        $section          = $_POST['section'];
        $hasPass          = sha1($traineePassword);
        $countFiles = count($_FILES['files']['name']);
        $query = "INSERT INTO trainees(traineeName,traineeFullName,traineeEmail,traineePass,traineeMobileNum,traineePic,groupID,trainerID, nutritionistID, sectionID) 
                      VALUES(?,?,?,?,?,?,1, ?, ?, ?)";
        $statement = $conn->prepare($query);
        for ($i = 0; $i < $countFiles; $i++) {
            $fileName = $_FILES['files']['name'][$i];
            $target_file = "../images/imagesTraineers/" . $fileName;
            $fileExtension = pathinfo($target_file, PATHINFO_EXTENSION);
            $fileExtension = strtolower($fileExtension);

            $validExtension = array("png", "jpg", "jpeg");
            if (in_array($fileExtension, $validExtension)) {
                if (move_uploaded_file($_FILES['files']['tmp_name'][$i], $target_file)) {
                    $statement->execute(array($trainneName, $trainneFullName, $traineeEmail, $hasPass, $traineeMobileNum, $target_file, $Trainers, $nutritionists, $section));

                    $theMessage = '<div class="container"><div class="alert alert-success"> the Trainees has been inserted successfully</div></div>';
                    redirecthome($theMessage, '?control=manage');
                }
            }
        }
    }
}
?>
<?php
?>


<script src="<?php echo $js; ?>navbar.js"></script>
<script src="<?php echo $js; ?>main.js"></script>

<?php

include $tpl . "footer.inc";
?>
<!-- frontend -->