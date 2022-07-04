<?php
session_start();
$conn = new PDO('mysql:host=localhost;dbname=sport', 'root', '');
include 'FPDF/fpdf.php';
$tpID = $_GET['tpID'];

class MyPDF extends FPDF
{
    function Table()
    {
        $this->SetFont('arial', 'B', '16');
        $this->Cell(25, 10, 'Day ', 1, 0, 'C');
        $this->Cell(40, 10, 'muscle ', 1, 0, 'C');
        $this->Cell(134, 10, 'exercise ', 1, 0, 'C');
        
        $this->ln();
    }
    function viewTable($conn)
    {
        $tpID = $_GET['tpID'];
        $this->SetFont('arial', 'B', '16');
        $stmt = $conn->query("SELECT * FROM trainerprograms
                            INNER JOIN programdetails
                            ON trainerprograms.tpID=programdetails.tpID
                            INNER JOIN weekdays
                            ON weekdays.dayID=programdetails.dayID
                            WHERE trainerprograms.tpID=$tpID");
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            $this->Cell(25, 10, $row->day, 1, 0, 'C');
            $this->Cell(40, 10, $row->muscle, 1, 0, 'C');
            $this->Cell(134, 10, $row->exercise, 1, 0, 'C');
            $this->ln();
        }
    }
}

// Create PDF Fille
$pdf = new MyPDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetTitle('Programs');
$pdf->Table();
$pdf->viewTable($conn);
$pdf->Output();
// End Create PDF File

?>