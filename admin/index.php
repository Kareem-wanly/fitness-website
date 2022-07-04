<?php
    session_start();
    $TitlePage = 'Login Page';
    if(isset($_SESSION['adminName'])) {
        header('location:dashboard.php');
        exit();
    }
    include 'init.php';

    if($_SERVER['REQUEST_METHOD']=='POST') {
        $adminName = $_POST['adminName'];
        $password = $_POST['password'];
        $hashpass = sha1($password);
        $stmt = $conn->prepare('SELECT adminID,adminName,password 
                                FROM admins 
                                WHERE
                                    adminName=?
                                    AND
                                    password=?');
        $stmt->execute(array($adminName,$hashpass));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();
        if($count > 0) {
            $_SESSION['adminName'] = $adminName;
            $_SESSION['ID'] = $row['adminID'];
            header('location:dashboard.php');
            exit();
        }
    }



?>
<link rel="stylesheet" href="layout/css/styling-forms.css">
<!-- front end  -->
</head>
<body>
    <div class="bg">
        <form class="login-form" action="" method="POST">
            <h2 class="login-title">login</h2>
            <div class="input-field">
                <label for="">User Name:</label>
                <input class="input text-input" type="text" name="adminName" autocomplete="off" placeholder="name must be higher than 6 characters">
            </div>  
            <!-- <div class="name-error-message">
            </div> -->
            <div class="input-field">
                <label for="">password:</label>
                <input class="input pass-input" type="password" name="password" autocomplete="new-password" placeholder="password must be higher than 10 characters">
                <!-- <ion-icon class="eye" name="eye-outline"></ion-icon>
                <ion-icon class="close-eye eye active" name="eye-off-outline"></ion-icon> -->
            </div>
            <!-- <div class="pass-error-message">
            </div> -->
            <div class="input-field">
                <input class="submit-btn" type="submit" name="submit">
            </div>
        </form>
    </div>
<!-- <script src="<?php echo $js;?>forms.js"></script> -->
<!-- front end  -->
<?php

    include $tpl . "footer.inc";
?>