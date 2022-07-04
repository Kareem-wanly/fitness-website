<?php 
    session_start();
    include "init.php";
    ?>
    <link rel="stylesheet" href="<?php echo $css;?>home-page.css">
    <?php
    include $tpl . "navbar.inc";
    $control = isset($_GET['control']) ? $_GET['control'] : 'main';
    if($control == 'main') {
        $stmt = $conn->prepare('SELECT * FROM section');
        $stmt->execute();
        $rows = $stmt->fetchAll();
        ?>
        <div class="container">
            <div class="main">
                <div class="maining-side">
                    <div class="main-title-sec">
                        <h2>all sections</h2>
                    </div>
                    <div class="vt-card-container">
                        <?php
                        foreach($rows as $row) {
                        echo'<div class="vt-card">
                            <div class="vt-card-content">
                                <div class="vt-card-header">'.$row['sectionName'].'</div>
                                <a href="?control=view&sectionID=' . $row['sectionID'] . '" class="vt-card-btn">view </a>
                            </div>
                        </div>';
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