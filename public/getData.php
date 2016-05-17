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
$searchTerm = trim(strip_tags($_GET['text']));
$a_json = array();
$a_json_row = array();
$localitate = explode(',',trim($searchTerm))[0];
$judet = explode('.',trim($searchTerm))[1];

if ($query = $db->query("SELECT * FROM date WHERE localitate LIKE '%".$localitate."%' AND judet LIKE '%".$judet."%' ORDER BY localitate ASC")){
    while ($row = mysqli_fetch_array($query)) {
        $location = htmlentities(stripcslashes($row['localitate']));
        $county = htmlentities(stripcslashes($row['judet']));
        $value = htmlentities(stripcslashes($row['valoare_totala_vot_1persoana']));
        $a_json_row['location'] = ucwords(strtolower($location));
        $a_json_row['county'] = ucwords(strtolower($county));
        $a_json_row['value'] = $value;
        array_push($a_json, $a_json_row);
    }
}

//return json data
echo json_encode($a_json);
flush();

$db->close();
?>
