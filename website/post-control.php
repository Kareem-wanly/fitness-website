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
    if($control == "view") {
        $postID = $_GET['postID'];
        $stmt = $conn->prepare("SELECT * FROM post WHERE postID=?");
        $stmt->execute(array($postID));
        $row = $stmt->fetch();
        $trainer = $row['trainerID'];
        ?>
        <div class="container">
            <div class="main-title-sec">
                <h2>all post content:</h2>
            </div>
            <div class="content">
                <div class="content-img">
                    <img src="<?php echo $row['postPic'] ?>" alt="hello">
                </div>
                <div class="content-info">
                    <div class="label name">
                    <span>Post Title:</span><?php echo $row['postTitle'] ?><span></span>
                    </div>
                    <div class="label fullname">
                    <span>Post Decraption:</span><span><?php echo $row['postDecraption'] ?></span>
                    </div>
                    <div class="label email">
                    <span>Post Date:</span><span><?php echo $row['postDate']; ?></span>
                    </div>
                    <div class="label mobile-num">
                    <span>Post Owner:</span>
                    <span>
                        <?php
                        if ($trainer != '') {
                            $stmt = $conn->prepare("SELECT * FROM trainer WHERE trainerID=?");
                            $stmt->execute(array($trainer));
                            $rows = $stmt->fetchAll();
                            foreach ($rows as $row) {
                                echo $row['trainerName'];
                            }
                        }
                        ?>
                    </span>
                    </div>
                </div>
            </div>
            <div class="control-btn">
                <a href="?control=delete&postID=<?php echo $postID; ?>">delete</a>
                <a href="?control=edit&postID=<?php echo $postID; ?>">edit</a>
            </div>
        </div>

    <?php
    } elseif($control == "edit") { 
        $postID = $_GET['postID'];
        $stmt = $conn->prepare("SELECT * FROM post WHERE postID=?");
        $stmt->execute(array($postID));
        $row = $stmt->fetch();
        ?>
        <div class="container">
            <div class="main-title-sec">
                <h2>edit post:</h2>
            </div>
            <form action="?control=update" method="POST"  enctype="multipart/form-data" class="form profile-form">
                <div class="input-field">
                    <label for="">post title</label>
                    <input type="text" name="postTitle" value="<?php echo $row['postTitle']; ?>" class="input">
                </div>
                
                <div class="input-field">
                    <input type="text" hidden name="postID" value="<?php echo $row['postID']; ?>" class="input">
                </div>
                <div class="input-field">
                    <input type="text" name="postPic" hidden value="<?php echo $row['postPic']; ?>" class="input">
                </div>
                <div class="input-field">
                    <label for="">post description:</label>
                    <textarea name="postDecraption" class="input" rows="5"><?php echo $row['postDecraption']; ?></textarea>
                </div>
               
                <div class="input-field">
                    <label for="">post pic</label>
                    <input type="file" name="files[]" multiple class="input-img">
                </div>
                <div class="input-field">
                    <label for="">post Status</label>
                    <select class="input" name="postStatus" selected>
                    <option value="1">Public Posts</option>
                    <option value="2">Training Posts</option>
                    </select>
                </div>
                <div class="input-field">
                    <input type="submit" name="editPost" class="input submit-btn">
                </div>
            </form>
        </div>
    <?php
    }  elseif ($control == 'update') {
        if (isset($_POST['editPost'])) {
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
                        redirecthome($theMessage, 'profile.php');
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
                                redirecthome($theMessage, 'profile.php');
                            }
                        }
                    }
                }
            }
        }
    } elseif($control == "delete") {
        $postID = $_GET['postID'];
        $stmt = $conn->prepare("SELECT * FROM post WHERE postID=?");
        $stmt->execute(array($postID));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        extract($row);
        $postpic = $row['postPic'];
        if (file_exists($postpic)) {
            $postPicStatus = unlink($postPic);
        }
        $dalete = $conn->prepare("DELETE FROM post WHERE postID=?");
        $dalete->execute(array($postID));
        $rowDelete = $dalete->rowCount();
        if ($rowDelete > 0) {
            $TheMessages = '<div class="container"><div class="alert alert-success">Delete Successfully</div></div>';
            redirecthome($TheMessages, 'profile.php');
        }
    }
    
    include $tpl . "footer.inc";
?>