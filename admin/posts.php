<?php
session_start();
include "init.php";
?>
<link rel="stylesheet" href="<?php echo $css . 'posts.css'; ?>">
<link rel="stylesheet" href="<?php echo $css . 'styling-forms.css'; ?>">
<link rel="stylesheet" href="<?php echo $css . 'user-info.css'; ?>">
<?php
include $tpl . "navbar.inc";
$control = isset($_GET['control']) ? $_GET['control'] : 'manage';
if ($control == 'manage') { ?>
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
            <h2>all posts:</h2>
            <a class="add-btn" href="posts.php?control=add"><span class="add-icon"><i class="fas fa-plus"></i></span>add new post</a>

        </div>
        <div class="posts-container">
            <?php
            if (isset($_GET['search'])) {
                $searchValue = $_GET['search'];
                $stmt = $conn->prepare("SELECT * FROM post
                                            WHERE postTitle LIKE ?
                                            ORDER BY post.postDate DESC
                                            ");
                $stmt->execute(array("%$searchValue%"));
                $rows = $stmt->fetchAll();
                $count = $stmt->rowCount();
                if ($count > 0) {
                    foreach ($rows as $row) {
                        echo '
                        <div class="post-card">
                            <img class="post-image" src="' . $row['postPic'] . '" alt="">
                            <div class="post-content">
                                <div class="post-header">' . $row['postTitle'] . '</div>
                                <div class="post-info">' . $row['postDecraption'] . '</div>
                                <a href="posts.php?control=view&postID=' . $row['postID'] . '" class="post-btn">view all the content</a>
                                </div>
                        </div>';
                    }
                } else {
                    echo '<div class="panel-info">
                        <p style="color: #f00">no records was found</p>
                        </div>';
                }
            } else {
                $stmt = $conn->prepare("SELECT * FROM post
                                            ORDER BY post.postDate");
                $stmt->execute();
                $rows = $stmt->fetchAll();
                foreach ($rows as $row) {
                    echo '
                        <div class="post-card">
                            <img class="post-image" src="' . $row['postPic'] . '" alt="">
                            <div class="post-content">
                                <div class="post-header">' . $row['postTitle'] . '</div>
                                <div class="post-info">' . $row['postDecraption'] . '</div>
                                <a href="posts.php?control=view&postID=' . $row['postID'] . '" class="post-btn">view all the content</a>
                                </div>
                        </div>';
                }
            }
            ?>
        </div>
    </div>
<?php
} elseif ($control == 'add') { ?>
    <div class="container form-container">
        <div class="header-sec">
            <div class="icon"><i class="fas fa-bars"></i></div>
            <a href="logout.php" class="logut-btn">
                logout<i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
        <div class="form add-form">
            <h2 class="login">add new post</h2>
            <form action="posts.php?control=insert" method="POST" enctype="multipart/form-data">
                <div class="input-field">
                    <label for="">post title:</label>
                    <input class="input text-input" type="text" name="postTitle" autocomplete="off" placeholder="name must be higher than 6 characters">
                </div>
                <div class="input-field">
                    <label for="">post description:</label>
                    <input class="input text-input" type="text" name="postDecraption" autocomplete="off" placeholder="name must be higher than 6 characters">
                </div>
                <!-- <div class="input-field">
                    <label for="">post owner:</label>
                    <input class="input text-input" type="text" name="trainerEmail" autocomplete="off" placeholder="name must be higher than 6 characters">
                </div>   -->
                <!-- <div class="name-error-message">
                </div> -->
                <div class="input-field">
                    <label for="">post picture / video:</label>
                    <input class="fileInput" type="file" name="files[]" >
                    <!-- <ion-icon class="eye" name="eye-outline"></ion-icon>
                    <ion-icon class="close-eye eye active" name="eye-off-outline"></ion-icon> -->
                </div>
                <div class="input-field">
                        <label for="">postStatus:</label>
                        <select class="select-input" name="postStatus">
                            <option value="1">Public Posts</option>
                            <option value="2">Training Posts</option>
                        </select>
                </div>

                <div class="input-field">
                    <input class="submit-btn" type="submit" name="add-post">
                </div>
            </form>
        </div>
    </div>
<?php
} elseif ($control == 'insert') {
    if (isset($_POST['add-post'])) {
        $postTitle = $_POST['postTitle'];
        $postDecraption = $_POST['postDecraption'];
        $postStatus = $_POST['postStatus'];
        $adminID = $_SESSION['ID'];
        $countFile = count($_FILES['files']['name']);
        $query = "INSERT INTO post(postTitle,postDecraption,postPic,postStatus,adminID) VALUES(?,?,?,?,?)";
        $stmt = $conn->prepare($query);
        for ($i = 0; $i < $countFile; $i++) {
            $fileName = $_FILES['files']['name'][$i];
            $targetFile = "../images/imagesPost/" . $fileName;
            $fileExtension = pathinfo($targetFile, PATHINFO_EXTENSION);
            $fileExtension = strtolower($fileExtension);
            $fileType = array("jpg", "jpeg", "png");
            if (in_array($fileExtension, $fileType)) {
                if (move_uploaded_file($_FILES['files']['tmp_name'][$i], $targetFile));
                $stmt->execute(array($postTitle, $postDecraption, $targetFile,$postStatus, $adminID));
                $theMessage = '<div class="container"><div class="alert alert-success"> the Posts has been inserted successfully</div></div>';
                redirecthome($theMessage, '?control=manage', "3");
            }
        }
    }
} elseif ($control == 'view') {
    $postID = $_GET['postID'];
    $stmt = $conn->prepare("SELECT * FROM post WHERE postID=?");
    $stmt->execute(array($postID));
    $row = $stmt->fetch();
    $trainerID = $row['trainerID'];
    $NutitionstsID = $row['nutitionstsID'];
    $adminID = $row['adminID'];
?>

    <div class="container">
        <div class="header-sec">
            <div class="icon"><i class="fas fa-bars"></i></div>
            <a href="logout.php" class="logut-btn">
                logout<i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
        <div class="main-title-sec">
            <h2>all post content:</h2>
        </div>
        <div class="content">
            <div class="content-img">
                <img src="<?php echo $row['postPic']; ?>" alt="hello">
            </div>
            <div class="content-info">
                <div class="label name">
                    <span>Post Title:</span><span><?php echo $row['postTitle']; ?></span>
                </div>
                <div class="label fullname">
                    <span>Post Decraption:</span><span><?php echo $row['postDecraption'] ?></span>
                </div>
                <div class="label email">
                    <span>Post Date:</span><span><?php echo $row['postDate']; ?></span>
                </div>
                <div class="label mobile-num">
                    <span>Post Owner:</span><span>
                        <?php if ($trainerID == null && $NutitionstsID == null) {
                            $stmt = $conn->prepare("SELECT * FROM admins WHERE adminID=?");
                            $stmt->execute(array($adminID));
                            $rows = $stmt->fetchAll();
                            foreach ($rows as $row) {
                                echo $row['adminName'];
                            }
                        } elseif ($trainerID == null && $adminID == null) {
                            $stmt = $conn->prepare("SELECT * FROM nutritionists WHERE nutritionistID=?");
                            $stmt->execute(array($NutitionstsID));
                            $rows = $stmt->fetchAll();
                            foreach ($rows as $row) {
                                echo $row['nutritionistName'];
                            }
                        } elseif ($NutitionstsID == null && $adminID == null) {
                            $stmt = $conn->prepare("SELECT * FROM trainer WHERE trainerID=?");
                            $stmt->execute(array($trainerID));
                            $rows = $stmt->fetchAll();
                            foreach ($rows as $row) {
                                echo $row['trainerName'];
                            }
                        } ?>
                    </span>
                </div>
            </div>
        </div>
        <div class="control-btn">
            <a href="?control=delete&postID=<?php echo $postID; ?>">delete</a>
            <?php
            if (!empty($adminID)) {
                echo '<a href="?control=edit&postID=' . $postID . '">edit</a>';
            }
            ?>
        </div>
    </div>
<?php
} elseif ($control == 'delete') {
    $postID = $_GET['postID'];
    $stmt = $conn->prepare("SELECT * FROM post WHERE postID=?");
    $stmt->execute(array($postID));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    extract($row);
    $postPic = $row['postPic'];
    if (file_exists($postPic)) {
        $picState = unlink($postPic);
    }
    $delete = $conn->prepare("DELETE FROM post WHERE postID=?");
    $delete->execute(array($postID));
    $TheMessages = '<div class="container"><div class="alert alert-success">Delete Successfully</div></div>';
    redirecthome($TheMessages, 'posts.php');
} elseif ($control == 'edit') {
    $postID = $_GET['postID'];
    $stmt = $conn->prepare("SELECT * FROM post WHERE postID=?");
    $stmt->execute(array($postID));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    extract($row);

?>
    <div class="container form-container">
        <div class="header-sec">
            <div class="icon"><i class="fas fa-bars"></i></div>
        </div>
        <div class="form add-form">
            <h2 class="form-title">edit post</h2>
            <form method="POST" action="posts.php?control=update" enctype="multipart/form-data">
                <div class="input-field">
                    <label for="">post title:</label>
                    <input class="input text-input" type="text" value="<?php echo $row['postTitle']; ?>" name="postTitle" autocomplete="off" placeholder="name must be higher than 6 characters">
                </div>
                <div class="input-field">
                    <label for="">post description:</label>
                    <input class="input text-input" type="text" value="<?php echo $row['postDecraption']; ?>" name="postDecraption" autocomplete="off" placeholder="name must be higher than 6 characters">
                </div>
                <input type="text" hidden name="postID" value="<?php echo $row['postID']; ?>">
                <input type="text" hidden name="postPic" value="<?php echo $row['postPic']; ?>">
                <!-- <div class="input-field">
                    <label for="">post owner:</label>
                    <input class="input text-input" type="text" name="trainerEmail" autocomplete="off" placeholder="name must be higher than 6 characters">
                </div>   -->
                <!-- <div class="name-error-message">
                </div> -->
                <div class="input-field">
                    <label for="">post picture / video:</label>
                    <input class="fileInput" type="file" name="files[]" >
                    <!-- <ion-icon class="eye" name="eye-outline"></ion-icon>
                    <ion-icon class="close-eye eye active" name="eye-off-outline"></ion-icon> -->
                </div>

                <div class="input-field">
                        <label for="">postStatus:</label>
                        <select class="select-input" name="postStatus">
                            <option value="1">Public Posts</option>
                            <option value="2">Training Posts</option>
                        </select>
                </div>

                <div class="input-field">
                    <input class="submit-btn" type="submit" name="edit-post">
                </div>
            </form>
        </div>
    </div>
<?php
} elseif ($control == 'update') {
    if (isset($_POST['edit-post'])) {
        $countFile = count($_FILES['files']['name']);
                for ($i = 0 ; $i < $countFile ; $i++) {
                    $image = $_FILES['files']['name'][$i];
                    if ($image == null) {
                        $postID = $_POST['postID'];
                        $postTitle = $_POST['postTitle'];
                        $postDecraption = $_POST['postDecraption'];
                        $postStatus = $_POST['postStatus'];
                        $postPic = $_POST['postPic'];
                        $stmt = $conn->prepare( "UPDATE post SET postTitle=?,
                                                    postDecraption=?,
                                                    postPic=?,
                                                    postStatus=?
                                                    WHERE postID=?");
                        $stmt->execute(array($postTitle,$postDecraption,$postPic,$postStatus,$postID));
                        $row = $stmt->rowCount();
                        if($row > 0) {
                            $theMessage = '<div class="container"><div class="alert alert-success"> the Post has been Update successfully</div></div>';
                            redirecthome($theMessage, 'posts.php');
                        }
                    } else {
                        $postID = $_POST['postID'];
                        $postTitle = $_POST['postTitle'];
                        $postDecraption = $_POST['postDecraption'];
                        $postStatus = $_POST['postStatus'];
                        $postPic = $_POST['postPic'];
                        if(file_exists($postPic)) {
                            $imageStatus = unlink($postPic);
                        }
                        $countFile = count($_FILES['files']['name']);
                        $query = "UPDATE post SET postTitle=?,
                                                    postDecraption=?,
                                                    postPic=?,
                                                    postStatus=?
                                                WHERE postID=?";
                        $stmt = $conn->prepare($query);
                        for($i = 0 ; $i < $countFile ; $i++) {
                            $image = $_FILES['files']['name'][$i];
                            $targetFile = "../images/imagesPost/" . $image;
                            $fileEX = pathinfo($targetFile,PATHINFO_EXTENSION);
                            $fileEX = strtolower($fileEX);
                            $validExtension = array('png','jpg','jpeg');
                            if(in_array($fileEX,$validExtension)) {
                                if(move_uploaded_file($_FILES['files']['tmp_name'][$i],$targetFile)) {
                                    $stmt->execute(array($postTitle,$postDecraption,$postPic,$postStatus,$postID));
                                    $theMessage = '<div class="container"><div class="alert alert-success"> the Post has been Update successfully</div></div>';
                                    redirecthome($theMessage, 'posts.php');
                                }
                            }
                        }
                    }
                }
            }
    }

?>
<script src="<?php echo $js . 'main.js'; ?>"></script>
<script src="<?php echo $js . 'navbar.js'; ?>"></script>
<?php
include $tpl . "footer.inc";
?>