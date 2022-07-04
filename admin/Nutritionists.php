<?php
session_start();
$TitlePage = 'Nutritionists Page';

if (isset($_SESSION['adminName'])) {

    include 'init.php';

    $control = isset($_GET['control']) ? $_GET['control'] : 'Manage'; ?>

    <link rel="stylesheet" href="<?php echo $css; ?>dashboard.css">
    <link rel="stylesheet" href="<?php echo $css; ?>trainers.css">
    <link rel="stylesheet" href="<?php echo $css; ?>user-info.css">
    <link rel="stylesheet" href="<?php echo $css; ?>styling-forms.css">

    <!-- frontend -->
    <?php
    include $tpl . "navbar.inc";
    ?>
    <?php
    if ($control == 'Manage') { ?>

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
                <h2>all Nutritionists:</h2>
                <a href="?control=add" class="add-btn"><span class="add-icon"><i class="fas fa-plus"></i></span>add new Nutritionist</a>
            </div>

            <div class="panel-container">
                <div class="panel">
                    <h3 class="trainer-panel-btn">view Nutritionists:</h3>
                    <div class="panel-content trainees-panel">
                        <?php

                        if (isset($_GET['search'])) {
                            $searchValue = $_GET['search'];
                            $stmt2 =  $stmt = $conn->prepare("SELECT * FROM nutritionists
                                                                WHERE nutritionistName LIKE ?");
                            $stmt->execute(array("%$searchValue%"));
                            $rows = $stmt->fetchAll();
                            $count = $stmt->rowCount();
                            if ($count > 0) {
                                foreach ($rows as $row) {
                                    echo '<div class="panel-info">
                                    <p>' . $row['nutritionistFullName'] . '</p>
                                    <a href="Nutritionists.php?control=view&nutritionistID=' . $row['nutritionistID'] . '">view</a>
                                    <a href="Nutritionists.php?control=edit&nutritionistID=' . $row['nutritionistID'] . '">edit</a>
                                    <a href="Nutritionists.php?control=Delete&nutritionistID=' . $row['nutritionistID'] . '">Delete</a>
                                    </div>';
                                }
                            } else {
                                echo '<div class="panel-info">
                                <p style="color: #f00">no records was found</p>
                                </div>';
                            }
                        } else {
                            $stmt = $conn->prepare("SELECT * FROM nutritionists");
                            $stmt->execute();
                            $rows = $stmt->fetchAll();
                            foreach ($rows as $row) {
                                echo '<div class="panel-info">
                                <p>' . $row['nutritionistFullName'] . '</p>
                                <a href="Nutritionists.php?control=view&nutritionistID=' . $row['nutritionistID'] . '">view</a>
                                <a href="Nutritionists.php?control=edit&nutritionistID=' . $row['nutritionistID'] . '">edit</a>
                                <a href="Nutritionists.php?control=Delete&nutritionistID=' . $row['nutritionistID'] . '">Delete</a>
                                </div>';
                            }
                        }

                        ?>
                    </div>
                </div>
            </div>
            <div class="latest-trainers-posts">
                <div class="main-title-sec">
                    <h2>latest Nutritionists posts:</h2>
                </div>
                <div class="latest-posts-cont">
                    <?php
                    $stmt = $conn->prepare("SELECT * FROM nutritionists
                                               INNER JOIN post
                                               ON nutritionists.nutritionistID  = post.nutitionstsID
                                               ORDER BY post.postID LIMIT 6 ");
                    $stmt->execute();
                    $rows = $stmt->fetchAll();
                    foreach ($rows as $row) {
                        echo '
                            <div class="post-card">
                                <img class="post-image" src="' . $row['postPic'] . '" alt="">
                                <div class="post-content">
                                    <div class="post-header">' . $row['postTitle'] . '</div>
                                    <div class="post-info">' . $row['postDecraption'] . '</div>
                                    <a href="#" class="post-btn">view</a>
                                </div>
                            </div>';
                    }
                    ?>

                </div>
            </div>
        </div>


    <?php
    } elseif ($control == 'view') {
        $nutritionistsID = $_GET['nutritionistID'];
        $stmt = $conn->prepare('SELECT * FROM nutritionists WHERE nutritionistID = ?');
        $stmt->execute(array($nutritionistsID));
        $rows = $stmt->fetch();
    ?>
        <div class="container">
            <div class="header-sec">
                <div class="icon"><i class="fas fa-bars"></i></div>
                <a href="logout.php" class="logut-btn">
                    logout<i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
            <div class="main-title-sec">
                <h2><?php echo $rows['nutritionistName']; ?> Information:</h2>
            </div>
            <div class="content">
                <div class="content-img">
                    <img src="<?php echo $rows['nutritionistPic']; ?>" alt="hello">
                </div>
                <div class="content-info">
                    <div class="label name">
                        <span>name:</span><span><?php echo $rows['nutritionistName']; ?></span>
                    </div>
                    <div class="label fullname">
                        <span>full name:</span><span><?php echo $rows['nutritionistFullName']; ?></span>
                    </div>
                    <div class="label email">
                        <span>email:</span><span><?php echo $rows['nutritionistEmail']; ?></span>
                    </div>
                    <div class="label mobile-num">
                        <span>mobile number:</span><span><?php echo $rows['nutritionistMobileNum']; ?></span>
                    </div>
                </div>
            </div>
            <div class="control-btn">
                <a href="Nutritionists.php?control=edit&nutritionistID=<?php echo $rows['nutritionistID']; ?>">edit</a>
                <a href="Nutritionists.php?control=Delete&nutritionistID=<?php echo $rows['nutritionistID']; ?>">delete</a>
            </div>
            <div class="trainer-posts">
                <div class="main-title-sec">
                    <h2><?php echo $rows['nutritionistName']; ?> posts:</h2>
                </div>
                <div class="latest-posts-cont">
                    <?php
                    $stmt = $conn->prepare("SELECT * FROM nutritionists
                                                    INNER JOIN post
                                                    ON nutritionists.nutritionistID=post.nutitionstsID  
                                                    WHERE nutritionists.nutritionistID=?");
                    $stmt->execute(array($nutritionistsID));
                    $rows = $stmt->fetchAll();
                    foreach ($rows as $row) {
                        echo '
                                    <div class="post-card">
                                        <img class="post-image" src="' . $row['postPic'] . '" alt="">
                                        <div class="post-content">
                                            <div class="post-header">' . $row['postTitle'] . '</div>
                                            <div class="post-info">' . $row['postDecraption'] . '</div>
                                            <a href="#" class="post-btn">view</a>
                                        </div>
                                    </div>
                                ';
                    }
                    ?>
                </div>
                <div class="trainer-trainees">
                    <div class="main-title-sec">
                        <h2>heba trainees:</h2>
                    </div>
                    <table class="bills-table">
                        <?php
                        $stmt = $conn->prepare("SELECT * FROM nutritionists
                                            INNER JOIN trainees
                                            ON nutritionists.nutritionistID= trainees.nutritionistID
                                            WHERE nutritionists.nutritionistID=?");
                        $stmt->execute(array($nutritionistsID));
                        $rows = $stmt->fetchAll();
                        ?>

                        <thead>
                            <th>ID</th>
                            <th>Name</th>
                            <th>FullName</th>
                            <th>Email</th>
                            <th>Mobile Number</th>
                        </thead>
                        <?php
                        foreach ($rows as $row) {
                            echo ' 
                            <tr>
                                 <td>' . $row['traineeID'] . '</td>
                                 <td>' . $row['traineeName'] . '</td>
                                 <td>' . $row['traineeFullName'] . '</td>
                                 <td>' . $row['traineeEmail'] . '</td>
                                <td>' . $row['traineeMobileNum'] . '</td>
                           </tr>';
                        }
                        ?>
                    </table>
                </div>
            </div>

        <?php

    } elseif ($control == "add") { ?>

            <div class="container form-container">
                <div class="header-sec">
                    <div class="icon"><i class="fas fa-bars"></i></div>
                    <a href="logout.php" class="logut-btn">
                        logout<i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
                <div class="form add-form">
                    <h2 class="login">add nutritionists</h2>
                    <form action="Nutritionists.php?control=insert" method="POST" enctype="multipart/form-data">
                        <div class="input-field">
                            <label for="">User Name:</label>
                            <input class="input text-input" type="text" name="nutritionistName" autocomplete="off" placeholder="name must be higher than 6 characters">
                        </div>
                        <div class="input-field">
                            <label for="">full Name:</label>
                            <input class="input text-input" type="text" name="nutritionistFullName" autocomplete="off" placeholder="name must be higher than 6 characters">
                        </div>
                        <div class="input-field">
                            <label for="">email:</label>
                            <input class="input text-input" type="text" name="nutritionistEmail" autocomplete="off" placeholder="name must be higher than 6 characters">
                        </div>
                        <!-- <div class="name-error-message">
                    </div> -->
                        <div class="input-field">
                            <label for="">password:</label>
                            <input class="input pass-input" type="password" name="nutritionistPass" autocomplete="new-password" placeholder="password must be higher than 10 characters">
                            <!-- <ion-icon class="eye" name="eye-outline"></ion-icon>
                        <ion-icon class="close-eye eye active" name="eye-off-outline"></ion-icon> -->
                        </div>
                        <div class="input-field">
                            <label for="">Mobile Number:</label>
                            <input class="input pass-input" type="test" name="nutritionistMobileNum" autocomplete="off" placeholder="Enter The Mobile Number">
                            <!-- <ion-icon class="eye" name="eye-outline"></ion-icon>
                        <ion-icon class="close-eye eye active" name="eye-off-outline"></ion-icon> -->
                        </div>
                        <div class="input-field">
                            <label for="">Image:</label>
                            <input class="fileInput" type="file" name="files[]" multiple>
                            <!-- <ion-icon class="eye" name="eye-outline"></ion-icon>
                        <ion-icon class="close-eye eye active" name="eye-off-outline"></ion-icon> -->
                        </div>
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
                            <input class="submit-btn" type="submit" name="add-nutritionist">
                        </div>

                    </form>
                </div>
            </div>
        </div>
    <?php
    } elseif ($control == 'insert') {
        if (isset($_POST['add-nutritionist'])) {

            $nutritionistName        = $_POST['nutritionistName'];

            $nutritionistFullName    = $_POST['nutritionistFullName'];

            $nutritionistEmail       = $_POST['nutritionistEmail'];

            $nutritionistPassword    = $_POST['nutritionistPass'];

            $nutritionistMobileNum   = $_POST['nutritionistMobileNum'];

            $sectionName             = $_POST['section'];

            $hashPassword            = sha1($nutritionistPassword);

            $countFile = count($_FILES['files']['name']);

            $query = "INSERT INTO nutritionists(nutritionistName,nutritionistFullName,nutritionistEmail,nutritionistPass,nutritionistMobileNum,nutritionistPic,sectionID)
                 VALUES(?,?,?,?,?,?,?)";

            $stmt = $conn->prepare($query);

            for ($i = 0; $i < $countFile; $i++) {

                $fileName = $_FILES['files']['name'][$i];

                $target_file = "../images/imagesNutritionists/" . $fileName;

                $fileExtension = pathinfo($target_file, PATHINFO_EXTENSION);

                $fileExtension = strtolower($fileExtension);

                $fileType = array("png", "jpg", "jpeg");

                if (in_array($fileExtension, $fileType)) {

                    if (move_uploaded_file($_FILES['files']['tmp_name'][$i], $target_file)) {

                        $stmt->execute(array($nutritionistName, $nutritionistFullName, $nutritionistEmail, $hashPassword, $nutritionistMobileNum, $target_file, $sectionName));
                        $theMessage = '<div class="container"><div class="alert alert-success"> the nutritionists has been inserted successfully</div></div>';
                        redirecthome($theMessage, 'Nutritionists.php?control=Manage');
                    }
                }
            }
        }
    } elseif ($control == 'Delete') {
        $nutritionistID = $_GET['nutritionistID'];
        $stmt = $conn->prepare('DELETE FROM nutritionists WHERE nutritionistID=?');
        $stmt->execute(array($nutritionistID));
        $row = $stmt->rowCount();
        if ($row > 0) {
            $TheMessages = '<div class="alert alert-success"> Recored Deleted </div>';
            redirecthome($TheMessages, 'Nutritionists.php');
        }
    } elseif ($control == 'edit') {
        $nutritionistsID = $_GET['nutritionistID'];
        $stmt = $conn->prepare("SELECT * FROM nutritionists WHERE nutritionistID=?");
        $stmt->execute(array($nutritionistsID));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        extract($row);
    ?>
        <div class="container form-container">
            <div class="header-sec">
                <div class="icon"><i class="fas fa-bars"></i></div>
            </div>
            <div class="form add-form">
                <h2 class="login">edit <?php echo $row['nutritionistName']; ?> nutritionist</h2>
                <form action="Nutritionists.php?control=update" method="POST" enctype="multipart/form-data">
                    <div class="input-field">
                        <label for="">User Name:</label>
                        <input class="input text-input" type="text" name="nutritionistName" value="<?php echo $row['nutritionistName']; ?>" autocomplete="off" placeholder="name must be higher than 6 characters">
                    </div>
                    <div class="input-field">
                        <label for="">full Name:</label>
                        <input class="input text-input" type="text" name="nutritionistFullName" value="<?php echo $row['nutritionistFullName']; ?>" autocomplete="off" placeholder="name must be higher than 6 characters">
                    </div>
                    <div class="input-field">
                        <label for="">email:</label>
                        <input class="input text-input" type="text" name="nutritionistEmail" value="<?php echo $row['nutritionistEmail']; ?>" autocomplete="off" placeholder="name must be higher than 6 characters">
                    </div>
                    <!-- <div class="name-error-message">
                    </div> -->
                   
                    <div class="input-field">
                        <label for="">Mobile Number:</label>
                        <input class="input pass-input" type="test" name="nutritionistMobileNum" value="<?php echo $row['nutritionistMobileNum']; ?>" autocomplete="off" placeholder="Enter The Mobile Number">
                        <!-- <ion-icon class="eye" name="eye-outline"></ion-icon>
                        <ion-icon class="close-eye eye active" name="eye-off-outline"></ion-icon> -->
                    </div>
                    <input type="text" name="nutritionistID" hidden value="<?php echo $row['nutritionistID']; ?>">
                    <input type="text" name="nutritionistPic" hidden value="<?php echo $row['nutritionistPic']; ?>">
                    <div class="input-field">
                        <label for="">Image:</label>
                        <input class="fileInput" type="file" name="files[]" multiple>
                        <!-- <ion-icon class="eye" name="eye-outline"></ion-icon>
                        <ion-icon class="close-eye eye active" name="eye-off-outline"></ion-icon> -->
                    </div>
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
                        <input class="submit-btn" type="submit" name="add-nutritionist">
                    </div>

                </form>
            </div>
        </div>
        </div>
<?php
    } elseif ($control == "update") {
        if (isset($_POST['add-nutritionist'])) {
            $countFile = count($_FILES['files']['name']);
                for ($i = 0 ; $i < $countFile ; $i++) {
                    $image = $_FILES['files']['name'][$i];
                    if ($image == null) {
                        $nutritionistID = $_POST['nutritionistID'];
                        $nutritionistName = $_POST['nutritionistName'];
                        $nutritionistFullName = $_POST['nutritionistFullName'];
                        $nutritionistEmail = $_POST['nutritionistEmail'];
                        $nutritionistMobileNum = $_POST['nutritionistMobileNum'];
                        $nutritionistPic = $_POST['nutritionistPic'];

                        $stmt = $conn->prepare( "UPDATE nutritionists SET nutritionistName=?,
                                                        nutritionistFullName=?,
                                                        nutritionistEmail=?,
                                                        nutritionistMobileNum=?,
                                                        nutritionistPic=?
                                                    WHERE nutritionistID=?");
                        $stmt->execute(array($nutritionistName,$nutritionistFullName,$nutritionistEmail,$nutritionistMobileNum,$nutritionistPic,$nutritionistID));
                        $row = $stmt->rowCount();
                        if($row > 0) {
                            $theMessage = '<div class="container"><div class="alert alert-success"> the nutritionists has been Update successfully</div></div>';
                            redirecthome($theMessage, 'Nutritionists.php?control=Manage');
                        }
                    } else {
                        $nutritionistID = $_POST['nutritionistID'];
                        $nutritionistName = $_POST['nutritionistName'];
                        $nutritionistFullName = $_POST['nutritionistFullName'];
                        $nutritionistEmail = $_POST['nutritionistEmail'];
                        $nutritionistMobileNum = $_POST['nutritionistMobileNum'];
                        $nutritionistPic = $_POST['nutritionistPic'];
                        if(file_exists($nutritionistPic)) {
                            $imageStatus = unlink($nutritionistPic);
                        }
                        $countFile = count($_FILES['files']['name']);
                        $stmt = $conn->prepare( "UPDATE nutritionists SET nutritionistName=?,
                                                        nutritionistFullName=?,
                                                        nutritionistEmail=?,
                                                        nutritionistMobileNum=?,
                                                        nutritionistPic=?
                                                    WHERE nutritionistID=?");
                        for($i = 0 ; $i < $countFile ; $i++) {
                            $image = $_FILES['files']['name'][$i];
                            $targetFile = "../images/imagesNutritionists/" . $image;
                            $fileEX = pathinfo($targetFile,PATHINFO_EXTENSION);
                            $fileEX = strtolower($fileEX);
                            $validExtension = array('png','jpg','jpeg');
                            if(in_array($fileEX,$validExtension)) {
                                if(move_uploaded_file($_FILES['files']['tmp_name'][$i],$targetFile)) {
                                    $stmt->execute(array($nutritionistName,$nutritionistFullName,$nutritionistEmail,$nutritionistMobileNum,$targetFile,$nutritionistID));
                                    $theMessage = '<div class="container"><div class="alert alert-success"> the nutritionists has been Update successfully</div></div>';
                                    redirecthome($theMessage, 'Nutritionists.php?control=Manage');
                                }
                            }
                        }
                    }
                }
            }
        }       
} 
?>

<script src="<?php echo $js; ?>navbar.js"></script>
<script src="<?php echo $js; ?>main.js"></script>

<?php
include $tpl . "footer.inc";
?>