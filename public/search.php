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
//get matched data from table
if ($query = $db->query("SELECT * FROM date WHERE localitate LIKE '%".$searchTerm."%' ORDER BY localitate ASC")){
    $index = 0;
    while ($row = mysqli_fetch_array($query)) {
        $location = htmlentities(stripcslashes($row['localitate']));
        $county = htmlentities(stripcslashes($row['judet']));
        $a_json_row['label'] = ucwords(strtolower($location)) . ', jud.' . strtoupper($county);
        $a_json_row['value'] = ucwords(strtolower($location)) . ', jud.' . strtoupper($county);
        array_push($a_json, $a_json_row);
    }
}

//return json data
echo json_encode($a_json);
flush();

$db->close();
?>
