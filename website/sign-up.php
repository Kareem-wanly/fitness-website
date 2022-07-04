<?php
    session_start();
    $TitlePage = 'sign up Page';
    if(isset($_SESSION['adminName'])) {
        header('location:main.php');
        exit();
    }
    include 'init.php';
?>
<link rel="stylesheet" href="layout/css/forms.css">

<!-- front end  -->

</head>
<body>
    <?php
    $view = isset($_GET['view']) ? $_GET['view'] : 'sign-up';
    if($view == 'sign-up') {?>
        <div class="bg">
        </div>
        <form class="sign-form" action="?view=insert" enctype="multipart/form-data" method="POST">
            <h2 class="form-title">sign up</h2>
            <div class="form-con">
                <div class="field">
                    <div class="input-field">
                        <label for="">User Name:</label>
                        <input class="input text-input" type="text" name="traineeName" autocomplete="off" placeholder="name must be higher than 6 characters">
                    </div>
                    <div class="input-field">
                        <label for="">full name:</label>
                        <input class="input pass-input" type="text" name="traineeFullName" autocomplete="off" placeholder="full name go here">
                    </div>
                </div>
                <div class="field">
                    <div class="input-field">
                        <label for="">email:</label>
                        <input class="input pass-input" type="email" name="traineeEmail" autocomplete="off" placeholder="EX: account@gmail.com">
                    </div>
                    <div class="input-field">
                        <label for="">password:</label>
                        <input class="input pass-input" type="password" name="traineePass" autocomplete="new-password" placeholder="password must be higher than 10 characters">
                    </div>
                </div>
                <div class="field">
                    <div class="input-field">
                        <label for="">mobile number:</label>
                        <input class="input pass-input" type="number" name="traineeMobileNum" autocomplete="off" placeholder="EX: +963 992 992 992">
                    </div>
                    <div class="input-field">
                        <label for="">sectionName:</label>
                        <select id="section" class="input" name="section">
                            <option value="0">select section</option>
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
                </div>
                <div class="field">
                    <div class="input-field">
                        <label for="">trainer:</label>
                        <select id="trainer" class="input" name="Trainers">
                            <option value="">select section first</option>
                        </select>
                    </div>
                    <div class="input-field">
                        <label for="">nutritionist:</label>
                        <select id="nutritionist" class="input" name="nutritionists">
                           <option value="">select section first</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="input-field">
                <label for="">piture:</label>
                <input class="input img-input" type="file" name="files[]" multiple>
            </div>
            <div class="submit-field">
                <input class="input submit-btn" type="submit" name="addTrainee">
                <a href="login.php" class="form-link">already have an account yet!</a>
            </div>
        </form>
    <?php
    }elseif ($view == 'insert') {
        if (isset($_POST['addTrainee'])) {
            $traineeName = $_POST['traineeName'];
            $traineeFullName = $_POST['traineeFullName'];
            $traineeEmail = $_POST['traineeEmail'];
            $traineePass = $_POST['traineePass'];
            $hashPassword = sha1($traineePass);
            $traineeMobileNum = $_POST['traineeMobileNum'];
            $section = $_POST['section'];
            $Trainers = $_POST['Trainers'];
            $nutritionists = $_POST['nutritionists'];
            $countFile = count($_FILES['files']['name']);
            $query = "INSERT INTO trainees(traineeName,traineeFullName,traineeEmail,traineePass,traineeMobileNum,traineePic,groupID,trainerID,nutritionistID,sectionID)
                                VALUES(?,?,?,?,?,?,0,?,?,?)";
            $stmt = $conn->prepare($query);
            for ($i = 0; $i < $countFile; $i++) {
                $fileName = $_FILES['files']['name'][$i];
                $targetFile = "../images/imagesTraineers/" . $fileName;
                $fileEX = pathinfo($targetFile, PATHINFO_EXTENSION);
                $fileEX = strtolower($fileEX);
                $validExtension = array('png', 'jpg', 'jpeg');
                if (in_array($fileEX, $validExtension)) {
                    if (move_uploaded_file($_FILES['files']['tmp_name'][$i], $targetFile)) {
                        $stmt->execute(array($traineeName, $traineeFullName, $traineeEmail, $hashPassword, $traineeMobileNum, $targetFile, $Trainers, $nutritionists, $section));
                        $lastID = $conn->LastInsertId();
                        $theMessage = '<div class="container"><div class="alert alert-success"> Inserted Successfully</div></div>';
                        redirecthome($theMessage, '?view=addBody&traineeID=' . $lastID);
                    }
                }
            }
        }
    }elseif($view == 'addBody'){?>
        <div class="bg">
        </div>
        <form class="login-form" action="?view=insertBody" method="POST">
            <h2 class="form-title">we need more info about you</h2>
            <div class="input-field">
                <label for="">weight:</label>
                <input class="input text-input" type="number" name="weight" autocomplete="off" placeholder="name must be higher than 6 characters">
            </div>
            <div class="input-field">
                <label for="">height:</label>
                <input class="input pass-input" type="number" name="height" autocomplete="off" placeholder="full name go here">
            </div>
            <div class="input-field">
                <label for="">age:</label>
                <input class="input pass-input" type="number" name="age" autocomplete="off" placeholder="EX: account@gmail.com">
            </div>
            <div class="input-field">
                <label for="">healthState:</label>
                <input class="input pass-input" type="text" name="healthState" autocomplete="new-password" placeholder="password must be higher than 10 characters">
                <input class="input pass-input" type="text" hidden name="traineeID" value="<?php echo $_GET['traineeID'] ?>" autocomplete="new-password" placeholder="password must be higher than 10 characters">
            </div>
            <div class="submit-field">
                <input class="input submit-btn" type="submit" name="addBody">
            </div>
        </form>
        

        <?php
    }elseif($view == 'insertBody') {
        if(isset($_POST['addBody'])) {
            $weight = $_POST['weight'];
            $height = $_POST['height'];
            $age = $_POST['age'];  
            $healthState = $_POST['healthState'];
            $traineeID = $_POST['traineeID'];
            $stmt = $conn->prepare("INSERT INTO traineesbody(weight,height,age,healthState,traineeID)
                                    VALUES(?,?,?,?,?)");
            $stmt->execute(array($weight,$height,$age,$healthState,$traineeID));
            $row = $stmt->rowCount();
            if($row > 0) {
                $theMessage = '<div class="container"><div class="alert alert-success"> Inserted Successfully</div></div>';
                redirecthome($theMessage, 'index.php');
            }
        }
    }
    ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" 
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" 
        crossorigin="anonymous">
    </script>
    <script>
        //when the document is ready
        $(document).ready(function() {
            //when i change the section select option
            $('#section').on('change', function() {
                //store the section id we choose in a variable
                const sectionID = $(this).val();
                //to fetch the data from the server for a specific section and store all the data in data variable
                $.post("selectControl.php", {sectionID: sectionID}, function(data) {
                    //if there is a trainers in this array
                    if(data['trainers']) {
                        //to empty the select options
                        $('#trainer').empty();
                        //to add the trainers option to select 
                        data['trainers'].forEach(function(trainer){
                            $('#trainer').append(`<option value="${trainer['trainerID']}">${trainer['trainerName']}</option>`)
                        })
                        //if there is no trainers in current array
                    } else {
                        $('#trainer').html(`<option disabled value="">no trainers in this section</option>`)
                    }
                    // if there is a nutritionists in this array
                    if(data['nutritionists']) {
                        //to empty the select options
                        $('#nutritionist').empty();
                        //to add the nutritionists option to select 
                        data['nutritionists'].forEach(function(nutritionist){
                            $('#nutritionist').append(`<option value="${nutritionist['nutritionistID']}">${nutritionist['nutritionistName']}</option>`)
                        })
                        //if there is no nutritionists in current array
                    } else {
                        $('#nutritionist').html(`<option disabled value="">no nutritionist in this section</option>`)
                    }
                })
            });
        });
    </script>
</body>
</html>