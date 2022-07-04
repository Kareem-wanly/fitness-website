<?php

use LDAP\Result;

    session_start();
    include "init.php";
    ?>
    <link rel="stylesheet" href="<?php echo $css;?>home-page.css">
    <link rel="stylesheet" href="<?php echo $css;?>posts.css">
    <link rel="stylesheet" href="<?php echo $css;?>user-info.css">
    <?php
    include $tpl . "navbar.inc";
    $view = isset($_GET['view']) ? $_GET['view'] : 'Manage';
    if ($view == 'trainer') {
        ?>
        <div class="container">
            <div class="main-title-sec">
                <h2>trainer information:</h2>
                <!-- <h2>nutritionist information:</h2> -->
            </div>
            <?php
                $trainerID = $_GET['trainerID'];
                $stmt = $conn->prepare('SELECT * FROM trainer WHERE trainerID=?');
                $stmt->execute(array($trainerID));
                $row = $stmt->fetch(); ?>
            <div class="content">
                <div class="content-img">
                    <img src="<?php echo $row['trainerPic'] ?>" alt="hello">
                </div>
                <div class="content-info">
                    <div class="label name">
                        <span>trainer Name:</span><span><?php echo $row['trainerName']; ?></span>
                    </div>
                    <div class="label fullname">
                        <span>trainer FullName:</span><span><?php echo $row['trainerFullName'] ?></span>
                    </div>
                    <div class="label email">
                        <span>trainer Email:</span><span><?php echo $row['trainerEmail'] ?></span>
                    </div> 
                    <div class="label MobileNum">
                        <span>trainer MobileNumber:</span><span><?php echo $row['trainerMobileNum'] ?></span>
                    </div>
                </div>
            </div>
            <?php
                    $trainerID = $_GET['trainerID'];
                $stmt = $conn->prepare('SELECT * FROM trainer
                                            INNER JOIN post 
                                            ON trainer.trainerID=post.trainerID
                                            WHERE trainer.trainerID=?');
                $stmt->execute(array($trainerID));
                $rows = $stmt->fetchAll(); ?>
            <div class="main-title-sec">
                <h2>related posts</h2>
            </div>
            <div class="small-posts-container">
                <?php foreach ($rows as $row) { ?>
                    <div class="small-post-card">
                        <div class="image">
                            <img src="<?php echo $row['postPic']?>" alt="">
                            <div class="type">public</div>
                        </div>
                        <div class="content">
                            <div class="title"><?php echo $row['postTitle'] ?></div>
                            <div class="desc">
                                <?php
                                    $desc = $row['postDecraption'];
                                    if(strlen($desc) > 30) {
                                        $desc2 = substr($desc, 0, 60). " ...";
                                        echo $desc2;
                                    }
                                ?>
                            </div>
                            <div class="desc" hidden><?php echo $row['postID'] ?></div>
                            <div class="ctrl">
                                <span> <i class="fas fa-comment"></i>
                                    <?php
                                        $postID = $row['postID'];
                                        $stmt = $conn->prepare('SELECT * FROM post
                                                                INNER JOIN comment
                                                                ON post.postID=comment.postID
                                                                WHERE post.postID=?');
                                        $stmt->execute(array($postID));
                                        $row = $stmt->rowCount();
                                        if($row > 0) {
                                            $count = counts('commentID','comment','postID',$postID);
                                            echo $count;
                                        }
                                    ?>
                                </span>
                                <a href="posts.php?control=view&postID=<?php echo $row['postID'] ?>" class="btn btn-primary">view</a>
                            </div>
                        </div>
                    </div>
                <?php 
                } ?>
            </div>
        </div>
    <?php
    }elseif($view == 'nutritionists') {
        ?>
        <div class="container">
        <div class="main-title-sec">
            <h2>nutritionists information:</h2>
            <!-- <h2>nutritionist information:</h2> -->
        </div>
        <?php
            $nutritionistID = $_GET['nutritionistID'];
            $stmt = $conn->prepare('SELECT * FROM nutritionists WHERE nutritionistID=?');
            $stmt->execute(array($nutritionistID));
            $row = $stmt->fetch(); 
            ?>
        <div class="content">
            <div class="content-img">
                <img src="<?php echo $row['nutritionistPic'] ?>" alt="hello">
            </div>
            <div class="content-info">
                <div class="label name">
                    <span>nutritionis tName:</span><span><?php echo $row['nutritionistName']; ?></span>
                </div>
                <div class="label fullname">
                    <span>nutritionist FullName:</span><span><?php echo $row['nutritionistFullName'] ?></span>
                </div>
                <div class="label email">
                    <span>nutritionist Email:</span><span><?php echo $row['nutritionistEmail'] ?></span>
                </div> 
                <div class="label MobileNum">
                    <span>nutritionist MobileNumber:</span><span><?php echo $row['nutritionistMobileNum'] ?></span>
                </div>
            </div>
        </div>
        <?php
             $nutritionistID = $_GET['nutritionistID'];
            $stmt = $conn->prepare('SELECT * FROM nutritionists
                                        INNER JOIN post 
                                        ON nutritionists.nutritionistID=post.nutitionstsID 
                                        WHERE nutritionists.nutritionistID=?');
            $stmt->execute(array($nutritionistID));
            $rows = $stmt->fetchAll(); ?>
        <div class="main-title-sec">
            <h2>related posts</h2>
        </div>
        <div class="small-posts-container">
            <?php foreach ($rows as $row) { ?>
                <div class="small-post-card">
                    <div class="image">
                        <img src="<?php echo $row['postPic']?>" alt="">
                        <div class="type">public</div>
                    </div>
                    <div class="content">
                        <div class="title"><?php echo $row['postTitle'] ?></div>
                        <div class="desc">
                            <?php
                                $desc = $row['postDecraption'];
                                if(strlen($desc) > 30) {
                                    $desc2 = substr($desc, 0, 60). " ...";
                                    echo $desc2;
                                }
                            ?>
                        </div>
                        <div class="desc" hidden><?php echo $row['postID'] ?></div>
                        <div class="ctrl">
                            <span> <i class="fas fa-comment"></i>
                                <?php
                                $postID = $row['postID'];
                                $stmt = $conn->prepare('SELECT * FROM post 
                                INNER JOIN comment 
                                ON post.postID=comment.postID
                                WHERE post.postID=?');
                                $stmt->execute(array($postID));
                                $rows = $stmt->rowCount();
                                if($rows > 0) {
                                    $count = counts('commentID','comment','postID',$postID);
                                    echo $count;
                                }
                            ?>
                            </span>
                            <a href="posts.php?control=view&postID=<?php echo $row['postID'] ?>" class="btn btn-primary">view</a>
                        </div>
                    </div>
                </div>
            <?php 
            } ?>
        </div>
    </div>
    <?php
    }
include $tpl . "footer.inc";
?>