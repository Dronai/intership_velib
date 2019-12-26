<?php
require_once 'connect.php';

function get_list_bornes($pdo){
    $sql = "SELECT * FROM velib";
    $exe = $pdo->query($sql);
    $List_bornes = array();
    while($result = $exe->fetch(PDO::FETCH_OBJ)){
        array_push($List_bornes, array("id" => $result));
    }

    return json_encode($List_bornes);
}

function get_borne($id, $pdo){
    $sql = "SELECT * FROM velib WHERE id = ".$id; //je réalise ma requête avec l'ID passée en paramètres
    $exe = $pdo->query($sql); //j'exécute ma requête
    while($result = $exe->fetch(PDO::FETCH_OBJ)) {
        $borne = array("ID" => $result->id);//je mets le résultat de ma requête dans une variable
    }
    return json_encode($borne); //je retourne l'article en question
}

function get_list_bornes_zone($address, $pdo){
    $sql = "SELECT * FROM velib";
    $exe = $pdo->query($sql);
    $List_bornes = array();
    while($result = $exe->fetch(PDO::FETCH_OBJ)){
        array_push($List_bornes, array("geo" => $result->geo));
    }

    $data = get_info_address($address);

    $bornesAvaible =array();
    $mark = ',';
    foreach ($List_bornes as $borne){
       $value = explode($mark, $borne["geo"]);

       if(isset($data) && $data != 'REQUEST_DENIED'){
            $km = calc_distance($data['lon'], $data['lat'], $value);

           if($km < 0.5){
               array_push($bornesAvaible, $borne);
           }
       }else{
           echo "here error";
       }
    }

    header('Content-Type: application/json');
    return json_encode($bornesAvaible);
}

function get_info_address($address){
    $apikey = 'KEYKEYKEY';

    $address = str_replace(" ", "+", $address);

    $json = file_get_contents("https://maps.google.com/maps/api/geocode/json?address=$address&sensor=true&region=fr&key=" . $apikey);
    $json = json_decode($json);

    if ($json->status == 'OK' && count($json->results) > 0) {
        $res = $json->results[0];
        $data['address'] = $res->formatted_address;
        $data['lat'] = $res->geometry->location->lat;
        $data['lon'] = $res->geometry->location->lng;
        return $data;
    }else {
        return $json->status;
    }
}

function calc_distance($lon, $lat, $value){
    $earth_radius = 6378137;
    $rlon1 = deg2rad($lon);
    $rlat1 = deg2rad($lat);
    $rlon2 = deg2rad($value[1]);
    $rlat2 = deg2rad($value[0]);

    $dlon = ($rlon2 - $rlon1) / 2;
    $dlat = ($rlat2 - $rlat1) / 2;
    $a = (sin($dlat) * sin($dlat)) + cos($rlat1) * cos($rlat2) * (sin($dlon) * sin($dlon));
    $d = 2 * atan2(sqrt($a), sqrt(1 - $a));

    $km = ($earth_radius * $d)/1000;

    return $km;
}

$possible_url = array("get_list_bornes", "get_borne", "get_list_bornes_zone", "get_info_address"); //je définis les URLs valables

$value = "Une erreur est survenue"; //je mets le message d'erreur par défaut dans une variable

if (isset($_GET["action"]) && in_array($_GET["action"], $possible_url)) { //si l'URL est OK

    switch ($_GET["action"]) {

        case "get_list_bornes": $value = get_list_bornes($pdo); break; //Je récupère la liste des articles

        case "get_info_address": if (isset($_GET["address"])) $value = get_info_address($_GET["address"], $pdo); break;

        case "get_borne": if (isset($_GET["id"])) $value = get_borne($_GET["id"], $pdo); break; //si l'ID est spécifié alors je renvoie l'article en question

        case "get_list_bornes_zone": if (isset($_GET["address"])) $value = get_list_bornes_zone($_GET["address"], $pdo);

        else $value = "Argument manquant"; break; } //si l'ID n'est pas valable je change mon message d'erreur
}

exit(json_encode($value)); //je retourne ma réponse en JSON
