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
$localitate = explode(',',trim($searchTerm));
$judet = explode('.',trim($searchTerm));

if ($query = $db->query("SELECT * FROM date WHERE localitate LIKE '%".$localitate[0]."%' AND judet LIKE '%".$judet[1]."%' ORDER BY localitate ASC")){
    while ($row = mysqli_fetch_array($query)) {
        $location = htmlentities(stripcslashes($row['localitate']));
        $localitate = explode(' ',$location);
        
        if ($localitate[0] == 'Bucuresti') {
            $location = $localitate[0];
            $county = $localitate[1] . ' ' . $localitate[2];
        } else {
            $county = htmlentities(stripcslashes($row['judet']));    
        }
        
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
