<?php 
    session_start();
    $TitlePage = 'Home-Page';
    include "init.php";
    ?>
    <link rel="stylesheet" href="<?php echo $css;?>home-page.css">
    <?php
    include $tpl . "navbar.inc";
    $control = isset($_GET['control']) ? $_GET['control'] : 'main';
    if ($control == 'main') {
        if (isset($_SESSION['traineeID'])) {
            $stmt = $conn->prepare("SELECT * FROM post WHERE postStatus=2");
            $stmt->execute();
            $rows = $stmt->fetchAll(); ?>
            <div class="container">
                <div class="main-title-sec">
                    <h2>training posts</h2>
                </div>
                <div class="small-posts-container">
                    <?php foreach ($rows as $row) { ?>
                        <div class="small-post-card">
                            <div class="image">
                                <img src="<?php echo $row['postPic']?>" alt="">
                                <div class="type"><?php if($row['postStatus'] == 1) { echo 'Public'; } else { echo 'Training'; } ?></div>
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
                                    <span> <i class="fas fa-comment"></i><?php 
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
                                    ?></span>
                                    <a href="posts.php?control=view&postID=<?php echo $row['postID']; ?>" class="btn btn-primary">view</a>
                                </div>
                            </div>
                        </div>
                    <?php 
                    } ?>
                </div>
            </div>

        <?php
        } elseif(!isset($_SESSION['trainerID']) 
            && !isset($_SESSION['nutritionistID']) 
            && !isset($_SESSION['traineeID'])) 
            {
                ?>
                <div class="container">
                    <div class="main-title-sec">
                        <h2>all sections</h2>
                    </div>
                    <?php
                    $stmt = $conn->prepare('SELECT * FROM section');
                    $stmt->execute();
                    $rows = $stmt->fetchAll();
                    ?>
                    <div class="hz-cards">
                        <?php
                        foreach($rows as $row) { 
                        echo'<div class="hz-card">
                            <div class="left">
                                <div class="logo">
                                    <span class="web-logo ">
                                        <i class="fas fa-dumbbell "></i>
                                    </span> 
                                    <span class="logo-title">section</span>
                                </div>
                            </div>
                            <div class="right">
                                <div class="name">'.$row['sectionName'].'</div>
                                <a href="?control=viewSection&sectionID='.$row['sectionID'].'" class="btn btn-primary">view</a>
                            </div>
                        </div>';
                        }
                        ?>
                    </div>
                    <div class="main-title-sec">
                        <h2>training posts</h2>
                    </div>
                    <?php 
                    $stmt = $conn->prepare('SELECT * FROM post WHERE postStatus=2');
                    $stmt->execute();
                    $rows = $stmt->fetchAll();
                    ?>
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
                                    <div class="ctrl">
                                        <span> <i class="fas fa-comment"></i> 25</span>
                                        <a href="posts.php?control=view&postID=<?php echo $row['postID'] ?>" class="btn btn-primary">view</a>
                                    </div>
                                </div>
                            </div>
                        <?php 
                        } ?>
                    </div>
                </div>
                <?php
            } else {
                ?>
                <div class="container">
                    <div class="main-title-sec">
                        <h2>training posts</h2>
                    </div>
                    <?php 
                    $stmt = $conn->prepare('SELECT * FROM post WHERE postStatus=2');
                    $stmt->execute();
                    $rows = $stmt->fetchAll();
                    ?>
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
                                    <div class="ctrl">
                                        <span> <i class="fas fa-comment"></i> 25</span>
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
    }elseif($control == 'viewSection') {
        $sectionID = $_GET['sectionID'];
        $stmt = $conn->prepare('SELECT * FROM trainer WHERE sectionID = ?');
        $stmt->execute(array($sectionID));
        $rows = $stmt->fetchAll();
        $stmt2 = $conn->prepare('SELECT * FROM nutritionists WHERE sectionID = ?');
        $stmt2->execute(array($sectionID));
        $rows2 = $stmt2->fetchAll();
        ?>
         <div class="container">
         <div class="main">
            <div class="maining-side">
                <div class="main-title-sec">
                    <h2>all trainers</h2>
                </div>
                <div class="hz-cards">
                    <?php foreach($rows as $row){?>
                        <div class="hz-card">
                            <div class="left">
                                <img src="<?php echo $row['trainerPic']?>" alt="">
                            </div>
                            <div class="right">
                                <div class="name">
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
        <div class="main">
            <div class="maining-side">
                <div class="main-title-sec">
                    <h2>all nutrisionists</h2>
                </div>
                <div class="hz-cards">
                    <?php foreach($rows2 as $row2){?>
                        <div class="hz-card">
                            <div class="left">
                                <img src="<?php echo $row2['nutritionistPic']?>" alt="">
                            </div>
                            <div class="right">
                                <div class="name">
                                    <?php  echo $row2['nutritionistFullName']?>
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
            
        <?php
    }   
    include $tpl . "footer.inc";
?>