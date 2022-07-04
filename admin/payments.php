<?php 
    include "init.php";
    session_start();
?>

<link rel="stylesheet" href="<?php echo $css;?>styling-forms.css">


<?php 
    include $tpl . "navbar.inc"; 
    $control = isset($_GET['control']) ? $_GET['control'] : "manage";
?>
<?php if($control == 'manage') { 
        $stmt = $conn->prepare("SELECT subscription_payments.*, traineeName, adminName
                                FROM subscription_payments 
                                INNER JOIN trainees 
                                ON subscription_payments.traineeID = trainees.traineeID
                                INNER JOIN admins 
                                ON subscription_payments.adminID = admins.adminID
                                ORDER BY subsPaymentDate DESC");
        $stmt->execute();
        $rows = $stmt->fetchAll();
    ?>
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
            <h2>all payments:</h2>
           <a class="add-btn" href="payments.php?control=add"><span class="add-icon"><i class="fas fa-plus"></i></span>add new payment</a>
       
        </div>
        <div style="overflow-x:auto;">
            <table  class="bills-table">
                <thead>
                    <th>payment maker</th>
                    <th>payment owner</th>
                    <th>payment Date</th>
                    <th>full payment</th>
                    <th>Payment value</th>
                    <th>Rest of total</th>
                    
                </thead>
                <?php 
                
                if(isset($_GET['search'])) {
                    $searchValue = $_GET['search'];
                    $stmt2 =  $stmt = $conn->prepare("SELECT subscription_payments.*, traineeName, adminName
                                                        FROM subscription_payments 
                                                        INNER JOIN trainees 
                                                        ON subscription_payments.traineeID = trainees.traineeID
                                                        INNER JOIN admins 
                                                        ON subscription_payments.adminID = admins.adminID
                                                        WHERE traineeName LIKE ?
                                                        ORDER BY subsPaymentDate DESC");
                    $stmt->execute(array("%$searchValue%"));
                    $rows = $stmt->fetchAll();
                    $count = $stmt->rowCount();
                    if($count > 0) {
                        foreach($rows as $row) {
                            echo '
                            <tr>
                                <td> '.$row['adminName'] .' </td>
                                <td> '.$row['traineeName'] .' </td>
                                <td> '.$row['subsPaymentDate'] .' </td>
                                <td> '.$row['totalPaymentVal'] .' </td>
                                <td> '.$row['paymentValue'] .' </td>
                                <td> '.$row['restOfTotal'] .' </td>
                            </tr>
                            ';
                        }
                    } else {
                        echo '
                            <tr>
                                <td colspan="6">no records please type a correct name</td>
                            </tr>
                        ';
                    }
                } else {
                    foreach($rows as $row) {
                        echo '
                        <tr>
                            <td> '.$row['adminName'] .' </td>
                            <td> '.$row['traineeName'] .' </td>
                            <td> '.$row['subsPaymentDate'] .' </td>
                            <td> '.$row['totalPaymentVal'] .' </td>
                            <td> '.$row['paymentValue'] .' </td>
                            <td> '.$row['restOfTotal'] .' </td>
                        ';
                    }
                }
                ?>
            </table>
        </div>
    </div>
<?php
} elseif($control = 'add') {
    
    ?>

    <div class="container form-container">
        <div class="header-sec">
            <div class="icon"><i class="fas fa-bars"></i></div>
            <a href="logout.php" class="logut-btn">
                logout<i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
        <div class="form add-form">
            <h2 class="login">add new payment</h2>
            <form action="add-payment.php" method="POST" enctype="multipart/form-data">
                <div class="input-field">
                    <label for="">select trainee name:</label>
                    <select class="select-input" name="traineeID">
                    <?php
                            $stmt = $conn->prepare("SELECT * FROM trainees");

                            $stmt->execute();

                            $rows = $stmt->fetchAll();

                            foreach($rows as $row) {
                                echo '<option value="'.  $row['traineeID'] . '">' . $row['traineeName'] . '</option>';
                            }
                    ?>
                    </select>
                </div>
                <div class="input-field">
                    <input class="submit-btn" type="submit" name="add-payment">
                </div>
            </form>
        </div>
    </div>

<?php
}
?>


<script src="<?php echo $js . 'main.js';?>"></script>
<script src="<?php echo $js . 'navbar.js';?>"></script>
<?php include $tpl . "footer.inc";?>