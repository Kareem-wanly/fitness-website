<link rel="stylesheet" href="layout/css/navbar.css">
</head>
<body>
    <div class="navbar-container">
        <div class="logo"><span class="web-logo"><i class="fas fa-dumbbell"></i></span> <span class="logo-title">sport</span></div>
        <ul class="navbar-list">
            <li class="list-item">
                <a data-name="home-page.php" class="nav-btn" href="home-page.php">
                    <span class="nav-btn-title">home page</span>
                </a>
            </li>
            <?php
            if (!isset($_SESSION['traineeID'])) {
                ?>
                <li class="list-item">
                    <a data-name="trainers.php" class="nav-btn" href="trainers.php">
                        <span class="nav-btn-title">trainers</span>
                    </a>
                </li>
                <li class="list-item">
                    <a data-name="Nutritionists.php" class="nav-btn" href="Nutritionists.php">
                        <span class="nav-btn-title">Nutritionists</span>
                    </a>
                </li>
            <?php
            } ?>
            <?php
            if (isset($_SESSION['traineeID'])) {
                ?>
                <li class="list-item">
                    <a data-name="trainers.php" class="nav-btn" href="trainers.php">
                        <span class="nav-btn-title">my trainer</span>
                    </a>
                </li>
                <li class="list-item">
                    <a data-name="Nutritionists.php" class="nav-btn" href="Nutritionists.php">
                        <span class="nav-btn-title">my Nutritionist</span>
                    </a>
                </li>
            <?php
            } ?>
            <li class="list-item">
                <a data-name="posts.php" class="nav-btn" href="posts.php">
                    <span class="nav-btn-title">posts</span>
                </a>
            </li>
        </ul>
        <ul class="nav-list">           
            <?php
                if(isset($_SESSION['traineeID']) || isset($_SESSION['trainerID']) || isset($_SESSION['nutritionistID'])) {
                    ?>
                    <li class="list-item"><a href="profile.php" class="profile-icon" title="profile"><img src='<?php echo $_SESSION['Pic']; ?>'></a></li>
                    <li class="list-item"><a href="sign-out.php" class="logout" title="sign out"><i class="fas fa-sign-out-alt"></i></a></li>
                    <?php
                } else { ?>

                    <a href="login.php">login | register</a>

                <?php
                }
            ?>
            <li class="list-item bar"><i class="fas fa-bars"></i></li>
        </ul>
    </div>
            <!-- <i class="fad fa-sign-out-alt"></i> -->
