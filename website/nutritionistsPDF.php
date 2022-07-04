<?php 
session_start();
$conn = new PDO('mysql:host=localhost;dbname=sport', 'root', '');
include 'FPDF/fpdf.php';
$npID = $_GET['npID'];

class MyPDF extends FPDF
{ 
    function Table()
    {
        $this->SetFont('arial', 'B', '10');
        $this->Cell(10, 10, '#', 1, 0, 'C');
        $this->Cell(30, 10, 'breakfast', 1, 0, 'C');
        $this->Cell(35, 10, 'lunch', 1, 0, 'C');
        $this->Cell(30, 10, 'Dinner', 1, 0, 'C');
        $this->Cell(45, 10, 'preWorkout', 1, 0, 'C');
        $this->Cell(48, 10, 'postWorkout', 1, 0, 'C');
        $this->ln();
    }
    function viewTable($conn)
    {
        $npID = $_GET['npID'];
        $this->SetFont('arial', 'B', '10');
        $stmt = $conn->query("SELECT * FROM nutritionistsprograms
                                INNER JOIN n_p_details
                                ON nutritionistsprograms.npID=n_p_details.programID 
                                WHERE nutritionistsprograms.npID='$npID'");
        $count = 0 ;
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                $count++;
                $this->Cell(10, 10, $count, 1, 0, 'C');
                $this->Cell(30, 10, $row->breakfast, 1, 0, 'C');
                $this->Cell(35, 10, $row->lunch, 1, 0, 'C');
                $this->Cell(30, 10, $row->Dinner, 1, 0, 'C');
                $this->Cell(45, 10, $row->preWorkout, 1, 0, 'C');
                $this->Cell(48, 10, $row->postWorkout, 1, 0, 'C');
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