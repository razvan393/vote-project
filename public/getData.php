<?php
//database configuration
$dbHost = 'localhost';
$dbUsername = 'root';
$dbPassword = 'mysql';
$dbName = 'vot2016';

//connect with the database
$db = new mysqli($dbHost,$dbUsername,$dbPassword,$dbName);
if($db->connect_error) {
    echo 'Database connection failed. Error' . $db->connect_errno . ' ' . $db->connect_error;
    exit;
} else {
    $db->set_charset('utf8');
}

//get search term
$searchTerm = trim(strip_tags($_GET['term']));
$a_json = array();
$a_json_row = array();
if ($query = $db->query("SELECT * FROM date WHERE localitate LIKE '%".$searchTerm."%' ORDER BY localitate ASC")){
    while ($row = mysqli_fetch_array($query)) {
        $location = htmlentities(stripcslashes($row['localitate']));
        $judet = htmlentities(stripcslashes($row['judet']));
        $valoare = htmlentities(stripcslashes($row['valoare_totala_vot_1persoana']));
        $a_json_row['location'] = $location;
        $a_json_row['judet'] = $judet;
        $a_json_row['valoare'] = $valoare;
        array_push($a_json, $a_json_row);
    }
}

//return json data
echo json_encode($a_json);
flush();

$db->close();
?>
