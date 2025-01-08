<?php

$host = 'localhost'; // Database host
$user = 'root';      // Database username
$pass = '';          // Database password
$dbname = 'mbrouk'; // Database name

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titre = $_POST["titre"];
    $type = $_POST["type"];
    $date_debut = $_POST["date_debut"];
    $date_fin = $_POST["date_fin"];
    $fuseau_horaire = $_POST["fuseau_horaire"];
    $invites = $_POST["invites"];
    $lieu = $_POST["lieu"];
    $description = $_POST["description"];

    $stmt = $conn->prepare("INSERT INTO evenement (titre, type, date_debut, date_fin, fuseau_horaire, invites, lieu, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $titre, $type, $date_debut, $date_fin, $fuseau_horaire, $invites, $lieu, $description);

    if ($stmt->execute()) {
        echo "Événement créé avec succès !";
    } else {
        echo "Erreur : " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}

?>
