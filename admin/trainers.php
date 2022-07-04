<?php
session_start();
$TitlePage = 'Trainer Page';

if(isset($_SESSION['adminName'])) {

    include 'init.php';

    $control = isset($_GET['control'])? $_GET['control'] : 'Manage';?>

    <link rel="stylesheet" href="<?php echo $css;?>dashboard.css">
    <link rel="stylesheet" href="<?php echo $css;?>trainers.css">
    <link rel="stylesheet" href="<?php echo $css;?>user-info.css">
    <link rel="stylesheet" href="<?php echo $css;?>styling-forms.css">

    <!-- frontend -->
    <?php 
        include $tpl . "navbar.inc";
    ?>
    <?php
    if($control == 'Manage') { ?>

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
                <h2>all trainers:</h2>
                <a href="?control=add" class="add-btn"><span class="add-icon"><i class="fas fa-plus"></i></span>add new trainer</a>
            </div>

            <div class="panel-container">
                <div class="panel">
                    <h3 class="trainer-panel-btn">view trainers:</h3>
                    <div class="panel-content trainees-panel">
                        <?php
                        if(isset($_GET['search'])) {
                            $searchValue = $_GET['search'];
                            $stmt2 =  $stmt = $conn->prepare("SELECT * FROM trainer
                                                                WHERE trainerName LIKE ?");
                            $stmt->execute(array("%$searchValue%"));
                            $rows = $stmt->fetchAll();
                            $count = $stmt->rowCount();
                            if($count > 0) {
                                foreach($rows as $row) {
                                    echo '<div class="panel-info">
                                    <p>'.$row['trainerFullName'].'</p>
                                    <a href="trainers.php?control=view&trainerID='.$row['trainerID'].'">view</a>
                                    <a href="trainers.php?control=edit&trainerID='. $row['trainerID'] .'">edit</a>
                                    <a href="trainers.php?control=Delete&trainerID='. $row['trainerID'] .'">delete</a>
                                    </div>';
                                }
                            } else {
                                echo '<div class="panel-info">
                                <p style="color: #f00">no records was found</p>
                                </div>';
                            }
                        } else {
                            $stmt = $conn->prepare("SELECT * FROM trainer");
                            $stmt->execute();
                            $rows = $stmt->fetchAll();
                            foreach($rows as $row) {
                                echo '<div class="panel-info">
                                <p>'.$row['trainerFullName'].'</p>
                                <a href="trainers.php?control=view&trainerID='.$row['trainerID'].'">view</a>
                                <a href="trainers.php?control=edit&trainerID='. $row['trainerID'] .'">edit</a>
                                <a href="trainers.php?control=Delete&trainerID='. $row['trainerID'] .'">delete</a>
                                </div>';
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="latest-trainers-posts"> 
                <div class="main-title-sec">
                    <h2>latest trainers posts:</h2>
                </div>
                <div class="latest-posts-cont">
                    <?php
                       $stmt = $conn->prepare("SELECT * FROM trainer
                                               INNER JOIN post
                                               ON trainer.trainerID = post.trainerID
                                               ORDER BY post.postID LIMIT 6 ");
                        $stmt->execute();
                        $rows = $stmt->fetchAll();
                        foreach($rows as $row) {
                            echo '
                            <div class="post-card">
                                <img class="post-image" src="'.$row['postPic'].'" alt="">
                                <div class="post-content">
                                    <div class="post-header">'. $row['postTitle'] .'</div>
                                    <div class="post-info">'. $row['postDecraption'] .'</div>
                                    <a href="#" class="post-btn">view</a>
                                </div>
                            </div>';
                        }
                    ?>
                </div>
            </div>
        </div>

       
    <?php
    } 
    elseif($control == 'view') {
            $trainerID = $_GET['trainerID'];
            $stmt = $conn->prepare('SELECT * FROM trainer WHERE trainerID = ?');
            $stmt->execute(array($trainerID));
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
                <h2><span class="member-name"><?php  echo $rows['trainerName'];?></span> Information:</h2>
            </div>
            <div class="content">
                <div class="content-img">
                    <img src="<?php echo $rows['trainerPic'];?>"alt="hello">
                </div>
                <div class="content-info">
                    <div class="label name">
                        <span>name:</span><span><?php echo $rows['trainerName'];?></span>
                    </div>
                    <div class="label fullname">
                        <span>full name:</span><span><?php echo $rows['trainerFullName'];?></span>
                    </div>
                    <div class="label email">
                        <span>email:</span><span><?php echo $rows['trainerEmail'];?></span>
                    </div>
                    <div class="label mobile-num">
                        <span>mobile number:</span><span><?php echo $rows['trainerMobileNum']; ?></span>
                    </div>
                </div>
            </div>
            <div class="control-btn">
                <a href="?control=edit&trainerID=<?php echo $trainerID;?>">edit</a>
                <a href="?control=Delete&trainerID=<?php echo $trainerID;?>">delete</a>
            </div>
            <div class="trainer-posts">
                <div class="main-title-sec">
                    <h2><?php  echo $rows['trainerName'];?> posts:</h2>
                </div>
                    <div class="latest-posts-cont">
                        <?php
                            $stmt = $conn->prepare("SELECT * FROM trainer
                                                    INNER JOIN post
                                                    ON trainer.trainerID = post.trainerID
                                                    WHERE trainer.trainerID =?");
                            $stmt->execute(array($trainerID));
                            $rows = $stmt->fetchAll();
                            foreach($rows as $row){
                                echo '
                                    <div class="post-card">
                                        <img class="post-image" src="'.$row['postPic'].'" alt="">
                                        <div class="post-content">
                                            <div class="post-header">'.$row['postTitle'].'</div>
                                            <div class="post-info">'.$row['postDecraption'].'</div>
                                            <a href="#" class="post-btn">view</a>
                                        </div>
                                    </div>
                                ';
                        }
                        ?> 
                    </div>
            <div class="trainer-trainees">
                <div class="main-title-sec">
                    <h2><span class="member-name">kareem</span> trainees:</h2>
                </div>
                <table class="bills-table">
                    <?php
                    $stmt = $conn->prepare("SELECT * FROM trainer
                                            INNER JOIN trainees
                                            ON trainer.trainerID  = trainees.trainerID
                                            WHERE trainer.trainerID  = ?");
                    $stmt->execute(array($trainerID));
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
                    foreach($rows as $row) {
                        echo' 
                            <tr>
                                 <td>'.$row['traineeID'].'</td>
                                 <td>'.$row['traineeName'].'</td>
                                 <td>'.$row['traineeFullName'].'</td>
                                 <td>'.$row['traineeEmail'].'</td>
                                <td>'.$row['traineeMobileNum'].'</td>
                           </tr>';
                }
                   ?>
                </table>
            </div>
        </div>

<?php

    } elseif($control == "add") { ?>

        <div class="container form-container">
            <div class="header-sec">
                <div class="icon"><i class="fas fa-bars"></i></div>
                <a href="logout.php" class="logut-btn">
                    logout<i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
            <div class="form add-form">
                <h2 class="login">add trainer</h2>
                <form action="trainers.php?control=insert" method="POST" enctype="multipart/form-data">
                    <div class="input-field">
                        <label for="">User Name:</label>
                        <input class="input text-input" type="text" name="trainerName" autocomplete="off" placeholder="name must be higher than 6 characters">
                    </div> 
                    <div class="input-field">
                        <label for="">full Name:</label>
                        <input class="input text-input" type="text" name="trainerFullName" autocomplete="off" placeholder="name must be higher than 6 characters">
                    </div>   
                    <div class="input-field">
                        <label for="">email:</label>
                        <input class="input text-input" type="text" name="trainerEmail" autocomplete="off" placeholder="name must be higher than 6 characters">
                    </div>  
                    <!-- <div class="name-error-message">
                    </div> -->
                    <div class="input-field">
                        <label for="">password:</label>
                        <input class="input pass-input" type="password" name="trainerPass" autocomplete="new-password" placeholder="password must be higher than 10 characters">
                        <!-- <ion-icon class="eye" name="eye-outline"></ion-icon>
                        <ion-icon class="close-eye eye active" name="eye-off-outline"></ion-icon> -->
                    </div>
                    <div class="input-field">
                        <label for="">Mobile Number:</label>
                        <input class="input pass-input" type="test" name="trainerMobileNum"autocomplete="off" placeholder="Enter The Mobile Number">
                        <!-- <ion-icon class="eye" name="eye-outline"></ion-icon>
                        <ion-icon class="close-eye eye active" name="eye-off-outline"></ion-icon> -->
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

                                foreach($sections as $section) {
                                    
                                    echo '<option value="'.  $section['sectionID'] . '">' . $section['sectionName'] . '</option>';
                                }
                        ?>
                        </select>
                    </div>  
                    <div class="input-field">
                        <input class="submit-btn" type="submit" name="add-trainer">
                    </div>
                    
                </form>
            </div>
        </div>
    <?php
    } 
    elseif($control == 'insert'){
        if(isset($_POST['add-trainer'])) {

       $trainerName        = $_POST['trainerName'];

       $trainerFullName    = $_POST['trainerFullName'];

       $trainerEmail       = $_POST['trainerEmail'];

       $trainerPassword    = $_POST['trainerPass'];

       $trainerMobileNum   = $_POST['trainerMobileNum'];

       $sectionName        = $_POST['section'];

       $hashPassword       = sha1($trainerPassword);

       $countFile = count($_FILES['files']['name']);
       
       $query = "INSERT INTO trainer(trainerName,trainerFullName,trainerEmail,trainerPass,trainerMobileNum,trainerPic,sectionID)
                 VALUES(?,?,?,?,?,?,?)";

       $stmt = $conn->prepare($query);

       for($i = 0; $i<$countFile; $i++) {

           $fileName = $_FILES['files']['name'][$i];
           
           $target_file = "../images/imagesTrainers/" . $fileName;

           $fileExtension = pathinfo($target_file,PATHINFO_EXTENSION);

           $fileExtension = strtolower($fileExtension);

           $fileType = array("png","jpg","jpeg");

           if(in_array($fileExtension,$fileType)) {

               if(move_uploaded_file($_FILES['files']['tmp_name'][$i],$target_file)) {

                   $stmt->execute(array($trainerName,$trainerFullName,$trainerEmail,$hashPassword,$trainerMobileNum,$target_file, $sectionName));
                   $theMessage = '<div class="container"><div class="alert alert-success"> the Trainers has been inserted successfully</div></div>';
                   redirecthome($theMessage, '?control=Manage');
                }
           }
       }
    }

    } elseif($control == 'Delete'){
        $trainerID = $_GET['trainerID'];
        $stmt = $conn->prepare("SELECT * FROM trainer WHERE trainerID=?");
        $stmt->execute(array($trainerID));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        extract($row); 
        $trainerPic = $row['trainerPic'];
        if(file_exists($trainerPic)) {
            $picState = unlink($trainerPic);
        } 
        $delete = $conn->prepare("DELETE FROM trainer WHERE trainerID=?");
        $delete->execute(array($trainerID));
        $TheMessages = '<div class="container"><div class="alert alert-success">Delete Successfully</div></div>';
        redirecthome($TheMessages,'trainers.php');

    } elseif($control == 'edit') { 
        $trainerID = $_GET['trainerID'];
        $stmt = $conn->prepare("SELECT * FROM trainer WHERE trainerID=?");
        $stmt->execute(array($trainerID));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        extract($row);
        ?>
        <div class="container form-container">
            <div class="header-sec">
                <div class="icon"><i class="fas fa-bars"></i></div>
            </div>
            <div class="form add-form">
                <h2 class="login">edit <?php echo $row['trainerName'];?> trainer</h2>
                <form action="trainers.php?control=update" method="POST" enctype="multipart/form-data">
                    <div class="input-field">
                        <label for="">User Name:</label>
                        <input class="input text-input" type="text" name="trainerName" value="<?php echo $row['trainerName']; ?>" autocomplete="off" placeholder="name must be higher than 6 characters">
                    </div> 
                    <div class="input-field">
                        <label for="">full Name:</label>
                        <input class="input text-input" type="text" name="trainerFullName"value="<?php echo $row['trainerFullName']; ?>"  autocomplete="off" placeholder="name must be higher than 6 characters">
                    </div>   
                    <div class="input-field">
                        <label for="">email:</label>
                        <input class="input text-input" type="text" name="trainerEmail" value="<?php echo $row['trainerEmail'];?>" autocomplete="off" placeholder="name must be higher than 6 characters">
                    </div>  
                    <div class="input-field">
                        <label for="">Mobile Number:</label>
                        <input class="input pass-input" type="test" name="trainerMobileNum" value="<?php echo $row['trainerMobileNum']; ?>" autocomplete="off" placeholder="Enter The Mobile Number">
                    </div>
                     <div class="input-field">
                        <input type="text" hidden name="trainerID" value="<?php echo $row['trainerID'];?>">
                        <input type="test" hidden name="trainerPic" value="<?php echo $row['trainerPic'];?>" autocomplete="off" placeholder="Enter The Mobile Number">
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

                                $stmt2 = $conn->prepare("SELECT * FROM trainer WHERE trainerID=? LIMIT 1");
                                $stmt2->execute(array($trainerID));
                                $result = $stmt2->fetch();
                                
                                $stmt = $conn->prepare("SELECT * FROM section");

                                $stmt->execute();

                                $sections = $stmt->fetchAll();

                                foreach($sections as $section) {

                                    if($result['sectionID'] == $section['sectionID']) {
                                        echo '<option selected value="'.  $section['sectionID'] . '">' . $section['sectionName'] . '</option>';
                                    } else {
                                        echo '<option value="'.  $section['sectionID'] . '">' . $section['sectionName'] . '</option>';
                                    }
                                }
                        ?>
                        </select>
                    </div>  
                    <div class="input-field">
                        <input class="submit-btn" type="submit" name="edit-trainer">
                    </div>
                    
                </form>
            </div>
        </div>
    <?php
    }  elseif ($control == "update") {
        if(isset($_POST['edit-trainer'])) {
            $countFile = count($_FILES['files']['name']);
                for ($i = 0 ; $i < $countFile ; $i++) {
                    $image = $_FILES['files']['name'][$i];
                    if ($image == null) {
                        $trainerID = $_POST['trainerID'];
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
                            $theMessage = '<div class="container"><div class="alert alert-success"> the Trainers has been Update successfully</div></div>';
                            redirecthome($theMessage, '?control=Manage');
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
                                    $theMessage = '<div class="container"><div class="alert alert-success"> the Trainers has been Update successfully</div></div>';
                   redirecthome($theMessage, '?control=Manage');
                                }
                            }
                        }
                    }
                }
            }
    }
}?>

<script src="<?php echo $js;?>navbar.js"></script>
<script src="<?php echo $js;?>main.js"></script>

<?php
    include $tpl . "footer.inc";
?>