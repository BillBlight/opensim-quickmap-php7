PHP7 Version Manfred Aabye

Lade quickmap herunter und entpacke es.

Öffne dbconnect.php

Nutze deine Einstellungen aus deiner Robust.ini.

Trage sie ein unter:
// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++   Anfang der Einstellungen
//Datenbank Einstellungen - IP oder localhost, Benutzername, Passwort, Datenbankname
$link = new mysqli("localhost", "Benutzername", "Passwort", "Datenbankname");
// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++   Ende der Einstellungen

Lade nun diese Quickmap Ordner in deinen Webserver.