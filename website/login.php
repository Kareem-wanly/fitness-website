<?php
    session_start();
    $TitlePage = 'Login Page';
    if(isset($_SESSION['adminName'])) {
        header('location:home-page.php');
        exit();
    }
    include 'init.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = $_POST['name'];
        $password = $_POST['password'];
        $hashpass = sha1($password);
        $stmt = $conn->prepare('SELECT * 
                                    FROM trainees 
                                    WHERE
                                        traineeName=?
                                    AND
                                        traineePass=?');
        $stmt->execute(array($name, $hashpass));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();
        if ($count > 0) {
            $_SESSION['traineeName'] = $row['traineeName'];
            $_SESSION['traineeID'] = $row['traineeID'];
            $_SESSION['fullName'] = $row['traineeFullName'];
            $_SESSION['Email'] = $row['traineeEmail'];
            $_SESSION['MobileNum'] = $row['traineeMobileNum'];
            $_SESSION['Pic'] = $row['traineePic'];
            $_SESSION['TRID'] = $row['trainerID'];
            $_SESSION['NUID'] = $row['nutritionistID'];
            $_SESSION['sectionID'] = $row['sectionID'];
            header('location:home-page.php');
            exit();
        } else {
            $stmt2 = $conn->prepare('SELECT *
                                    FROM trainer 
    
                                    WHERE
                                        trainerName=?
                                    AND
                                        trainerPass=?');
            $stmt2->execute(array($name, $hashpass));
            $row2 = $stmt2->fetch();
            $count = $stmt2->rowCount();
            if ($count > 0) {
                $_SESSION['trainerName'] = $row2['trainerName'];
                $_SESSION['trainerID'] = $row2['trainerID'];
                $_SESSION['fullName'] = $row2['trainerFullName'];
                $_SESSION['Email'] = $row2['trainerEmail'];
                $_SESSION['MobileNum'] = $row2['trainerMobileNum'];
                $_SESSION['Pic'] = $row2['trainerPic'];
                header('location:home-page.php');
                exit();
            } else {
                $stmt3 = $conn->prepare('SELECT *
                                    FROM nutritionists 
    
                                    WHERE
                                        nutritionistName=?
                                    AND
                                        nutritionistPass=?');
                $stmt3->execute(array($name, $hashpass));
                $row3 = $stmt3->fetch();
                $count = $stmt3->rowCount();
                if ($count > 0) {
                    $_SESSION['nutritionistName'] = $row3['nutritionistName'];
                    $_SESSION['nutritionistID'] = $row3['nutritionistID'];
                    $_SESSION['fullName'] = $row3['nutritionistFullName'];
                    $_SESSION['Email'] = $row3['nutritionistEmail'];
                    $_SESSION['MobileNum'] = $row3['nutritionistMobileNum'];
                    $_SESSION['Pic'] = $row3['nutritionistPic'];
                    header('location:home-page.php');
                    exit();
                }
            }
        }
    }

?>
<link rel="stylesheet" href="layout/css/forms.css">

<!-- front end  -->

</head>
<body>
    <div class="bg">
    </div>
    <form class="login-form" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
        <h2 class="form-title">login</h2>
        <div class="input-field">
            <label for="">User Name:</label>
            <input class="input text-input" type="text" name="name" autocomplete="off" placeholder="name must be higher than 6 characters">
        </div>  
        <div class="input-field">
            <label for="">password:</label>
            <input class="input pass-input" type="password" name="password" autocomplete="new-password" placeholder="password must be higher than 10 characters">
        </div>
        <div class="submit-field">
            <input class="input submit-btn" type="submit" name="submit">
            <a href="sign-up.php?view=sign-up" class="form-link">don't have an account yet!</a>
        </div>
    </form>
<!-- <script src="<?php echo $js;?>forms.js"></script> -->
<!-- front end  -->
<?php
    include $tpl . "footer.inc";
?>