<?php
// PHP program to add 10 days in date 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Declare a date
    $daritgl = date_create($_POST['daritgl']);
    $lamacuti = ($_POST['lamacuti']);

    $end = date_create(date_format($daritgl, "Y-m-d"));
    for ($hari=0; $hari<$lamacuti; 
            date_add($end, date_interval_create_from_date_string("1 days"))) {
        if ($end->format('l') != "Saturday" && $end->format('l') != "Sunday") {
            // Use date_add() function to add date object
            echo "weekday";
            $hari++;
        } else {
            echo "weekend";
        }
    }

    
    date_add($end, date_interval_create_from_date_string("-1 days"));

    // Display the added date
    echo date_format($daritgl, "Y-m-d");
    echo date_format($end, "Y-m-d");
} else {
?>
    <form action="test.php" method="post">
        <input type="date" name="daritgl" id="">
        <input type="number" name="lamacuti" id="">
        <input type="submit" value="">
    </form>

<?php
}

?>