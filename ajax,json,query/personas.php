<?php
if( isset($_GET['id']) ) {
    get_persons($_GET['id']);
} else {
    die("Solicitud no válida.");
}

function get_persons( $id ) {
    
    $dbserver = "localhost";
    $dbuser = "usuario_basededatos";
    $password = "password_basededatos";
    $dbname = "nombre_basededatos";
    
    $database = new mysqli($dbserver, $dbuser, $password, $dbname);
    
    if($database->connect_errno) {
        die("No se pudo conectar a la base de datos");
    }
    
    $jsondata = array();
    
    
    if( is_array($id) ) {
        $id = array_map('intval', $id);
        $querywhere = "WHERE `ID` IN (" . implode( ',', $id ) . ")";
    } else {
        $id = intval($id);
        $querywhere = "WHERE `ID` = " . $id;
    }
    
    if ( $result = $database->query( "SELECT * FROM `cyb_users` " . $querywhere ) ) {
        
        if( $result->num_rows > 0 ) {
            
            $jsondata["success"] = true;
            $jsondata["data"]["message"] = sprintf("Se han encontrado %d usuarios", $result->num_rows);
            $jsondata["data"]["users"] = array();
            while( $row = $result->fetch_object() ) {
                
                
                $jsondata["data"]["users"][] = $row;
            }
            
        } else {
            
            $jsondata["success"] = false;
            $jsondata["data"] = array(
            'message' => 'No se encontró ningún resultado.'
            );
            
        }
        
        $result->close();
        
    } else {
        
        $jsondata["success"] = false;
        $jsondata["data"] = array(
        'message' => $database->error
        );
        
    }
    
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($jsondata, JSON_FORCE_OBJECT);
    
    $database->close();
    
}

exit();                            