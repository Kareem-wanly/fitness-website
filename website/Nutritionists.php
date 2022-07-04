<?php 
    session_start();
    include "init.php";
    ?>
    <link rel="stylesheet" href="<?php echo $css;?>home-page.css">
    <link rel="stylesheet" href="<?php echo $css;?>user-info.css">
    <?php
    include $tpl . "navbar.inc";
    if(isset($_SESSION['traineeID'])) {
        $stmt = $conn->prepare("SELECT * FROM nutritionists WHERE nutritionistID = ?");
        $stmt->execute(array($_SESSION['NUID']));
        $row = $stmt->fetch();

        $programs = $conn->prepare("SELECT * FROM trainees 
                                    INNER JOIN nutritionistsprograms
                                    ON trainees.traineeID=nutritionistsprograms.traineeID
                                    WHERE trainees.traineeID=?");
        $programs->execute(array($_SESSION['traineeID']));
        $programsRows = $programs->fetchAll();
        $count = 0;

        ?>
        <div class="container">
            <div class="main-title-sec">
                <h2>my nuts</h2>
            </div>
            <div class="content">
                <div class="content-img">
                    <img src="<?php echo $row['nutritionistPic'] ?>" alt="hello">
                </div>
                <div class="content-info">
                    <div class="label name">
                        <span>name:</span><span> <?php echo $row['nutritionistName'] ?></span>
                    </div>
                    <div class="label fullname">
                        <span>full name:</span><span> <?php echo $row['nutritionistFullName']; ?></span>
                    </div>
                    <div class="label email">
                        <span>email:</span><span> <?php echo $row['nutritionistEmail']; ?></span>
                    </div>
                    <div class="label mobile-num">
                        <span>mobile number:</span><span><?php echo $row['nutritionistMobileNum']; ?></span>
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
                        <td><a href="traineesN-control.php?control=program&npID='.$programsRow['npID'].'" class="btn btn-primary">view</a></td>
                    </tr>
                </tbody>';
                }
                ?>
            </table>
        </div>
        
        <?php
    } else {
        $stmt = $conn->prepare('SELECT * FROM nutritionists');
        $stmt->execute();
        $rows = $stmt->fetchAll();
        ?>
        <div class="container">
            <div class="main">
                <div class="maining-side">
                    <div class="main-title-sec">
                        <h2>all nutritionists</h2>
                    </div>
                    <div class="profile-cards-container">
                        <?php foreach($rows as $row){?>
                            <div class="profile-card">
                                <div class="images">
                                    <img class="card-bg" src="<?php echo $row['nutritionistPic']?>" alt="">
                                    <img class="card-img" src="<?php echo $row['nutritionistPic']?>" alt="">
                                </div>
                                <div class="content">
                                    <div class="title">
                                    <?php echo $row['nutritionistFullName']?>
                                    </div>
                                    <a href="personPage.php?view=nutritionists&nutritionistID=<?php echo $row['nutritionistID']?>" class="btn btn-primary">view</a>
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