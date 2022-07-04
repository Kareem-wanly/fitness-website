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
        $traineeID = $_GET['traineeID'];
        $stmt = $conn->prepare("SELECT * FROM trainees
                            INNER JOIN traineesbody
                            ON trainees.traineeID=traineesbody.traineeID
                            WHERE trainees.traineeID=?");
        $stmt->execute(array($traineeID));
        $row = $stmt->fetch(); ?>
        <div class="container">
            <div class="main-title-sec">
                <h2><?php echo $row['traineeName']; ?>information:</h2>
            </div>
            <div class="content" style="width: 90%;">
                <div class="content-img">
                    <img src=<?php echo $row['traineePic']; ?> alt="hello">
                </div>
                <div class="content-info">
                    <div class="label name">
                        <span>name:</span><span> <?php echo $row['traineeName']; ?></span>
                    </div>
                    <div class="label fullname">
                        <span>full name:</span><span><?php echo $row['traineeFullName']; ?></span>
                    </div>
                    <div class="label email">
                        <span>email:</span><span><?php echo $row['traineeEmail']; ?></span>
                    </div>
                    <div class="label mobile-num">
                        <span>number:</span><span><?php echo $row['traineeMobileNum']; ?></span>
                    </div>
                </div>
                <div class="content-info">
                    <div class="label name">
                        <span>weight:</span><span><?php echo $row['weight']; ?></span>
                    </div>
                    <div class="label fullname">
                        <span>height:</span><span><?php echo $row['height'] ?></span>
                    </div>
                    <div class="label email">
                        <span>age:</span><span><?php echo $row['age']; ?></span>
                    </div>
                    <div class="label mobile-num">
                        <span>health state:</span><span><?php echo $row['healthState']; ?></span>
                    </div>
                </div>
            </div>
            <a href="?control=viewMedical&traineeID=<?php echo $traineeID; ?>" class="btn btn-light">
                medical drugs
            </a>
            <?php
            $traineeName = $conn->prepare("SELECT * FROM trainees WHERE traineeID=?");
            $stmt->execute(array($traineeID));
            $row = $stmt->fetch();
            ?>
            <div class="main-title-sec">
                <h2><?php echo $row['traineeName'] ?> programs:</h2>
                <a href="program-controls.php?control=add&traineeID=<?php echo $traineeID; ?>" class="add-btn">add new program</a>
            </div>
            <?php
            $traineeID = $_GET['traineeID'];
            $stmt = $conn->prepare("SELECT * FROM trainees 
                                            INNER JOIN trainerprograms
                                            ON trainees.traineeID=trainerprograms.traineeID
                                            WHERE trainees.traineeID=?");
            $stmt->execute(array($traineeID));
            $rows = $stmt->fetchAll();
            $count = 0; ?>
            <div class="table-responsive">
                <table class="bills-table text-center">
                    <thead class="">
                        <th>#</th>
                        <th>Trainee Name</th>
                        <th>program title</th>
                        <th>program date</th>
                        <th>end date</th>
                        <th>controls</th>
                    </thead>
                    <?php
                    foreach ($rows as $row) {
                        $count++;
                        echo '<tbody>
                        <tr>
                            <td>' . $count . '</td>
                            <td>' . $row['traineeName'] . '</td>
                            <td>' . $row['programTitle'] . '</td>
                            <td>' . $row['programDate'] . '</td>
                            <td>' . $row['programEndDate'] . '</td>
                            <td>
                                <a href="program-controls.php?control=view&tpID=' . $row['tpID'] . '" class="btn btn-primary">view</a>
                                <a href="program-controls.php?control=edit&tpID=' . $row['tpID'] . '" class="btn btn-outline-primary">edit</a>
                            </td>
                        </tr>
                    </tbody>';
                    } ?>
                </table>
            </div>
        </div>

    <?php
    
    } elseif ($control == 'viewMedical') {
        $traineeID = $_GET['traineeID'];
        $stmt = $conn->prepare("SELECT * FROM trainees
                            INNER JOIN traineesmedical
                            ON trainees.traineeID=traineesmedical.traineeID
                            WHERE trainees.traineeID=?");
        $stmt->execute(array($traineeID));
        $rows = $stmt->fetchAll();
        $count = 0; ?>
        <!--Get Trainee Name -->
        <?php
        $stmt2 = $conn->prepare("SELECT * FROM trainees WHERE traineeID=?");
        $stmt2->execute(array($traineeID));
        $row2 = $stmt2->fetch();
        ?>
        <!-- End Get Trainee Name -->
        <div class="container">
            <div class="main-title-sec">
                <h2>all medical drugs <?php echo $row2['traineeName']; ?> take:</h2>
            </div>
            <div class="table-responsive">
                <table class="bills-table text-center" style="width: fit-content;">
                    <thead>
                        <th>#</th>
                        <th>drug name</th>
                        <th>drug doses</th>
                    </thead>
                    <?php
                    foreach ($rows as $row) {
                        $count++;
                        echo '<tbody>
                        <tr>
                            <td>' . $count . '</td>
                            <td>' . $row['medicalDrugName'] . '</td>
                            <td>' . $row['medicalDrugDoses'] . '</td>
                        </tr>
                    </tbody>';
                    } ?>
                </table>
            </div>
        </div>
    <?php
    } elseif($control == 'allMessages') {
        $trainerID = $_SESSION['trainerID'];
        $stmt = $conn->prepare("SELECT * FROM trainer
                                INNER JOIN message
                                ON trainer.trainerID=message.trainerID
                                WHERE trainer.trainerID=?");
        $stmt->execute(array($trainerID));
        $rows = $stmt->fetchAll();
        $count = 0;
    
        ?>
        
        <div class="container">
            <div class="main-title-sec">
                <h2>all readed messages</h2>
            </div>
            <div class="table-responsive">
                <table class="bills-table text-center">
                    <thead>
                        <th class="col-1">#</th>
                        <th class="col-6">His message</th>
                        <th class="col-2">controls</th>
                    </thead>
                    <?php
                    foreach($rows as $row) {
                     $count ++;   
                    echo'<tbody>
                        <tr>
                            <td>'.$count.'</td>
                            <td>'.$row['messageText'].'</td>
                            <td>
                                <a href="?control=message&messageID='.$row['messageID'].'" class="btn btn-primary">reply</a>
                                <a href="?control=delete&messageID='.$row['messageID'].'" class="btn btn-danger">delete</a>
                            </td>
                        </tr>
                    </tbody>';
                    }
                    ?>
                </table>
            </div>
        </div>
    <?php
    } elseif($control == 'delete'){
        $messageID = $_GET['messageID'];
        $stmt = $conn->prepare("DELETE FROM message WHERE messageID=?");
        $stmt->execute(array($messageID));
        $row = $stmt->rowCount();
        if($row > 0) {
            $TheMessages = '<div class="container"><div class="alert alert-success">Delete Successfully</div></div>';
            redirecthome($TheMessages, 'profile.php?control=pers-trainees');
        }
    } elseif($control == 'message') { 
            $messageID = $_GET['messageID'];
            $stmt = $conn->prepare("SELECT * FROM message
                                    INNER JOIN reply
                                    ON message.messageID=reply.messageID
                                    WHERE message.messageID=?");
            $stmt->execute(array($messageID));
            $row = $stmt->fetch();
            if($row > 0) {
                
        ?>
        <div class="container">
            <div class="main-title-sec">
                <h2>show the message and reply</h2>
            </div>
            <div class="message-con">
                <div class="message-content">
                    <div class="message-header">
                        <h3>message sent on</h3>
                        <div class="date"><?php echo $row['messageDate'] ?></div>
                    </div>
                    <div class="mes-content">
                        <p>
                           <?php echo $row['messageText']; ?>
                        </p>
                    </div>
                </div>
                <div class="message-content">
                    <div class="message-header">
                        <h3>replied on</h3>
                        <div class="date"><?php echo $row['replyDate']; ?></div>
                    </div>
                    <div class="mes-content">
                        <p>
                            <?php echo $row['replyText'];?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
            <?php } else { 
                $messageID = $_GET['messageID'];
                $stmt = $conn->prepare("SELECT * FROM message WHERE messageID=?");
                $stmt->execute(array($messageID));
                $row = $stmt->fetch();
                ?>
            ?>
             <div class="container">
            <div class="main-title-sec">
                <h2>show the message and reply</h2>
            </div>
            <div class="message-con">
                <div class="message-content">
                    <div class="message-header">
                        <h3>message sent on</h3>
                        <div class="date"><?php echo $row['messageDate']; ?></div>
                    </div>
                    <div class="mes-content">
                        <p>
                           <?php echo $row['messageText']; ?>
                        </p>
                    </div>
                </div>
                    <div class="message-content">
                        <div class="message-header">
                        <div class="date">
                        <form action="?control=insertReply" method="POST" class="form">
                            <div class="input-field">
                                <h4 class="text-center mb-2">type the reply</h4>
                                <textarea name="replyText"  cols="30" rows="5" class="input"></textarea>
                                </div>
                                <input hidden type="text" name="messageID" value="<?php echo $_GET['messageID'] ?>">
                            <div class="input-field  mt-2">
                                <input type="submit" name="Reply" class="input submit-btn">
                            </div>
                        </form>
                        </div>
                </div>
            </div>
        </div>
    <?php
    }
    } elseif($control == 'insertReply') {
        if (isset($_POST['Reply'])) {
            if(!empty($_POST['replyText'])) {
                $replyText = $_POST['replyText'];
                $messageID = $_POST['messageID'];
                $stmt = $conn->prepare("INSERT INTO reply(replyText,messageID)
                                        VALUES(?,?)");
                $stmt->execute(array($replyText,$messageID));
                $row = $stmt->rowCount();
                if ($row > 0) {
                    $TheMessages = '<div class="container"><div class="alert alert-success">Recored Inserted </div></div>';
                    redirecthome($TheMessages, 'profile.php?control=pers-trainees');
                }
            }else{
                $TheMessages= '<div class="container"><div class="alert alert-danager">Error Enter The replyText</div></div>';
                redirecthome($TheMessages, 'profile.php?control=pers-trainees');
            }
        }
    }
} elseif(isset($_SESSION['nutritionistID'])) {
    $control = isset($_GET['control']) ? $_GET['control'] : 'view';
    if ($control == "view") {
        $traineeID = $_GET['traineeID'];
        $stmt = $conn->prepare("SELECT * FROM trainees
                            INNER JOIN traineesbody
                            ON trainees.traineeID=traineesbody.traineeID
                            WHERE trainees.traineeID=?");
        $stmt->execute(array($traineeID));
        $row = $stmt->fetch(); ?>
        <div class="container">
            <div class="main-title-sec">
                <h2><?php echo $row['traineeName']; ?>information:</h2>
            </div>
            <div class="content" style="width: 90%;">
                <div class="content-img">
                    <img src=<?php echo $row['traineePic']; ?> alt="hello">
                </div>
                <div class="content-info">
                    <div class="label name">
                        <span>name:</span><span> <?php echo $row['traineeName']; ?></span>
                    </div>
                    <div class="label fullname">
                        <span>full name:</span><span><?php echo $row['traineeFullName']; ?></span>
                    </div>
                    <div class="label email">
                        <span>email:</span><span><?php echo $row['traineeEmail']; ?></span>
                    </div>
                    <div class="label mobile-num">
                        <span>number:</span><span><?php echo $row['traineeMobileNum']; ?></span>
                    </div>
                </div>
                <div class="content-info">
                    <div class="label name">
                        <span>weight:</span><span><?php echo $row['weight']; ?></span>
                    </div>
                    <div class="label fullname">
                        <span>height:</span><span><?php echo $row['height'] ?></span>
                    </div>
                    <div class="label email">
                        <span>age:</span><span><?php echo $row['age']; ?></span>
                    </div>
                    <div class="label mobile-num">
                        <span>health state:</span><span><?php echo $row['healthState']; ?></span>
                    </div>
                </div>
            </div>
            <a href="?control=viewMedical&traineeID=<?php echo $traineeID; ?>" class="btn btn-light">
                medical drugs
            </a>
            <?php
            $traineeName = $conn->prepare("SELECT * FROM trainees WHERE traineeID=?");
            $stmt->execute(array($traineeID));
            $row = $stmt->fetch();
            ?>
            <div class="main-title-sec">
                <h2> <?php echo $row['traineeName'] ?> programs:</h2>
                <a href="program-controls.php?control=add&traineeID=<?php echo $traineeID; ?>" class="add-btn">add new program</a>
            </div>
            <?php
            $traineeID = $_GET['traineeID'];
            $stmt = $conn->prepare("SELECT * FROM trainees 
                                            INNER JOIN nutritionistsprograms
                                            ON trainees.traineeID=nutritionistsprograms.traineeID
                                            WHERE trainees.traineeID=?");
            $stmt->execute(array($traineeID));
            $rows = $stmt->fetchAll();
            $count = 0; ?>
            <div class="table-responsive">
                <table class="bills-table text-center">
                    <thead class="">
                        <th>#</th>
                        <th>Trainee Name</th>
                        <th>program title</th>
                        <th>program date</th>
                        <th>end date</th>
                        <th>controls</th>
                    </thead>
                    <?php
                    foreach ($rows as $row) {
                        $count++;
                        echo '<tbody>
                        <tr>
                            <td>' . $count . '</td>
                            <td>' . $row['traineeName'] . '</td>
                            <td>' . $row['programTitle'] . '</td>
                            <td>' . $row['programDate'] . '</td>
                            <td>' . $row['programEndDate'] . '</td>
                            <td>
                                <a href="program-controls.php?control=view&npID=' . $row['npID'] . '" class="btn btn-primary">view</a>
                            </td>
                        </tr>
                    </tbody>';
                    } ?>
                </table>
            </div>
        </div>

    <?php
    } elseif ($control == 'viewMedical') {
        $traineeID = $_GET['traineeID'];
        $stmt = $conn->prepare("SELECT * FROM trainees
                            INNER JOIN traineesmedical
                            ON trainees.traineeID=traineesmedical.traineeID
                            WHERE trainees.traineeID=?");
        $stmt->execute(array($traineeID));
        $rows = $stmt->fetchAll();
        $count = 0; ?>
        <!--Get Trainee Name -->
        <?php
        $traineeName = $conn->prepare("SELECT * FROM trainees WHERE traineeID=?");
        $stmt->execute(array($traineeID));
        $row = $stmt->fetch();
        ?>
        <!-- End Get Trainee Name -->
        <div class="container">
            <div class="main-title-sec">
                <h2>all medical drugs the <?php echo $row['traineeName']; ?> take:</h2>
            </div>
            <div class="table-responsive">
                <table class="bills-table text-center" style="width: fit-content;">
                    <thead>
                        <th>#</th>
                        <th>drug name</th>
                        <th>drug doses</th>
                    </thead>
                    <?php
                    foreach ($rows as $row) {
                        $count++;
                        echo '<tbody>
                        <tr>
                            <td>' . $count . '</td>
                            <td>' . $row['medicalDrugName'] . '</td>
                            <td>' . $row['medicalDrugDoses'] . '</td>
                        </tr>
                    </tbody>';
                    } ?>
                </table>
            </div>
        </div>
    <?php
    }  elseif($control == 'allMessages') {
        $nutritionistID = $_SESSION['nutritionistID'];
        $stmt = $conn->prepare("SELECT * FROM nutritionists
                               
                                INNER JOIN message
                                ON nutritionists.nutritionistID=message.nutitionstsID 
                                WHERE nutritionists.nutritionistID=?");
        $stmt->execute(array($nutritionistID));
        $rows = $stmt->fetchAll();
        $count = 0;
        ?>
        <div class="container">
            <div class="main-title-sec">
                <h2>all readed messages</h2>
            </div>
            <div class="table-responsive">
                <table class="bills-table text-center">
                    <thead>
                        <th class="col-1">#</th>
                        <th class="col-6">his message</th>
                        <th class="col-2">controls</th>
                    </thead>
                    <?php
                    foreach($rows as $row) {
                        $count ++;   
                        echo'<tbody>
                            <tr>
                                <td>'.$count.'</td>
                                <td>'.$row['messageText'].'</td>
                                <td>
                                    <a href="?control=message&messageID='.$row['messageID'].'" class="btn btn-primary">reply</a>
                                    <a href="?control=delete&messageID='.$row['messageID'].'" class="btn btn-danger">delete</a>
                                </td>
                            </tr>
                        </tbody>';
                    }
                    ?>
                </table>
            </div>
        </div>
    <?php
    } elseif($control == 'message') {
        $messageID = $_GET['messageID'];
        $stmt = $conn->prepare("SELECT * FROM message
                                INNER JOIN reply
                                ON message.messageID=reply.messageID
                                WHERE message.messageID=?");
        $stmt->execute(array($messageID));
        $row = $stmt->fetch();
        if($row > 0) {
            ?>
            <div class="container">
                <div class="main-title-sec">
                    <h2>show the message and reply</h2>
                </div>
                <div class="message-con">
                    <div class="message-content">
                        <div class="message-header">
                            <h3>message sent on</h3>
                            <div class="date"><?php echo $row['messageDate'] ?></div>
                        </div>
                        <div class="mes-content">
                            <p>
                            <?php echo $row['messageText']; ?>
                            </p>
                        </div>
                    </div>
                    <div class="message-content">
                        <div class="message-header">
                            <h3>replied on</h3>
                            <div class="date"><?php echo $row['replyDate']; ?></div>
                        </div>
                        <div class="mes-content">
                            <p>
                                <?php echo $row['replyText'];?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        <?php } else { 
            $messageID = $_GET['messageID'];
            $stmt = $conn->prepare("SELECT * FROM message WHERE messageID=?");
            $stmt->execute(array($messageID));
            $row = $stmt->fetch();
            ?>
            <div class="container">
                <div class="main-title-sec">
                    <h2>show the message and reply</h2>
                </div>
                <div class="message-con">
                    <div class="message-content">
                        <div class="message-header">
                            <h3>message sent on</h3>
                            <div class="date"><?php echo $row['messageDate']; ?></div>
                        </div>
                        <div class="mes-content">
                            <p>
                            <?php echo $row['messageText']; ?>
                            </p>
                        </div>
                    </div>
                    <div class="message-content">
                        <div class="message-header">
                        <div class="date">
                        <form action="?control=insertReply" method="POST" class="form">
                            <div class="input-field">
                                <h4 class="text-center mb-2">type the reply</h4>
                                <textarea name="replyText"  cols="30" rows="5" class="input"></textarea>
                                </div>
                                <input hidden type="text" name="messageID" value="<?php echo $_GET['messageID'] ?>">
                            <div class="input-field  mt-2">
                                <input type="submit" name="Reply" class="input submit-btn">
                            </div>
                        </form>
                        </div>
                </div>
            </div>
        <?php
        }
    } elseif($control == 'insertReply'){
        if(isset($_POST['Reply'])) {
            if (!empty($_POST['replyText'])) {
                $replyText = $_POST['replyText'];
                $messageID = $_POST['messageID'];
                $stmt = $conn->prepare("INSERT INTO reply(replyText,messageID)
                                    VALUES(?,?)");
                $stmt->execute(array($replyText,$messageID));
                $row = $stmt->rowCount();
                if ($row > 0) {
                    $TheMessages = '<div class="container"><div class="alert alert-success">Recored Inserted </div></div>';
                    redirecthome($TheMessages, 'profile.php?control=pers-trainees');
                }
            }else{
                    $TheMessages= '<div class="container"><div class="alert alert-danager">Error Enter The replyText</div></div>';
                    redirecthome($TheMessages, 'profile.php?control=pers-trainees');
            }
        }
    } elseif($control == 'delete') {
        $messageID = $_GET['messageID'];
        $stmt = $conn->prepare("DELETE FROM message WHERE messageID=?");
        $stmt->execute(array($messageID));
        $row = $stmt->rowCount();
        if($row > 0) {
            $TheMessages = '<div class="container"><div class="alert alert-success">Delete Successfully</div></div>';
            redirecthome($TheMessages, 'profile.php?control=pers-trainees');

        }
    }
}   
include $tpl . "footer.inc";

?>