<?php 

    include "init.php";
    session_start();
    ob_start();
    ?>

    <link rel="stylesheet" href="<?php echo $css;?>styling-forms.css">

    <?php 
        include $tpl . "navbar.inc"; 
        if(isset($_POST['traineeID'])) {
            $traineeID = $_POST['traineeID'];
        } else {
            $traineeID = $_GET['traineeID'];
        }
        $stmt = $conn->prepare("SELECT * FROM subscription_payments
                                WHERE traineeID = ?
                                ORDER BY subsPaymentDate DESC
                                LIMIT 1
                                ");
        $stmt->execute(array($traineeID));
        $rows = $stmt->fetch();
        $count = $stmt->rowCount();
        
        $stmt2 = $conn->prepare("SELECT * FROM trainees 
                                INNER JOIN section 
                                ON trainees.sectionID = section.sectionID 
                                WHERE traineeID = ? LIMIT 1");
        $result = $stmt2->execute(array($traineeID));
        $data = $stmt2->fetch();
        
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
                <form action="" method="GET">
                    <div class="input-field">
                        <label for="">payment value:</label>
                        <input class="input pass-input" type="number" name="payment-val" />
                    </div>
                    <input type="text" hidden name="traineeID" value="<?php echo $traineeID; ?>">
                    <div class="input-field">
                        <label for="">total payment value:</label>
                        <input class="input pass-input" 
                                value="<?php if ($count > 0) {
                echo $rows['restOfTotal'];
            } else {
                echo $data['registerPayment'];
            } ?>" 
                                readonly 
                                type="number" 
                                name="total-value" 
                                autocomplete="off"  
                                placeholder="total payment value">
                    </div>
                    <div class="input-field">
                        <input class="submit-btn" type="submit" name="add">
                    </div>
                </form>
            </div>
            </div>
   
    <script src="<?php echo $js . 'main.js'; ?>"></script>
    <script src="<?php echo $js . 'navbar.js'; ?>"></script>
    <?php include $tpl . "footer.inc";
    
            if (isset($_GET['add'])) {
                $traineeID = $_GET['traineeID'];
                $totalPaymentVal = $_GET['total-value'];
                $paymentValue = $_GET['payment-val'];
                $restOTotal = $totalPaymentVal - $paymentValue;
                $stmt = $conn->prepare("INSERT INTO subscription_payments(totalPaymentVal, paymentValue, restOfTotal, adminID, traineeID)
                                        VALUES(?, ?, ?, ?, ?)");
                $stmt->execute(array($totalPaymentVal, $paymentValue,$restOTotal , $_SESSION['ID'], $traineeID));
                $rows = $stmt->fetchAll();
                if ($rows > 0) {
                    header("location: payments.php");
                    exit();
                }
            }
        
    ob_end_flush();
?>