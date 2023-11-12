<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

define ('INDEX', true);

require 'inc/dbcon.php';
require 'inc/base.php';

switch($_SERVER['REQUEST_METHOD']) {
    case 'GET': // GET medewerker { id, voornaam, familienaam, specialisatie_id, specialisatie naam }
        $sql="select mw_id, mw_voornaam, mw_familienaam, mw_specialisatie_id, sp_id, sp_naam FROM medewerker LEFT JOIN specialisatie ON mw_specialisatie_id = sp_id";

        $result = $conn -> query($sql);

        if (!$result) {
            $response['code'] = 7;
            $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
            $response['data'] = $conn->error;
            deliver_response($response);
        }

        $response['data'] = getJsonObjFromResult($result);
        $result->free();
        $conn->close();
        deliver_JSONresponse($response);
        break;
    case 'PUT': // PUT medewerker { voornaam, familienaam, specialisatie_id } op bestaande medewerker id
        if(!$stmt = $conn->prepare("update medewerker set mw_voornaam = ?, mw_familienaam = ?, mw_specialisatie_id = ? where mw_id = ?")){
            die('{"error":"Prepared Statement failed on prepare","errNo":"' . json_encode($conn -> errno) .'","mysqlError":"' . json_encode($conn -> error) .'","status":"fail"}');
        }
        if(!$stmt -> bind_param("ssii", htmlentities($postvars['mw_voornaam']), $postvars['mw_familienaam'], $postvars['mw_specialisatie_id'], $postvars['mw_id'])){
            die('{"error":"Prepared Statement bind failed on bind","errNo":"' . json_encode($conn -> errno) .'","mysqlError":"' . json_encode($conn -> error) .'","status":"fail"}');
        }
        $stmt -> execute();
        
        if($conn->affected_rows == 0) {
            $stmt -> close();
            die('{"error":"Prepared Statement failed on execute : no rows affected","errNo":"' . json_encode($conn -> errno) .'","mysqlError":"' . json_encode($conn -> error) .'","status":"fail"}');
        }
        $stmt -> close();
        die('{"data":"ok","message":"Record added successfully","status":"ok"}');
        break;
    case 'DELETE': // DELETE medewerker en project_medewerker met gegeven medewerker id
        if (!$stmt1 = $conn->prepare("DELETE FROM project_medewerker WHERE prmw_medewerker_id = ?")) {
            die('{"error":"Prepared Statement failed on prepare","errNo":"' . json_encode($conn -> errno) .'","mysqlError":"' . json_encode($conn -> error) .'","status":"fail"}');
        }
        if (!$stmt1->bind_param("i", htmlentities($postvars['mw_id']))) {
            die('{"error":"Prepared Statement bind failed on bind","errNo":"' . json_encode($conn -> errno) .'","mysqlError":"' . json_encode($conn -> error) .'","status":"fail"}');
        }
        $stmt1->execute();
        
        if (!$stmt2 = $conn->prepare("DELETE FROM medewerker WHERE mw_id = ?")) {
            die('{"error":"Prepared Statement failed on prepare","errNo":"' . json_encode($conn -> errno) .'","mysqlError":"' . json_encode($conn -> error) .'","status":"fail"}');
        }
        if (!$stmt2->bind_param("i", htmlentities($postvars['mw_id']))) {
            die('{"error":"Prepared Statement bind failed on bind","errNo":"' . json_encode($conn -> errno) .'","mysqlError":"' . json_encode($conn -> error) .'","status":"fail"}');
        }
        $stmt2->execute();
        
        $stmt1->close();
        $stmt2->close();
        
        die('{"data":"ok","message":"Record added successfully","status":"ok"}');
        break;
    case 'POST': // POST medewerker { voornaam, familienaam, specialisatie_id }
        if(!$stmt = $conn->prepare("insert into medewerker (mw_voornaam, mw_familienaam, mw_specialisatie_id) values (?,?,?)")){
            die('{"error":"Prepared Statement failed on prepare","errNo":"' . json_encode($conn -> errno) .'","mysqlError":"' . json_encode($conn -> error) .'","status":"fail"}');
        }
        if(!$stmt -> bind_param("ssi", htmlentities($postvars['mw_voornaam']), $postvars['mw_familienaam'], $postvars['mw_specialisatie_id'])){
            die('{"error":"Prepared Statement bind failed on bind","errNo":"' . json_encode($conn -> errno) .'","mysqlError":"' . json_encode($conn -> error) .'","status":"fail"}');
        }
        $stmt -> execute();
        
        if($conn->affected_rows == 0) {
            $stmt -> close();
            die('{"error":"Prepared Statement failed on execute : no rows affected","errNo":"' . json_encode($conn -> errno) .'","mysqlError":"' . json_encode($conn -> error) .'","status":"fail"}');
        }
        $stmt -> close();
        die('{"data":"ok","message":"Record added successfully","status":"ok"}');
        break;
}

?>