<?php
$connect = mysqli_connect("localhost", "fishopp2_furnitu", "fslhggi2020@@@", "fishopp2_furniture");
         
$output = '';
if(isset($_POST["import"]))
{
    $tmp = explode(".", $_FILES["excel"]["name"]);
    $extension = end($tmp); // For getting Extension of selected file
    $allowed_extension = array("xls", "xlsx", "csv"); //allowed extension
    if (in_array($extension, $allowed_extension)) //check selected file extension is present in allowed extension array
    
 {
    $file = $_FILES["excel"]["tmp_name"]; // getting temporary source of excel file
    include("/PhpSpreadsheet/IOFactory.php"); // Add PHPExcel Library in this code
    //require_once JPATH_LIBRARIES . '/phpspreadsheet/phpspreadsheet.php';
   // use PhpSpreadsheet\Spreadsheet;

    $objPHPExcel = PHPExcel_IOFactory::load($file); // create object of PHPExcel library by using load() method and in load method define path of selected file

    $output .= "<label class='text-success'>Data Inserted</label><br /><table class='table table-bordered'>";
    foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)
  {
    $highestRow = $worksheet->getHighestRow();
    for($row=2; $row<=$highestRow; $row++)
   {
        $output .= "<tr>";
                $name = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow(0, $row)->getValue());
                $counting_unit = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow(1, $row)->getValue());
                $package_type = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow(2, $row)->getValue());
                $weight = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow(3, $row)->getValue());
                $number_in_package  = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow(4, $row)->getValue());
                $delivery_time = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow(5, $row)->getValue());
                $msrp = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow(6, $row)->getValue());
                $sort_price = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow(7, $row)->getValue());
                $sale_type = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow(8, $row)->getValue());
                $query = "INSERT INTO tbl_test(product_name, product_counting_unit,product_package_type,product_weight
    ,product_number_in_package,product_delivery_time,product_msrp,product_sort_price,product_sale_type	) 
    VALUES ('" . $name . "', '" . $counting_unit . "', '" . $package_type . "', '" . $weight . "', '" . $number_in_package . "'
    , '" . $delivery_time . "'
    , '" . $msrp . "', '" . $sort_price . "', '" . $sale_type . "')";
                mysqli_query($connect, $query);
                $output .= '<td>' . $name . '</td>';
                $output .= '<td>' . $counting_unit . '</td>';
                $output .= '<td>' . $package_type . '</td>';
                $output .= '<td>' . $package_type . '</td>';
                $output .= '<td>' . $weight . '</td>';
                $output .= '<td>' . $number_in_package . '</td>';
                $output .= '<td>' . $msrp . '</td>';
                $output .= '<td>' . $sort_price . '</td>';
                $output .= '<td>' . $sale_type . '</td>';

   }
  } 
    $output .= '</table>';

 }
    else
 {
    $output = '<label class="text-danger">Invalid File</label>'; //if non excel file then
 }
}
?>

<html>
    <head>
        <title>ExcelToMysql</title>
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <link href="css/bootstrap.min.css" rel="stylesheet" />
        
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="container box">
            <h3 align="center">Import Excel to Mysql</h3><br />
            <form method="post" enctype="multipart/form-data">
                <label>Select Excel File</label>
                <input type="file" name="excel" />
                <br />
                <input type="submit" name="import" class="btn btn-info" value="Import" />
            </form>
            <br />
            <br />
            <?php
            echo $output;
            ?>
        </div>
    </body>
</html>