<?php

    session_start();
    include "init.php";
?>

<link rel="stylesheet" href="<?php echo $css; ?>dashboard.css">
<link rel="stylesheet" href="<?php echo $css; ?>section.css">
<link rel="stylesheet" href="<?php echo $css; ?>styling-forms.css">

<?php 
    include $tpl.'navbar.inc';
    $control = isset($_GET['control']) ? $_GET['control'] : 'Manage';
?>
<?php 
if($control == 'Manage') { ?>
    <div class="container">
    <div class="header-sec">
        <div class="icon"><i class="fas fa-bars"></i></div>
        <a href="logout.php" class="logut-btn">
            logout<i class="fas fa-sign-out-alt"></i>
        </a>
    </div>
        <div class="main-title-sec">
            <h2>all sections:</h2>
            <a href="?control=add" class="add-btn"><span class="add-icon"><i class="fas fa-plus"></i></span>add new section</a>
        </div>
        <div class="panel-container">
            <div class="panel">
                <h3 class="trainees-panel-btn">all Sections:</h3>
                <div class="panel-content trainees-panel">
                    <?php 
                        $stmt = $conn->prepare("SELECT * 
                                                FROM section");
                            $stmt->execute();
                            $rows = $stmt->fetchAll();
                            foreach($rows as $row) {
                                echo '<div class="panel-info">';
                                    echo '
                                        <p>'.$row['sectionName'].'</p>
                                        <a href="sections.php?control=View&sectionID='.$row['sectionID'].'">view</a>
                                        <a href="sections.php?control=edit&sectionID='.$row['sectionID'].'">Edit</a>
                                        <a href="sections.php?control=delete&sectionID='.$row['sectionID'].'">delete</a>'
                                    ;
                                echo '</div>';
                            }
                        ?>
                </div>
            </div>
        </div>
    </div>
<?php
} elseif($control == 'View') {
    $sectionID = $_GET['sectionID'];
    $stmt = $conn->prepare("SELECT * FROM section WHERE sectionID=?");
    $stmt->execute(array($sectionID));
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
            <h2>quick overview about <span><?php echo $rows['sectionName']; ?></span> section:</h2>
        </div>
        <div class="card-container">
            <a href="sections.php?control=viewTrainees&sectionID=<?php echo $sectionID;?>" class="card">
                <span><i class="card-icon fas fa-running"></i></span>
                <span class="name">all trainees in <span class="section-name"><?php echo $rows['sectionName']; ?></span> section</span>
                <span class="num">
                    <?php
                        $stmt = $conn->prepare("SELECT * FROM section
                                               INNER JOIN trainees
                                               ON section.sectionID=trainees.sectionID
                                               WHERE section.sectionID=?");
                        $stmt->execute(array($sectionID));
                        $row = $stmt->rowCount();
                        echo $row;
                    ?>
                </span>
            </a>
            <a href="sections.php?control=viewTrainers&sectionID=<?php echo $sectionID;?>" class="card">
                <span><i class="card-icon fas fa-user"></i></span>  
                <span class="name">all trainers in <span class="section-name"><?php echo $rows['sectionName']; ?></span> section</span>
                <span class="num">
                <?php
                            $stmt = $conn->prepare("SELECT * FROM section
                                                    INNER JOIN trainer
                                                    ON section.sectionID=trainer.sectionID 
                                                    WHERE section.sectionID=?");
                            $stmt->execute(array($sectionID));
                            $row = $stmt->rowCount();
                            echo $row;
                ?>
                </span>
            </a>
            <a href="sections.php?control=viewnutritionist&sectionID=<?php echo $sectionID;?>" class="card">
                <span><i class="card-icon fas fa-user-md"></i></span>
                <span class="name">all nutritionist in <span class="section-name"><?php echo $rows['sectionName']; ?></span> section</span>
                <span class="num">
                    <?php
                           $stmt = $conn->prepare("SELECT * FROM section
                                                   INNER JOIN nutritionists
                                                   ON section.sectionID=nutritionists.sectionID 
                                                   WHERE section.sectionID=?");
                           $stmt->execute(array($sectionID));
                           $row = $stmt->rowCount();
                           echo $row;
                    ?>
                </span>
            </a>
        </div>
        <div class="main-title-sec">
            <h2>latest members joined to <span><?php echo $rows['sectionName']; ?></span> section:</h2>
        </div>
        <div class="panel-container">
            <div class="panel">
                <h3 class="trainees-panel-btn">latest trainees joined:</h3>
                <div class="panel-content active">
                <?php
                            $stmt = $conn->prepare("SELECT * FROM section
                            INNER JOIN trainees
                            ON section.sectionID=trainees.sectionID
                            WHERE section.sectionID=?
                            ORDER BY section.sectionID LIMIT 3");
                            $stmt->execute(array($sectionID));
                            $rows = $stmt->fetchAll();            
                    foreach($rows as $row){
                    echo '
                    <div class="panel-info">
                        <p>';
                        echo $row['traineeName'];
                        echo '
                        </p>
                        <a>view</a>
                    </div>';
                    }
                ?>
                </div>
            </div>
            <div class="panel">
                <h3 class="trainer-panel-btn">latest trainers joined:</h3>
                <div class="panel-content active">
                <?php
                        $stmt = $conn->prepare("SELECT * FROM section
                        INNER JOIN trainer
                        ON section.sectionID=trainer.sectionID 
                        WHERE section.sectionID=?
                        ORDER BY section.sectionID LIMIT 3");
                        $stmt->execute(array($sectionID));
                        $rows = $stmt->fetchAll();
                       
                foreach($rows as $row){
                    echo '
                    <div class="panel-info">
                        <p>';
                        echo $row['trainerName'];
                        echo '
                        </p>
                        <a>view</a>
                    </div>';
                    }
                ?>
                </div>
            </div>
            <div class="panel">
                <h3 class="nutrintionist-panel-btn">latest nutrintionist joined:</h3>
                <div class="panel-content active">
                <?php
                            $stmt = $conn->prepare("SELECT * FROM section
                            INNER JOIN nutritionists
                            ON section.sectionID=nutritionists.sectionID 
                            WHERE section.sectionID=?
                            ORDER BY section.sectionID LIMIT 3");
                            $stmt->execute(array($sectionID));
                            $rows = $stmt->fetchAll();
                          
                    foreach($rows as $row){
                    echo '
                    <div class="panel-info">
                        <p>';
                        echo $row['nutritionistName'];
                        echo '
                        </p>
                        <a>view</a>
                    </div>';
                    }
                ?>
                </div>
            </div>
        </div>
    </div>    
<?php
}elseif($control == 'add') {?>
    <div class="container form-container">
    <div class="header-sec">
        <div class="icon"><i class="fas fa-bars"></i></div>
        <a href="logout.php" class="logut-btn">
            logout<i class="fas fa-sign-out-alt"></i>
        </a>
    </div>
        <div class="form add-form">
            <h2 class="login">add new section</h2>
            <form action="sections.php?control=insert" method="POST" enctype="multipart/form-data">
                <div class="input-field">
                    <label for="">section Name:</label>
                    <input class="input text-input" type="text" name="sectionName" autocomplete="off" placeholder="name must be higher than 6 characters">
                </div> 
                <div class="input-field">
                    <label for="">section registerition price:</label>
                    <input class="input text-input" type="number" name="registeritionPrice" autocomplete="off" placeholder="name must be higher than 6 characters">
                </div> 
                <!-- <div class="name-error-message">
                </div> -->
                
                <!-- <div class="pass-error-message">
                </div> -->
                <div class="input-field">
                    <input class="submit-btn" type="submit" name="add-section">
                </div>
            </form>
        </div>
    </div>
<?php
} elseif($control == 'insert') {
    if(isset($_POST['add-section'])) {
        $sectionName = $_POST['sectionName'];
        $registeritionPrice = $_POST['registeritionPrice'];
        $stmt = $conn->prepare("INSERT INTO section(sectionName,registerPayment) VALUES(?,?)");
        $stmt->execute(array($sectionName,$registeritionPrice));
        $rows = $stmt->rowCount();
        if($rows > 0) {
            $theMessage = '<div class="container"><div class="alert alert-success"> the Section has been inserted successfully</div></div>';
            redirecthome($theMessage,'sections.php');
        }
    }

} elseif($control == 'viewTrainees') {
    $sectionID = $_GET['sectionID'];
    $stmt = $conn->prepare("SELECT * FROM section WHERE sectionID=? LIMIT 1");
    $stmt->execute(array($sectionID));
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
                <h2>all trainees in <span class="section-name"><?php echo $rows['sectionName']; ?></span> section:</h2>
                <a class="add-btn" href="trainees.php?control=add">add trainee</a>
            </div>
            <div class="panel-container">
                <div class="panel approved-trainees">
                    <h3 class="trainees-panel-btn">all trainees</h3>
                    <div class="panel-content trainees-panel">
                    <?php
                        $stmt = $conn->prepare("SELECT * 
                        FROM section 
                        INNER JOIN trainees
                        ON section.sectionID=trainees.sectionID  
                        WHERE section.sectionID=?");
                        $stmt->execute(array($sectionID));
                        $rows = $stmt->fetchAll();

                        foreach($rows as $row) {
                            echo '<div class="panel-info">';
                            echo '
                                <p>'.$row['traineeFullName'].'</p>
                                <a href="trainees.php?control=view&traineeID='.$row['traineeID'].'">view</a>
                                <a href="trainees.php?control=disable&traineeID='.$row['traineeID'].'">disable</a>
                                <a href="trainees.php?control=delete&traineeID='.$row['traineeID'].'">delete</a>'
                            ;
                            echo '</div>';
                        }
                    ?>
                    </div>
                </div>
            </div>
        </div>
<?php
} elseif($control == 'viewTrainers') { 
    $sectionID = $_GET['sectionID'];
    $stmt = $conn->prepare("SELECT * FROM section WHERE sectionID=? LIMIT 1");
    $stmt->execute(array($sectionID));
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
                <h2>all trainers in <span class="section-name"><?php echo $rows['sectionName']; ?></span></span> section:</h2>
                <a class="add-btn" href="trainers.php?control=add">add new trainer</a>
            </div>
            <div class="panel-container">
                <div class="panel approved-trainees">
                    <h3 class="trainees-panel-btn">all trainers</h3>
                    <div class="panel-content trainees-panel active">
                    <?php
                            $stmt = $conn->prepare("SELECT * 
                            FROM section 
                            INNER JOIN trainer
                            ON section.sectionID=trainer.sectionID  
                            WHERE section.sectionID=?");
                            $stmt->execute(array($sectionID));
                            $rows = $stmt->fetchAll();
                        foreach($rows as $row) {
                            echo '<div class="panel-info">';
                            echo '
                                <p>'.$row['trainerFullName'].'</p>
                                <a href="trainers.php?control=view&trainerID='.$row['trainerID'].'">view</a>
                                <a href="trainers.php?control=delete&trainerID='.$row['trainerID'].'">delete</a>'
                            ;
                            echo '</div>';
                        }
                        ?>
                        ?>
                    </div>
                </div>
            </div>
        </div>
<?php
} elseif($control == 'viewnutritionist') {
    $sectionID = $_GET['sectionID'];
    $stmt = $conn->prepare("SELECT * FROM section WHERE sectionID=? LIMIT 1");
    $stmt->execute(array($sectionID));
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
            <h2>all nutritionists in <span class="section-name"><?php echo $rows['sectionName']; ?></span></span> section:</h2>
        </div>
        <div class="panel-container">
            <div class="panel approved-trainees">
                <h3 class="trainees-panel-btn">all nutritionists</h3>
                <div class="panel-content active">
                    <?php
                                $stmt = $conn->prepare("SELECT * 
                                                        FROM section 
                                                        INNER JOIN nutritionists
                                                        ON section.sectionID=nutritionists.sectionID  
                                                        WHERE section.sectionID=?");
                                $stmt->execute(array($sectionID));
                                $rows = $stmt->fetchAll();

                            foreach($rows as $row) {
                                echo '<div class="panel-info">';
                                echo '
                                    <p>'.$row['nutritionistName'].'</p>
                                    <a href="Nutritionists.php?control=view&nutritionistID='.$row['nutritionistID'].'">view</a>
                                    <a href="Nutritionists.php?control=delete&nutritionistID='.$row['nutritionistID'].'">delete</a>'
                                ;
                                echo '</div>';
                            }
                    ?>
                </div>
            </div>
        </div>
    </div>
<?php
} elseif($control == 'delete') {
    $sectionID = $_GET['sectionID'];
    $stmt = $conn->prepare("DELETE FROM section WHERE sectionID = ?");
    $result = $stmt->execute(array($sectionID));
    if($result) {
       redirecthome("<div class='container'><div class='alert alert-success'>recored deleted</div></div>","?control=Manage");
    } else {
        echo 'no';
    }

}elseif($control == "edit") {
    $sectionID = $_GET['sectionID'];
    $stmt = $conn->prepare("SELECT * FROM section WHERE sectionID=?");
    $stmt->execute(array($sectionID));
    $row = $stmt->fetch();
    ?>
        <div class="container form-container">
    <div class="header-sec">
        <div class="icon"><i class="fas fa-bars"></i></div>
    </div>
        <div class="form add-form">
            <h2 class="login">Edit <?php echo $row['sectionName'];?> section</h2>
            <form action="sections.php?control=update" method="POST" enctype="multipart/form-data">
                <div class="input-field">
                    <label for="">section Name:</label>
                    <input class="input text-input" type="text" name="sectionName" value="<?php echo $row['sectionName'];?>" autocomplete="off" placeholder="name must be higher than 6 characters">
                    <input type="text" name="sectionID" hidden value="<?php echo $row['sectionID'];?>">
                </div> 
                <div class="input-field">
                    <label for="">Section Registerition Price:</label>
                    <input class="input text-input" type="text" name="registerPayment" value="<?php echo $row['registerPayment'];?>" autocomplete="off" placeholder="name must be higher than 6 characters">
                </div> 
                <div class="input-field">
                    <input class="submit-btn" type="submit" name="edit-section">
                </div>
<?php
} elseif($control == "update") {
    $sectionID = $_POST['sectionID'];
    $sectionName = $_POST['sectionName'];
    $registerPayment = $_POST['registerPayment'];
    $stmt = $conn->prepare("UPDATE section SET sectionName=?,registerPayment=?
                            WHERE sectionID=?");
    $stmt->execute(array($sectionName,$registerPayment,$sectionID));
    $row = $stmt->rowCount();
    if($row > 0) {
        $TheMessages = '<div class="container"><div class="alert alert-success">'.$row.'Recored Updated </div></div>';
        redirecthome($TheMessages,'sections.php');
    }
}
?>




<script src="<?php echo $js;?>navbar.js"></script>
<script src="<?php echo $js;?>main.js"></script>
<?php 
    include $tpl . 'footer.inc';
?>