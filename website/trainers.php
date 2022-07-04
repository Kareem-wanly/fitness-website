<?php 
    session_start();
    include "init.php";
    ?>
    <link rel="stylesheet" href="<?php echo $css;?>home-page.css">
    <link rel="stylesheet" href="<?php echo $css;?>user-info.css">
    <?php
    include $tpl . "navbar.inc";
    if(isset($_SESSION['traineeID'])) {
        $stmt = $conn->prepare("SELECT * FROM trainer WHERE trainerID = ?");
        $stmt->execute(array($_SESSION['TRID']));
        $row = $stmt->fetch();

        $programs = $conn->prepare("SELECT * FROM trainees 
                                    INNER JOIN trainerprograms
                                    ON trainees.traineeID=trainerprograms.traineeID
                                    WHERE trainees.traineeID=?");
        $programs->execute(array($_SESSION['traineeID']));
        $programsRows = $programs->fetchAll();
        $count = 0;


        ?>
        <div class="container">
            <div class="main-title-sec">
                <h2>my trainer</h2>
            </div>
            <div class="content">
                <div class="content-img">
                    <img src="<?php echo $row['trainerPic'] ?>" alt="hello">
                </div>
                <div class="content-info">
                    <div class="label name">
                        <span>name:</span><span> <?php echo $row['trainerName'] ?></span>
                    </div>
                    <div class="label fullname">
                        <span>full name:</span><span> <?php echo $row['trainerFullName']; ?></span>
                    </div>
                    <div class="label email">
                        <span>email:</span><span> <?php echo $row['trainerEmail']; ?></span>
                    </div>
                    <div class="label mobile-num">
                        <span>mobile number:</span><span><?php echo $row['trainerMobileNum']; ?></span>
                    </div>
                </div>
            </div>
            <div class="main-title-sec">
                <h2>my programs</h2>
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
                foreach($programsRows as $programsRow) {
                    $count ++;
                    echo'<tbody>
                        <tr>
                            <td>'.$count.'</td>
                            <td>'.$programsRow['programTitle'].'</td>
                            <td>'.$programsRow['programDate'].'</td>
                            <td>'.$programsRow['programEndDate'].'</td>
                            <td><a href="trainees-control.php?control=program&tpID='.$programsRow['tpID'].'" class="btn btn-primary">view</a></td>
                        </tr>
                    </tbody>';
                }
                ?>
            </table>
        </div>
    <?php
    } else {
        $stmt = $conn->prepare('SELECT * FROM trainer');
        $stmt->execute();
        $rows = $stmt->fetchAll();
        ?>
        <div class="container">
            <div class="main">
                <div class="maining-side">
                    <div class="main-title-sec">
                        <h2>all trainers</h2>
                    </div>
                    <div class="profile-cards-container">
                        <?php foreach($rows as $row   ){?>
                            <div class="profile-card">
                                <div class="images">
                                    <img class="card-bg" src="<?php echo $row['trainerPic']?>" alt="">
                                    <img class="card-img" src="<?php echo $row['trainerPic']?>" alt="">
                                </div>
                                <div class="content">
                                    <div class="title">
                                    <?php echo $row['trainerFullName']?>
                                    </div>
                                    <a href="personPage.php?view=trainer&trainerID=<?php echo $row['trainerID']?>" class="btn btn-primary">view</a>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }
    include $tpl . "footer.inc";
?>