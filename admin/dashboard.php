<?php
    session_start();

    $TitlePage = 'Dashboard ';
    
    include "init.php";

?>
<link rel="stylesheet" href="<?php echo $css?>dashboard.css">
<?php 
    
    include $tpl . "navbar.inc";

?>
<?php

if(isset($_SESSION['adminName'])) {

$list =  5;

$getlist1 = getlist("*","trainees","traineeID",$list);

$getlist2 = getlist("*","trainer","trainerID",$list);

$getlist3 = getlist("*","nutritionists","nutritionistID",$list);

?>

<div class="container">
    <div class="header-sec">
        <div class="icon"><i class="fas fa-bars"></i></div>
        <a href="logout.php" class="logut-btn">
            logout<i class="fas fa-sign-out-alt"></i>
        </a>
    </div>
    <div class="main-title-sec">
        <h2>quick overview:</h2>
    </div>
    <div class="card-container">
        <a href="trainees.php?control=manage" class="card">
            <span><i class="card-icon fas fa-running"></i></span>
            <span class="name">all trainees</span>
            <span class="num">
                <?php
                    $count = counts("traineeID","trainees", "groupID", 1);
                    echo $count;
                ?>
            </span>
        </a>
        <a href="trainers.php?control=Manage" class="card">
            <span><i class="card-icon fas fa-user"></i></span>
            <span class="name">all trainers</span>
            <span class="num">
            <?php
                $countTrainer = counts("trainerID","trainer");
                echo $countTrainer;
            ?>
            </span>
        </a>
        <a href="#" class="card">
            <span><i class="card-icon fas fa-user-md"></i></span>

            <span class="name">all nutritionist</span>
            <span class="num">
                <?php
                    $countnutritionists = counts("nutritionistID","nutritionists");
                    echo $countnutritionists;
                ?>
            </span>
        </a>
        <a href="#" class="card">
            <span><i class="card-icon fas fa-clone"></i></span>
            <span class="name">all registered bills</span>
            <span class="num">1500</span>
        </a>
    </div>
    <div class="main-title-sec">
        <h2>latest members joined:</h2>
    </div>
    <div class="panel-container">
        <div class="panel">
            <h3 class="trainees-panel-btn">trainees registered:</h3>
            <div class="panel-content active">
            <?php
                foreach($getlist1 as $list){
                echo '
                <div class="panel-info">
                    <p>';
                    echo $list['traineeName'];
                    echo '
                    </p>
                    <a class="panel-main-btn" href="#">view</a>
                </div>';
                }
            ?>
            </div>
        </div>
        <div class="panel">
            <h3 class="trainer-panel-btn">all trainers:</h3>
            <div class="panel-content active">
            <?php
                foreach($getlist2 as $list){
                echo '
                <div class="panel-info">
                    <p>';
                    echo $list['trainerName'];
                    echo '
                    </p>
                    <a class="panel-main-btn" href="#">view</a>
                </div>';
                }
            ?>
            </div>
        </div>
        <div class="panel">
            <h3 class="nutrintionist-panel-btn">all nutrintionist:</h3>
            <div class="panel-content active">
            <?php
                foreach($getlist3 as $list){
                echo '
                <div class="panel-info">
                    <p>';
                    echo $list['nutritionistName'];
                    echo '
                    </p>
                    <a class="panel-main-btn" href="#">view</a>
                </div>';
                }
            ?>
            </div>
        </div>
    </div>
</div>
</div>

<script src="<?php echo $js;?>navbar.js"></script>
<script src="<?php echo $js;?>main.js"></script>

<?php

    include $tpl . "footer.inc";
} else {

}

?>