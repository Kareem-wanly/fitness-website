<?php 
    session_start();
    include "init.php";
    ?>
    <link rel="stylesheet" href="<?php echo $css;?>user-info.css">
    <link rel="stylesheet" href="<?php echo $css;?>forms.css">
    <link rel="stylesheet" href="<?php echo $css;?>posts.css">
    <?php
    include $tpl . "navbar.inc";
    $control = isset($_GET['control']) ? $_GET['control'] : 'view';
    if($control == 'view') {
        
    } elseif($control == 'viewMedical') { 
        $traineeID = $_SESSION['traineeID'];
        $stmt = $conn->prepare("SELECT * FROM trainees 
                                INNER JOIN traineesmedical
                                ON trainees.traineeID=traineesmedical.traineeID
                                WHERE trainees.traineeID=?");
        $stmt->execute(array($traineeID));
        $rows = $stmt->fetchAll();
        $count = 0 ;
        ?>
        <div class="container">
            <div class="main-title-sec">
                <h2>my drugs:</h2>
                <a href="?control=addDrug" class="add-btn">add new drug</a>
            </div>
            <div class="table-responsive">
                <table class="bills-table text-center" style="width: fit-content;">
                    <thead>
                        <th>#</th>
                        <th>Drug Name</th>
                        <th>Drug Doses</th>
                    </thead>
                    <?php
                    foreach($rows as $row ) {
                        $count ++;
                    echo'<tbody>
                        <tr>
                            <td>'.$count.'</td>
                            <td>'.$row['medicalDrugName'].'</td>
                            <td>'.$row['medicalDrugDoses'].'</td>
                        </tr>
                    </tbody>';
                    }
                    ?>
                </table>
            </div>
        </div>
    <?php
    } elseif($control == 'addDrug') { ?>
        <div class="container">
            <form action="?control=insertDrug" method="POST" class="form">
                <div class="input-field">
                    <label for="" class="label">drug name:</label>
                    <input type="text" name="medicalDrugName" class="input">
                </div>
                <div class="input-field">
                    <label for="" class="label">drug doses: </label>
                    <input type="number" name="medicalDrugDoses" class="input">
                </div>
                <div class="submit-field">
                    <input type="submit" name="addDrug" value="add drug" class="input submit-btn">
                </div>
            </form>
        </div>
    <?php
    } elseif($control == 'insertDrug') {
        if(isset($_POST['addDrug'])) {
            $medicalDrugName = $_POST['medicalDrugName'];
            $medicalDrugDoses = $_POST['medicalDrugDoses'];
            $traineeID = $_SESSION['traineeID'];
            $stmt = $conn->prepare("INSERT INTO traineesmedical(medicalDrugName,medicalDrugDoses,traineeID)
                                    VALUES(?,?,?)");
            $stmt->execute(array($medicalDrugName,$medicalDrugDoses,$traineeID));
            $row = $stmt->rowCount();
            if($row > 0) {
                $TheMessages = '<div class="container"><div class="alert alert-success">Recored Inserted </div></div>';
                redirecthome($TheMessages, 'trainees-control.php?control=viewMedical');
            }
        }
    } elseif($control == 'allPrograms') {
        $traineeID = $_SESSION['traineeID'];
        $stmt = $conn->prepare("SELECT * FROM trainees 
                                INNER JOIN trainerprograms
                                ON trainees.traineeID=trainerprograms.traineeID
                                WHERE trainees.traineeID=?");
        $stmt->execute(array($traineeID));
        $rows = $stmt->fetchAll();
        $count = 0;
        ?>
        <div class="container">
            <div class="main-title-sec">
                <h2>all trainer programs: </h2>
                <a href="?control=messages&traineeID=<?php echo $_SESSION['traineeID'] ?>" class="add-btn">send message</a>
            </div>
            <table class="bills-table text-center">
                <thead>
                    <th>#</th>
                    <th>program title</th>
                    <th>program date</th>
                    <th>program end date</th>
                    <th>controls</th>
                </thead>
                <?php
                foreach($rows as $row) {
                    $count ++;
                echo'<tbody>
                    <tr>
                        <td>'.$count.'</td>
                        <td>'.$row['programTitle'].'</td>
                        <td>'.$row['programDate'].'</td>
                        <td>'.$row['programEndDate'].'</td>
                        <td><a href="?control=program&tpID='.$row['tpID'].'" class="btn btn-primary">view</a></td>
                    </tr>
                </tbody>';
                }
                ?>
            </table>
        </div>
    <?php
    } elseif($control == 'program') {
        $tpID = $_GET['tpID'];
        $stmt = $conn->prepare("SELECT * FROM trainerprograms WHERE tpID=?");
        $stmt->execute(array($tpID));
        $row = $stmt->fetch();
        ?>
        <div class="container">
            <div class="main-title-sec">
                <h2><?php echo $row['programTitle'] ?> program info:</h2>
            </div>
            <div class="content">
                <div class="content-info">
                    <div class="label">
                        <span>program title:</span><span><?php echo $row['programTitle']; ?></span>
                    </div>
                    <div class="label">
                        <span>program made date:</span><span><?php echo $row['programDate']; ?></span>
                    </div>
                    <div class="label">
                        <span>program end date:</span><span><?php echo $row['programEndDate'] ?></span>
                    </div>
                    <div class="label">
                        <span>program maker:</span><span>
                            <?php
                            $traineeID = $_SESSION['traineeID'];
                            $stmt = $conn->prepare("SELECT * FROM trainees
                                                    INNER JOIN trainerprograms
                                                    ON trainees.traineeID = trainerprograms.traineeID
                                                    WHERE trainees.traineeID=?");
                            $stmt->execute(array($traineeID));
                            $row = $stmt->fetch();
                            echo $row['traineeName'];
                            ?>
                        </span>
                    </div>
                </div>
            </div>
            <div class="main-title-sec">
                
                <h2> program exercises:</h2>
                <a href="trainerPDF.php?tpID=<?php echo $_GET['tpID']; ?>" class="add-btn">get the PDF</a>
            </div>
            <?php
            $tpID = $_GET['tpID'];
                        $stmt = $conn->prepare("SELECT * FROM trainerprograms
                                    INNER JOIN programdetails
                                    ON trainerprograms.tpID=programdetails.tpID
                                    INNER JOIN weekdays
                                    ON weekdays.dayID=programdetails.dayID 
                                    WHERE trainerprograms.tpID=?");
                        $stmt->execute(array($tpID));
                        $rows = $stmt->fetchAll();
                        $count = 0 ; ?>
            <div class="table-responsive">
                <table class="bills-table text-center">
                    <thead class="">
                        <th class="col-1">#</th>
                        <th class="col-2">day</th>
                        <th class="col-2">muscle</th>
                        <th class="col-6">exercise</th>
                    </thead>
                <?php
                foreach ($rows as $row) {
                    $count ++;
                    echo'<tbody>
                        <tr>
                            <td>'.$count.'</td>
                            <td>'.$row['day'].'</td>
                            <td>'.$row['muscle'].'</td>
                            <td>'.$row['exercise'].'</td>
                        </tr>
                    </tbody>';
                } ?>
                </table>
            </div>
        </div>
    <?php
    
} elseif($control == 'messages') { 
        $trainerID = $_SESSION['TRID'];
        $traineeID = $_SESSION['traineeID'];
        $stmt = $conn->prepare("SELECT * FROM trainer
                                INNER JOIN  trainees
                                ON trainer.trainerID=trainees.trainerID
                                INNER JOIN message
                                ON trainer.trainerID=message.trainerID
                                WHERE trainer.trainerID=?
                                AND trainees.traineeID=?");
        $stmt->execute(array($trainerID,$traineeID));
        $rows = $stmt->fetchAll();
        $count = 0;
        ?>
     
        <div class="container">
            <div class="main-title-sec">
                <h2>send new message:</h2>
            </div>
            <form action="?control=insertMessage" method="POST">
                <div class="input-field">
                    <label for="">write your message:</label>
                    <textarea name="messageText" class="input" rows="5"></textarea>
                </div>
               
                <div class="submit-field">
                    <input type="submit" name="addMessage" class="input submit-btn">
                </div>
            </form>
            <div class="main-title-sec">
                <h2>all messages:</h2>
            </div>
            <div class="table-respnsive">
                <table class="bills-table text-center">
                    <thead>
                        <th class="col-1">#</th>
                        <th class="col-2">messageText</th>
                        <th class="col-2">messageDate</th>
                        <th class="col-2">Trainer Name</th>
                        <th class="col-2">control</th>
                    </thead>
                    <?php
                    foreach($rows as $row) {
                        $count ++;
                        echo'<tbody>
                        <tr>
                            <td>'.$count.'</td>
                            <td>'.$row['messageText'].'</td>
                            <td>'.$row['messageDate'].'</td>
                            <td>'.$row['trainerName'].'</td>
                            
                            <td><a href="?control=showMessage&messageID='.$row['messageID'].'" class="btn btn-primary">show</a></td>
                        </tr>
                    </tbody>';
                    }
                    ?>
                </table>
            </div>
        </div>
    <?php
    }elseif($control == 'insertMessage') {
        if(isset($_POST['addMessage'])) {
            if (!empty($_POST['messageText'])) {
                $messageText = $_POST['messageText'];
                $traineeID = $_SESSION['traineeID'];
                $trainerID = $_SESSION['TRID'];
                $stmt = $conn->prepare("INSERT INTO message(messageText,traineeID,trainerID)
                                    VALUES(?,?,?)");
                $stmt->execute(array($messageText,$traineeID,$trainerID));
                $row = $stmt->rowCount();
                if ($row > 0) {
                    $TheMessages = '<div class="container"><div class="alert alert-success">Recored Inserted </div></div>';
                    redirecthome($TheMessages, 'trainees-control.php?control=messages');
                }
            } else {
                $TheMessages = '<div class="container"><div class="alert alert-success">Recored Inserted </div></div>';
                    redirecthome($TheMessages, 'trainees-control.php?control=messages');
            }
        }
    } elseif($control == 'showMessage') {
        $messageID = $_GET['messageID'];
        $message = $conn->prepare("SELECT * FROM message WHERE messageID=?");
        $message->execute(array($messageID));
        $Messages = $message->fetch();
        ?>
        <?php
        $stmt = $conn->prepare("SELECT * FROM message
                                INNER JOIN reply 
                                ON message.messageID=reply.messageID 
                                WHERE message.messageID=?");
        $stmt->execute(array($messageID));
        $row = $stmt->fetch();
        print_r($row);
            ?>
        <div class="container">
            <div class="main-title-sec">
                <h2>show the message and reply</h2>
            </div>
            <div class="message-con">
                <div class="message-content">
                    <div class="message-header">
                        <h3>message sent on</h3>

                        <div class="date"><?php echo $Messages['messageDate'];?></div>
                    </div>
                    <div class="mes-content">
                        <p>
                            <?php echo $Messages['messageText'] ?>
                        </p>
                    </div>
                </div>
                <div class="message-content">
                    <div class="message-header">
                        <h3>replied on</h3>
                        <div class="date">
                            <?php
                            if(!empty($row['replyDate'])) {
                                echo $row['replyDate'];
                            }
                            ?>
                        </div>
                    </div>
                    <div class="mes-content">
                        <p>
                            <?php 
                                if(!empty($row['replyText'])) {
                                    echo $row['replyText'];
                                } else {
                                    echo 'No Replied';
                                }
                            ?>
                        </p>
                        
                    </div>
                </div>
            </div>
        </div>
 <?php
}
    include $tpl . "footer.inc";
?>  