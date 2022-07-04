<?php 
    session_start();
    include "init.php";
    ?>
    <link rel="stylesheet" href="<?php echo $css;?>home-page.css">
    <link rel="stylesheet" href="<?php echo $css;?>posts.css">
    <?php
    include $tpl . "navbar.inc";
    $control = isset($_GET['control']) ? $_GET['control'] : 'main';
    if($control == 'main') {
        $stmt = $conn->prepare('SELECT * FROM post');
        $stmt->execute();
        $rows = $stmt->fetchAll();
        ?>
        <div class="container">
            <div class="main-title-sec posts-haeder">
                <h2>all posts</h2>
                <div class="search-bar">
                    <form class="search">
                        <input type="text" name="search" placeholder="search for anything" class="search-feild">
                        <button type="submit" class="search-btn"><i class="fas fa-search"></i></button>
                    </form>
                </div>
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
    } elseif($control == 'view') {
        $postID = $_GET['postID'];
        $stmt = $conn->prepare("SELECT * FROM post WHERE postID=?");
        $stmt->execute(array($postID));
        $row = $stmt->fetch();
        $trainerID = $row['trainerID'];
        $NutitionstsID = $row['nutitionstsID'];
        $adminID = $row['adminID'];
        ?>
        <div class="container">
            <div class="main-title-sec">
                <h2>post content:</h2>
            </div>
            <div class="post-card">
                <div class="image">
                    <img src="<?php echo $row['postPic'] ?>" alt="">
                </div>
                <div class="content">
                    <div class="title"><span class="post-title"><?php echo $row['postTitle'] ?></span> <span class="post-type"><?php if($row['postStatus']){ echo 'Public'; } else { echo 'Training'; } ?></span></div>
                    <div class="desc">
                        <p>
                        <?php echo $row['postDecraption'] ?>
                        </p>
                    </div>
                    <div class="date">published date: <span><?php echo $row['postDate'] ?></span></div>
                </div>
            </div>
            <?php
            if (isset($_SESSION['traineeID'])) {
                ?>
                <div class="post-comments">
                    <form action="?control=insert" method="POST" class="add-comment">
                    <div class="input-field">
                        <textarea type="text" name="commentText" placeholder="type your comment here" class="input"></textarea>
                        <input type="text" name="postID" hidden value="<?php echo $_GET['postID']?>">
                        <button class="comment-btn fas fa-paper-plane" name="addComment"></button>
                    </div>
                    </form>
                    <?php 
                        $traineeID = $_SESSION['traineeID'];
                        $postID = $_GET['postID'];
                        $stmt = $conn->prepare('SELECT * FROM trainees
                                                INNER JOIN comment
                                                ON trainees.traineeID=comment.traineeID
                                                INNER JOIN post
                                                ON post.postID=comment.postID
                                                WHERE trainees.traineeID=?
                                                AND post.postID=?');
                        $stmt->execute(array($traineeID,$postID));
                        $rows = $stmt->fetchAll(); 
                    foreach ($rows as $row) {
                        echo'
                        <div class="comments-con">
                            <div class="field">
                                <div class="comment">
                                    <div class="top">
                                        <img src="'.$row['traineePic'].'"" alt="">
                                        <div class="content">
                                            <div class="name">'.$row['traineeName'].'</div>
                                            <div class="date"> '.$row['commentDate'].'</div>
                                        </div>
                                    </div>
                                    <div class="bottom">
                                        '.$row['commentText'].' 
                                    </div>
                                </div>
                            </div>
                        </div>';
                        }
            }
            ?>
                        
            </div>
        </div>
    <?php
    }elseif($control == 'insert') {
        if(isset($_POST['addComment'])) {
            if (!empty($_POST['commentText'])) {
                $traineeID = $_SESSION['traineeID'];
                $postID = $_POST['postID'];
                $commentText = $_POST['commentText'];
                $stmt = $conn->prepare('INSERT INTO comment(commentText,postID,traineeID)
                                    VALUES(?,?,?)');
                $stmt->execute(array($commentText, $postID, $traineeID));
                $row = $stmt->rowCount();
                if ($row > 0) {
                    header('location:?posts.php');
                }
            }
        }
    }
    include $tpl . "footer.inc";
?>