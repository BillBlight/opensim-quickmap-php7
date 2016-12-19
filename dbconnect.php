<?php

// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++   Anfang der Einstellungen
//Datenbank Einstellungen - IP oder localhost, Benutzername, Passwort, Datenbankname
$link = new mysqli("localhost", "Benutzername", "Passwort", "Datenbankname");
// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++   Ende der Einstellungen

if ($mysqli->connect_errno) {
    die("Verbindung fehlgeschlagen: " . $mysqli->connect_error);
}
?>