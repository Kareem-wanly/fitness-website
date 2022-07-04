<?php 
    session_start();
    include "init.php";
    ?>
    <link rel="stylesheet" href="<?php echo $css;?>user-info.css">
    <link rel="stylesheet" href="<?php echo $css;?>forms.css">
    <link rel="stylesheet" href="<?php echo $css;?>posts.css">
    <?php
    include $tpl . "navbar.inc";
    $control = isset($_GET['control']) ? $_GET['control'] : 'profile';
    ?>
    <?php if(isset($_SESSION['trainerID'])) { ?>
        <?php if($control == 'profile') {?>
            <div class="container">
                <div class="main-title-sec">
                    <h2>my information:</h2>
                </div>
                <div class="content">
                    <div class="content-img">
                        <img src="<?php echo $_SESSION['Pic'] ?>" alt="hello">
                    </div>
                    <div class="content-info">
                        <div class="label name">
                            <span>name:</span><span> <?php echo $_SESSION['trainerName']; ?> </span>
                        </div>
                        <div class="label fullname">
                            <span>full name:</span><span> <?php echo $_SESSION['fullName']; ?></span>
                        </div>
                        <div class="label email">
                            <span>email:</span><span> <?php echo $_SESSION['Email']; ?></span>
                        </div>
                        <div class="label mobile-num">
                            <span>mobile number:</span><span><?php echo $_SESSION['MobileNum']; ?></span>
                        </div>
                    </div>
                </div>
                <div class="control-btn">
                    <a href="?control=pers-posts" class="btn btn-light text-dark">my posts</a>
                    <a href="?control=edit" class="btn btn-danger">edit info</a>
                    <a href="?control=pers-trainees" class="btn btn-light text-dark">my trainees</a>
                </div>
            </div>
        <?php
        } elseif($control == 'pers-posts') { 
            $trainerID = $_SESSION['trainerID'];
            $stmt = $conn->prepare("SELECT * FROM trainer 
                                    INNER JOIN post
                                    ON trainer.trainerID=post.trainerID
                                    WHERE trainer.trainerID=?");
            $stmt->execute(array($trainerID));
            $rows = $stmt->fetchAll();
            ?>

            <div class="container">
                <div class="main-title-sec">
                    <h2>add new post:</h2>
                </div>
                <form action="?control=insert-posts" method="POST" enctype="multipart/form-data" class="form profile-form">
                    <div class="input-field">
                        <label for="">post title</label>
                        <input type="text" name="postTitle" class="input">
                    </div>
                    <div class="input-field">
                        <label for="">post description:</label>
                        <textarea name="postDecraption" class="input" rows="5"></textarea>
                    </div>
                    
                    <div class="input-field">
                        <label for="">post pic</label>
                        <input type="file" name="files[]" class="input img-input">
                    </div>
                    <div class="input-field">
                        <label for="">postStatus</label>
                        <select class="input" name="postStatus">
                            <option value="1">Public Posts</option>
                            <option value="2">Training Posts</option>
                        </select>
                    </div>
                    <div class="submit-field">
                        <input type="submit" name="add-post" class="input submit-btn">
                    </div>
                </form>
                <div class="main-title-sec">
                    <h2>all post:</h2>
                </div>
                <div class="posts-container">
                    <?php
                    foreach ($rows as $row) {
                        echo '
                        <div class="vt-card">
                            <img class="vt-card-image" src=' . $row['postPic'] . ' alt="">
                            <div class="vt-card-content">
                                <div class="vt-card-header">' . $row['postTitle'] . '</div>
                                <div class="vt-card-info">' . $row['postDecraption'] . '</div>
                                <a href="post-control.php?postID=' . $row['postID'] . '" class="vt-card-btn">view all the content</a>
                            </div>
                        </div>';
                    }
                    ?>
                </div>
            </div>
        <?php
        } elseif ($control == 'insert-posts') {
            if (isset($_POST['add-post'])) {
                $postTitle = $_POST['postTitle'];
                $postDecraption = $_POST['postDecraption'];
                $postStatus = $_POST['postStatus'];
                $trainerID = $_SESSION['trainerID'];
                $countFile = count($_FILES['files']['name']);
                $query = "INSERT INTO post(postTitle,postDecraption,postPic,postStatus,trainerID)
                          VALUES(?,?,?,?,?)";
                $stmt = $conn->prepare($query);
                for ($i = 0; $i < $countFile; $i++) {
                    $image = $_FILES['files']['name'][$i];
                    $targetFile = "../images/imagesPost/" . $image;
                    $fileEX = pathinfo($targetFile, PATHINFO_EXTENSION);
                    $fileEX = strtolower($fileEX);
                    $validExtension = array('png', 'jpg', 'jpeg');
                    if (in_array($fileEX, $validExtension)) {
                        if (move_uploaded_file($_FILES['files']['tmp_name'][$i], $targetFile)) {
                            $stmt->execute(array($postTitle, $postDecraption, $targetFile, $postStatus, $trainerID));
                            $theMessage = '<div class="container"><div class="alert alert-success"> Inserted Successfully</div></div>';
                            redirecthome($theMessage, 'profile.php?control=pers-posts');
                        }
                    }
                }
            } else {
                echo "no";
            }
        } elseif($control == 'pers-trainees') { 
            $trainerID = $_SESSION['trainerID'];
            $stmt = $conn->prepare("SELECT * 
                                    FROM trainer
                                    INNER JOIN trainees
                                    ON trainer.trainerID=trainees.trainerID
                                    WHERE trainer.trainerID=?");
            $stmt->execute(array($trainerID));
            $rows = $stmt->fetchAll();
            $counter = 0;
            ?>
            <div class="container">
                <div class="main-title-sec">
                    <h2>my trainees:</h2>
                    <a href="trainees-control2.php?control=allMessages" class="add-btn">all messages</a>
                </div>
                <div class="table-responsive">
                    <table class="bills-table text-center">
                        <thead class="">
                            <th>#</th>
                            <th>trainee name</th>
                            <th>trainee email</th>
                            <th>controls</th>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($rows as $row) {
                                $counter++;
                                echo '
                                <tr>
                                    <td>' . $counter . '</td>
                                    <td>' . $row['traineeName'] . '</td>
                                    <td>' . $row['traineeEmail'] . '</td>
                                    <td>
                                    <a href="trainees-control2.php?traineeID=' . $row['traineeID'] . ' " class="btn btn-primary">view</a>
                                    </td>
                                </tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php
        } elseif($control == 'edit') { 
            $trainerID = $_SESSION['trainerID'];
            $stmt = $conn->prepare("SELECT * FROM trainer WHERE trainerID=?");
            $stmt->execute(array($trainerID));
            $row = $stmt->fetch();
            ?>
            <div class="container">
                <form class="form" action="?control=update" enctype="multipart/form-data" method="POST">
                    <h2 class="form-title">edit information</h2>
                    <div class="input-field">
                        <label for="">User Name:</label>
                        <input class="input text-input" type="text" value="<?php echo $row['trainerName']; ?>" name="trainerName" autocomplete="off" placeholder="name must be higher than 6 characters">
                    </div>
                    <div class="input-field">
                        <label for="">full name:</label>
                        <input class="input pass-input" type="text" value="<?php echo $row['trainerFullName']; ?>" name="trainerFullName" autocomplete="off" placeholder="full name go here">
                    </div>
                    <div class="input-field">
                        <label for="">email:</label>
                        <input class="input pass-input" type="email" value="<?php echo $row['trainerEmail']; ?>" name="trainerEmail" autocomplete="off" placeholder="EX: account@gmail.com">
                    </div>
                    <div class="input-field">
                        <label for="">mobile number:</label>
                        <input class="input pass-input" type="number" value="<?php echo $row['trainerMobileNum']; ?>" name="trainerMobileNum" autocomplete="off" placeholder="EX: +963 992 992 992">
                    </div>
                    <div class="input-field">
                        <label for="">piture:</label>
                        <input class="input img-input" type="file" name="files[]" multiple>

                        <input class="input img-input" hidden type="text" name="trainerPic" value="<?php echo $row['trainerPic'] ?>" >
                    </div>
                    <div class="submit-field">
                        <input class="input submit-btn" type="submit" name="editTrainee">
                    </div>
                </form>
            </div>
        <?php
        }  elseif($control == 'update') { 
            if(isset($_POST['editTrainee'])) {
                $countFile = count($_FILES['files']['name']);
                for ($i = 0 ; $i < $countFile ; $i++) {
                    $image = $_FILES['files']['name'][$i];
                    if ($image == null) {
                        $trainerID = $_SESSION['trainerID'];
                        $trainerName = $_POST['trainerName'];
                        $trainerFullName = $_POST['trainerFullName'];
                        $trainerEmail = $_POST['trainerEmail'];
                        $trainerMobileNum = $_POST['trainerMobileNum'];
                        $trainerPic = $_POST['trainerPic'];
                        $stmt = $conn->prepare( "UPDATE trainer SET trainerName=?,
                                                    trainerFullName=?,
                                                    trainerEmail=?,
                                                    trainerMobileNum=?,
                                                    trainerPic=?
                                                 WHERE trainerID=?");
                        $stmt->execute(array($trainerName,$trainerFullName,$trainerEmail,$trainerMobileNum,$trainerPic,$trainerID));
                        $row = $stmt->rowCount();
                        if($row > 0) {
                            $TheMessages = '<div class="container"><div class="alert alert-success">Recored Updated </div></div>';
                            session_unset();
                            session_destroy();
                            redirecthome($TheMessages,'index.php');
                        }
                    } else {
                        $trainerID = $_SESSION['trainerID'];
                        $trainerName = $_POST['trainerName'];
                        $trainerFullName = $_POST['trainerFullName'];
                        $trainerEmail = $_POST['trainerEmail'];
                        $trainerMobileNum = $_POST['trainerMobileNum'];
                        $trainerPic = $_POST['trainerPic'];
                        if(file_exists($trainerPic)) {
                            $imageStatus = unlink($trainerPic);
                        }
                        $countFile = count($_FILES['files']['name']);
                        $query = "UPDATE trainer SET trainerName=?,
                                                    trainerFullName=?,
                                                    trainerEmail=?,
                                                    trainerMobileNum=?,
                                                    trainerPic=?
                                            WHERE trainerID=?";
                        $stmt = $conn->prepare($query);
                        for($i = 0 ; $i < $countFile ; $i++) {
                            $image = $_FILES['files']['name'][$i];
                            $targetFile = "../images/imagesTrainers/" . $image;
                            $fileEX = pathinfo($targetFile,PATHINFO_EXTENSION);
                            $fileEX = strtolower($fileEX);
                            $validExtension = array('png','jpg','jpeg');
                            if(in_array($fileEX,$validExtension)) {
                                if(move_uploaded_file($_FILES['files']['tmp_name'][$i],$targetFile)) {
                                    $stmt->execute(array($trainerName,$trainerFullName,$trainerEmail,$trainerMobileNum,$targetFile,$trainerID));
                                    $TheMessages = '<div class="container"><div class="alert alert-success">Recored Updated </div></div>';
                                    session_unset();
                                    session_destroy();
                                    redirecthome($TheMessages,'index.php');
                                }
                            }
                        }
                    }
                }
            }
        }
    } 

    elseif(isset($_SESSION['nutritionistID'])) { ?>
        <?php if($control == 'profile') {?>
            <div class="container">
                <div class="main-title-sec">
                    <h2>my information:</h2>
                </div>
                <div class="content">
                    <div class="content-img">
                        <img src="<?php echo $_SESSION['Pic'] ?>" alt="hello">
                    </div>
                    <div class="content-info">
                        <div class="label name">
                            <span>name:</span><span> <?php echo $_SESSION['nutritionistName'] ?></span>
                        </div>
                        <div class="label fullname">
                            <span>full name:</span><span> <?php echo $_SESSION['fullName']; ?></span>
                        </div>
                        <div class="label email">
                            <span>email:</span><span> <?php echo $_SESSION['Email']; ?></span>
                        </div>
                        <div class="label mobile-num">
                            <span>mobile number:</span><span><?php echo $_SESSION['MobileNum']; ?></span>
                        </div>
                    </div>
                </div>
                <div class="control-btn">
                    <a href="?control=pers-posts" class="btn btn-light text-dark">my posts</a>
                    <a href="?control=edit" class="btn btn-danger">edit info</a>
                    <a href="?control=pers-trainees" class="btn btn-light text-dark">my trainees</a>
                </div>
            </div>
        <?php
        } elseif($control == 'pers-posts') { 
            $nutitionstsID = $_SESSION['nutritionistID'];
            $stmt = $conn->prepare("SELECT * FROM nutritionists
                                    INNER JOIN post
                                    ON nutritionists.nutritionistID=post.nutitionstsID 
                                    WHERE nutritionists.nutritionistID= ?");
            $stmt->execute(array($nutitionstsID));
            $rows = $stmt->fetchAll();
            ?>

            <div class="container">
                <div class="main-title-sec">
                    <h2>add new post:</h2>
                </div>
                <form action="?control=insert-posts" method="POST" enctype="multipart/form-data" class="form profile-form">
                    <div class="input-field">
                        <label for="">post title</label>
                        <input type="text" name="postTitle" class="input">
                    </div>
                    <div class="input-field">
                        <label for="">post description:</label>
                        <textarea name="postDecraption" class="input" rows="5"></textarea>
                    </div>
                    
                    <div class="input-field">
                        <label for="">post pic</label>
                        <input type="file" name="files[]" class="input img-input">
                    </div>
                    <div class="input-field">
                        <label for="">postStatus</label>
                        <select class="input" name="postStatus">
                            <option value="1">Public Posts</option>
                            <option value="2">Training Posts</option>
                        </select>
                    </div>
                    <div class="submit-field">
                        <input type="submit" name="add-post" class="input submit-btn">
                    </div>
                </form>
                <div class="main-title-sec">
                    <h2>all post:</h2>
                </div>
                <div class="posts-container">
                    <?php
                    foreach ($rows as $row) {
                        echo '
                        <div class="vt-card">
                            <img class="vt-card-image" src=' . $row['postPic'] . ' alt="">
                            <div class="vt-card-content">
                                <div class="vt-card-header">' . $row['postTitle'] . '</div>
                                <div class="vt-card-info">' . $row['postDecraption'] . '</div>
                                <a href="post-control.php?postID=' . $row['postID'] . '" class="vt-card-btn">view all the content</a>
                            </div>
                        </div>';
                    }
                    ?>
                </div>
            </div>
        <?php
        } elseif ($control == 'insert-N-posts') {

            if (isset($_POST['add-post'])) {
                $postTitle = $_POST['postTitle'];
                $postDecraption = $_POST['postDecraption'];
                $postStatus = $_POST['postStatus'];
                $nutitionstsID = $_SESSION['nutritionistID'];
                $countFile = count($_FILES['files']['name']);
                $query = "INSERT INTO post(postTitle,postDecraption,postPic,postStatus,nutitionstsID)
                         VALUES(?,?,?,?,?)";
                $stmt = $conn->prepare($query);
                for ($i = 0; $i < $countFile; $i++) {
                    $image = $_FILES['files']['name'][$i];
                    $targetFile = "../images/imagesPost/" . $image;
                    $fileEX = pathinfo($targetFile, PATHINFO_EXTENSION);
                    $fileEX = strtolower($fileEX);
                    $validExtension = array('png', 'jpg', 'jpeg');
                    if (in_array($fileEX, $validExtension)) {
                        if (move_uploaded_file($_FILES['files']['tmp_name'][$i], $targetFile)) {
                            $stmt->execute(array($postTitle, $postDecraption, $targetFile, $postStatus, $nutitionstsID));
                            $theMessage = '<div class="container"><div class="alert alert-success"> Inserted Successfully</div></div>';
                            redirecthome($theMessage, 'profile.php?control=pers-posts');
                        }
                    }
                }
            }
        } elseif($control == 'pers-trainees') { 
            $nutitionstsID = $_SESSION['nutritionistID'];
            $stmt = $conn->prepare("SELECT * FROM nutritionists
                                    INNER JOIN trainees
                                    ON nutritionists.nutritionistID=trainees.nutritionistID
                                    WHERE nutritionists.nutritionistID=?");
            $stmt->execute(array($nutitionstsID));
            $Rows = $stmt->fetchAll();
            $counter = 0;
            ?>
            <div class="container">
                <div class="main-title-sec">
                    <h2>my trainees:</h2>
                    <a href="trainees-control2.php?control=allMessages" class="add-btn">all messages</a>
                </div>
                <div class="table-responsive">
                    <table class="bills-table text-center">
                        <thead class="">
                            <th>#</th>
                            <th>trainee name</th>
                            <th>trainee email</th>
                            <th>controls</th>
                        </thead>
                        <tbody>
                        <?php
                            foreach ($Rows as $row) {
                                $counter++;
                                echo '
                                <tr>
                                    <td>' . $counter . '</td>
                                    <td>' . $row['traineeName'] . '</td>
                                    <td>' . $row['traineeEmail'] . '</td>
                                    <td>
                                        <a href="trainees-control2.php?traineeID=' . $row['traineeID'] . ' " class="btn btn-primary">view</a>
                                    </td>
                                </tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php
        } elseif($control == 'edit') { 
            $nutritionistID = $_SESSION['nutritionistID'];
            $stmt = $conn->prepare("SELECT * FROM nutritionists WHERE nutritionistID=?");
            $stmt->execute(array($nutritionistID));
            $row = $stmt->fetch();
            ?>
            <div class="container">
                <form class="form" action="?control=update" enctype="multipart/form-data" method="POST">
                    <h2 class="form-title">edit information</h2>
                    <div class="input-field">
                        <label for="">User Name:</label>
                        <input class="input text-input" type="text" value="<?php echo $row['nutritionistName']; ?>" name="nutritionistName" autocomplete="off" placeholder="name must be higher than 6 characters">
                    </div>
                    <div class="input-field">
                        <label for="">full name:</label>
                        <input class="input pass-input" type="text" value="<?php echo $row['nutritionistFullName']; ?>" name="nutritionistFullName" autocomplete="off" placeholder="full name go here">
                    </div>
                    <div class="input-field">
                        <label for="">email:</label>
                        <input class="input pass-input" type="email" value="<?php echo $row['nutritionistEmail']; ?>" name="nutritionistEmail" autocomplete="off" placeholder="EX: account@gmail.com">
                    </div>
                    <div class="input-field">
                        <label for="">mobile number:</label>
                        <input class="input pass-input" type="number" value="<?php echo $row['nutritionistMobileNum']; ?>" name="nutritionistMobileNum" autocomplete="off" placeholder="EX: +963 992 992 992">
                    </div>
                    <div class="input-field">
                        <label for="">piture:</label>
                        <input class="input img-input" type="file" name="files[]" multiple>

                        <input class="input img-input" hidden type="text" name="nutritionistPic" value="<?php echo $row['nutritionistPic'] ?>" >
                    </div>
                    <div class="submit-field">
                        <input class="input submit-btn" type="submit" name="editnutritionist">
                    </div>
                </form>
            </div>
        <?php
        }  elseif($control == 'update') { 
            if(isset($_POST['editnutritionist'])) {
                $countFile = count($_FILES['files']['name']);
                for ($i = 0 ; $i < $countFile ; $i++) {
                    $image = $_FILES['files']['name'][$i];
                    if ($image == null) {
                        $nutritionistID = $_SESSION['nutritionistID'];
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
                            $TheMessages = '<div class="container"><div class="alert alert-success">Recored Updated </div></div>';
                            session_unset();
                            session_destroy();
                            redirecthome($TheMessages,'index.php');
                        }
                    } else {
                        $nutritionistID = $_SESSION['nutritionistID'];
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
                                    $TheMessages = '<div class="container"><div class="alert alert-success">Recored Updated </div></div>';
                                    session_unset();
                                    session_destroy();
                                    redirecthome($TheMessages,'index.php');
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    elseif(isset($_SESSION['traineeID'])) { ?>
        <?php if($control == 'profile') {
            $traineeID = $_SESSION['traineeID'];
            $stmt = $conn->prepare("SELECT * FROM trainees
                                    INNER JOIN traineesbody
                                    ON trainees.traineeID = traineesbody.traineeID
                                    WHERE trainees.traineeID=?");
            $stmt->execute(array($traineeID));
            $row = $stmt->fetch();
            ?>
            <div class="container">
                <div class="main-title-sec">
                    <h2><?php echo $row['traineeName'] ?> information:</h2>
                </div>
                <div class="content" style="width: 90%;">
                    <div class="content-img">
                        <img src="<?php echo $row['traineePic'] ?>" alt="hello">
                    </div>
                    <div class="content-info">
                        <div class="label name">
                            <span>name:</span><span><?php echo $row['traineeName'] ?></span>
                        </div>
                        <div class="label fullname">
                            <span>full name:</span><span><?php echo $row['traineeFullName'] ?></span>
                        </div>
                        <div class="label email">
                            <span>email:</span><span><?php echo $row['traineeEmail'] ?></span>
                        </div>
                        <div class="label mobile-num">
                            <span>number:</span><span><?php echo $row['traineeMobileNum'] ?></span>
                        </div>
                    </div>
                    <div class="content-info">
                        <div class="label name">
                            <span>wieght:</span><span><?php echo $row['weight'] ?></span>
                        </div>
                        <div class="label fullname">
                            <span>height:</span><span><?php echo $row['height'] ?></span>
                        </div>
                        <div class="label email">
                            <span>age:</span><span><?php echo $row['age'] ?></span>
                        </div>
                        <div class="label mobile-num">
                            <span>health state:</span><span><?php echo $row['healthState'] ?></span>
                        </div>
                    </div>
                </div>
                <div class="control-btn">
                    <a href="trainees-control.php?control=viewMedical&traineeID=<?php echo $_SESSION['traineeID']; ?>" class="btn btn-light text-dark">medicals</a>
                    <a href="?control=edit&traineeID=<?php echo $_SESSION['traineeID']; ?>" class="btn btn-danger">edit main info</a>
                    <a href="?control=editBody&traineeID=<?php echo $_SESSION['traineeID']; ?>" class="btn btn-light text-dark">edit body info</a>
                </div>
                <?php
                        $traineeID = $_SESSION['traineeID'];
                        $stmt = $conn->prepare('SELECT * FROM trainees
                                                INNER JOIN subscription_payments
                                                ON trainees.traineeID=subscription_payments.traineeID
                                                INNER JOIN admins
                                                ON admins.adminID=subscription_payments.adminID
                                                WHERE trainees.traineeID=?');
                        $stmt->execute(array($traineeID));
                        $rows = $stmt->fetchAll();
                        $count = 0;
                ?>
                <div class="main-title-sec">
                    <h2>my payments</h2>
                </div>
                <div class="table-responsive">
                    <table class="bills-table">
                        <thead>
                            <th>#</th>
                            <th>Payment Maker</th>
                            <th>Payment Date</th>
                            <th>Full Payment value</th>
                            <th>Payment Value</th>
                            <th>Rest Of Total</th>
                        </thead>
                        <?php
                        foreach($rows as $row) {
                            $count++;
                            echo'<tbody>
                            <tr>
                                <td>'.$count.'</td>
                                <td>'.$row['adminName'].'</td>
                                <td>'.$row['subsPaymentDate'].'</td>
                                <td>'.$row['totalPaymentVal'].'</td>
                                <td>'.$row['paymentValue'].'</td>
                                <td>'.$row['restOfTotal'].'</td>
                            </tr>
                        </tbody>';
                        }
                        ?>
                    </table>
                </div>
            </div>
        <?php
        } elseif($control == 'edit') {
            $traineeID = $_GET['traineeID'];
            $stmt = $conn->prepare("SELECT * FROM trainees WHERE traineeID=?");
            $stmt->execute(array($traineeID));
            $row = $stmt->fetch();
            ?>
            <div class="container">
                <form class="form " action="?control=editTrainee" enctype="multipart/form-data" method="POST">
                    <h2 class="form-title">edit information</h2>
                    <div class="input-field">
                        <label for="">User Name:</label>
                        <input class="input text-input" type="text"  value="<?php echo $row['traineeName']; ?>" name="traineeName" autocomplete="off" placeholder="name must be higher than 6 characters">
                    </div>
                    <div class="input-field">
                        <label for="">full name:</label>
                        <input class="input pass-input" type="text"  value="<?php echo $row['traineeFullName']; ?>" name="traineeFullName" autocomplete="off" placeholder="full name go here">
                    </div>
                    <div class="input-field">
                        <label for="">email:</label>
                        <input class="input pass-input" type="email" value="<?php echo $row['traineeEmail'] ?>" name="traineeEmail" autocomplete="off" placeholder="EX: account@gmail.com">
                    </div>
                    <div class="input-field">
                        <label for="">mobile number:</label>
                        <input class="input pass-input" type="number" value="<?php echo $row['traineeMobileNum'] ?>" name="traineeMobileNum" autocomplete="off" placeholder="EX: +963 992 992 992">
                    </div>
                    <div class="input-field">
                        <label for="">piture:</label>
                        <input class="input img-input" type="file" name="files[]" multiple>
                    </div>
                    <div class="input-field">
                        <input class="input img-input" type="text" hidden value="<?php echo $row['traineePic']; ?>" name="traineePic" multiple>
                    </div>
                    <div class="input-field">
                        <input class="input img-input" type="text" hidden value="<?php echo $row['traineeID']; ?>" name="traineeID" multiple>
                    </div>
                    <div class="submit-field">
                        <input class="input submit-btn" type="submit" name="editTrainee">
                    </div>
                </form>
            </div>
        <?php
        } elseif ($control == 'editTrainee') {
            if(isset($_POST['editTrainee'])) {
                $countFile = count($_FILES['files']['name']);
                for ($i = 0 ; $i < $countFile ; $i++) {
                    $image = $_FILES['files']['name'][$i];
                    if ($image == null) {
                        $traineeID = $_SESSION['traineeID'];
                        $traineeName = $_POST['traineeName'];
                        $traineeFullName = $_POST['traineeFullName'];
                        $traineeEmail = $_POST['traineeEmail'];
                        $traineeMobileNum = $_POST['traineeMobileNum'];
                        $traineePic = $_POST['traineePic'];

                        $stmt = $conn->prepare("UPDATE trainees SET traineeName=?,
                                                        traineeFullName=?,
                                                        traineeEmail=?,
                                                        traineeMobileNum=?,
                                                        traineePic=?
                                                    WHERE traineeID=?");
                        $stmt->execute(array($traineeName,$traineeFullName,$traineeEmail,$traineeMobileNum,$traineePic,$traineeID));
                        $row = $stmt->rowCount();
                        if($row > 0) {
                            $TheMessages = '<div class="container"><div class="alert alert-success">Recored Updated </div></div>';
                            session_unset();
                            session_destroy();
                            redirecthome($TheMessages,'index.php');
                        }
                    } else {
                        $traineeID = $_SESSION['traineeID'];
                        $traineeName = $_POST['traineeName'];
                        $traineeFullName = $_POST['traineeFullName'];
                        $traineeEmail = $_POST['traineeEmail'];
                        $traineeMobileNum = $_POST['traineeMobileNum'];
                        $traineePic = $_POST['traineePic'];
                        if(file_exists($traineePic)) {
                            $imageStatus = unlink($traineePic);
                        }
                        $countFile = count($_FILES['files']['name']);
                        
                        $stmt = $conn->prepare("UPDATE trainees SET traineeName=?,
                                                        traineeFullName=?,
                                                        traineeEmail=?,
                                                        traineeMobileNum=?,
                                                        traineePic=?
                                                    WHERE traineeID=?");
                        for($i = 0 ; $i < $countFile ; $i++) {
                            $image = $_FILES['files']['name'][$i];
                            $targetFile = "../images/imagesTraineers/" . $image;
                            $fileEX = pathinfo($targetFile,PATHINFO_EXTENSION);
                            $fileEX = strtolower($fileEX);
                            $validExtension = array('png','jpg','jpeg');
                            if(in_array($fileEX,$validExtension)) {
                                if(move_uploaded_file($_FILES['files']['tmp_name'][$i],$targetFile)) {
                                    $stmt->execute(array($traineeName,$traineeFullName,$traineeEmail,$traineeMobileNum,$traineePic,$traineeID));
                                    $TheMessages = '<div class="container"><div class="alert alert-success">Recored Updated </div></div>';
                                    session_unset();
                                    session_destroy();
                                    redirecthome($TheMessages,'index.php');
                                }
                            }
                        }
                    }
                }
            
        }
        } elseif($control == 'editBody') {
                $traineeID = $_SESSION['traineeID'];
                $stmt = $conn->prepare("SELECT * FROM trainees
                                        INNER JOIN traineesbody
                                        ON trainees.traineeID=traineesbody.traineeID
                                        WHERE trainees.traineeID=?");
                $stmt->execute(array($traineeID));
                $row = $stmt->fetch();
            ?>
            <div class="container">
                <form class="form" action="profile.php?control=update" method="POST">
                    <h2 class="form-title">edit body information</h2>
                    <div class="input-field">
                        <label for="">weight:</label>
                        <input class="input pass-input" type="text" value="<?php echo $row['weight']; ?>" name="weight">
                    </div>
                    <div class="input-field">
                        <label for="">height:</label>
                        <input class="input pass-input" type="text" value="<?php echo $row['height']; ?>" name="height">
                    </div>
                    <div class="input-field">
                        <label for="">age:</label>
                        <input class="input pass-input" type="date" value="<?php echo $row['age']; ?>" name="age">
                    </div>
                    <div class="input-field">
                        <label for="">health state:</label>
                        <input class="input pass-input" type="text" value="<?php echo $row['healthState']; ?>" name="healthState">
                    </div>
                    <input type="text" name="traineeBodyID " hidden value="<?php echo $row['traineeBodyID'] ?>">

                    <div class="submit-field">
                        <input class="input submit-btn" type="submit" name="editBody">
                    </div>
                </form>
            </div>
        <?php
        } elseif($control == 'update') {
            if(isset($_POST['editBody'])) {
                $weight = $_POST['weight'];
                $height = $_POST['height'];
                $age = $_POST['age'];
                $healthState = $_POST['healthState'];
                $traineeID = $_SESSION['traineeID'];
               
                $stmt = $conn->prepare("UPDATE traineesbody SET
                                                                weight=?,
                                                                height=?,
                                                                age=?,
                                                                healthState=?
                                                WHERE traineeID=?");
                $stmt->execute(array($weight,$height,$age,$healthState,$traineeID));
                $row = $stmt->rowCount();
                if($row > 0) {
                    $TheMessages = '<div class="container"><div class="alert alert-success">Recored Updated </div></div>';
                    redirecthome($TheMessages,'profile.php');
                }
            }
        }
    }

    include $tpl . "footer.inc";
?>