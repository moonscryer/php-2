<?php

function connectToDB()
{
    $env = parse_ini_file('.env');
    $db_host = $env["DB_HOST"];
    $db_user = $env["DB_USER"];
    $db_password = $env["DB_PASSWORD"];
    $db_db = $env["DB_DB"];
    $db_port = $env["DB_PORT"];

    try {
        $db = new PDO('mysql:host=' . $db_host . '; port=' . $db_port . '; dbname=' . $db_db, $db_user, $db_password);
    } catch (PDOException $e) {
        echo "Error!: " . $e->getMessage() . "<br />";
        die();
    }
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
    return $db;
}

function newAppointment(String $name, String $email, String $appdate): bool|int
{
    $db = connectToDB();
    $sql = "INSERT INTO appointments(name, email, appdate) VALUES (:name, :email, :appdate)";
    $stmt = $db->prepare($sql);
    $stmt->execute([
        'name' => $name,
        'email' => $email,
        'appdate' => $appdate
    ]);

    return $db->lastInsertId();
}
