<?php
session_start();
ob_start();
?>
<?php
require('localhost.php'); // Verbind met de database
// Controleer of het formulier is verzonden via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Controleer of alle verwachte POST-velden aanwezig zijn
    if (isset($_POST['bandname']) && isset($_POST['genre'])) {
        // Haal de gegevens uit het formulier op met de correcte veldnamen
        $bandname = mysqli_real_escape_string($conn, $_POST['bandname']);
        $genre = mysqli_real_escape_string($conn, $_POST['genre']);

        // Controleer of de bandnaam al bestaat in de database
        $checkQuery = "SELECT * FROM Bands WHERE bandname = '$bandname'";
        $checkResult = $conn->query($checkQuery);

        if ($checkResult->num_rows > 0) {
            // Als de bandnaam al bestaat, geef een foutmelding
            echo "<p style='color:red;'>De bandnaam $bandname bestaat al. Kies een andere naam.</p>";
            echo "<form id='redirectForm' action='bands.php' method='post'></form>";
        } else {
            // Voeg de nieuwe gegevens toe aan de database
            $insertQuery = "INSERT INTO Bands (genre, bandname) VALUES ('$genre', '$bandname')";

            if ($conn->query($insertQuery) === TRUE) {
                header("location: progamma.php");
            } else {
                echo "<p style='color:red;'>Fout bij het opslaan van gegevens: " . $conn->error . "</p>";
            }
        }
    } else {
        echo "<p style='color:red;'>Niet alle velden zijn ingevuld. Controleer het formulier en probeer het opnieuw.</p>";
    }
}

// Haal alle bands op en toon ze
$query = $conn->query("SELECT * FROM Bands");
while ($row = $query->fetch_assoc()) {
    echo "<p>Band: " . $row['bandname'] . " | Genre: " . $row['genre'] . "</p>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="fullstack.css" rel="stylesheet">
    <title>Invulformulier</title>
</head>
<body>
<form method="post" action="">
    <p><strong>Vul hier de bandnaam in:</strong></p>
    <input type="text" name="bandname" required> 
    <p><strong>Genre:</strong></p>
    <!-- Correcte radioknoppen met name="genre" en verschillende values -->
    <input type="radio" name="genre" value="pop" required> Pop<br>
    <input type="radio" name="genre" value="rock"> Rock<br>
    <input type="radio" name="genre" value="klassiek"> Klassiek<br>
    <input type="radio" name="genre" value="jazz"> Jazz<br>
    
    <br>
    <button type="submit">Verzenden</button>
</form>
</body>
</html>