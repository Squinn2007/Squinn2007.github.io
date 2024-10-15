<?php
require('localhost.php'); // Zorg dat dit bestand correct de verbinding maakt met de database

// Controleer of de verbinding succesvol is
if ($conn->connect_error) {
    die("Verbinding met de database mislukt: " . $conn->connect_error);
}

// Verwerk de login als het formulier is verzonden
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Haal de ingevoerde gegevens op en controleer of ze niet leeg zijn
    if (!empty($_POST['name']) && !empty($_POST['password'])) {
        $name = $_POST['name'];
        $password = $_POST['password'];

        // Gebruik een prepared statement om SQL-injectie te voorkomen
        $stmt = $conn->prepare("SELECT * FROM MyGuests WHERE bandname = ? AND wachtwoord = ?");
        $stmt->bind_param("ss", $name, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Als de gegevens kloppen, geef een succesbericht en stuur door naar bands.php
            echo "<script>alert('Inlog succesvol, welkom $name');</script>";

            // Verwijs naar bands.php met verborgen formulier en POST
            echo "<form id='redirectForm' action='Bands.php' method='post'>
                      <input type='hidden' name='name' value='$name'>
                      <input type='hidden' name='password' value='$password'>
                  </form>
                  <script type='text/javascript'>
                      document.getElementById('redirectForm').submit();
                  </script>";
        } else {
            // Als de gegevens niet kloppen, geef een foutmelding
            echo "<p style='color:red;'>Naam of wachtwoord is onjuist. Probeer het opnieuw.</p>";
        }
        
        $stmt->close(); // Sluit het prepared statement
    } else {
        // Als naam of wachtwoord niet zijn ingevuld, geef een foutmelding
        if (empty($_POST['name'])) {
            echo "<p style='color:red;'>Naam mag niet leeg zijn.</p>";
        }
        if (empty($_POST['password'])) {
            echo "<p style='color:red;'>Wachtwoord mag niet leeg zijn.</p>";
        }
    }
}

// Sluit de databaseverbinding
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="fullstack.css">
    <title>Inlog</title>
</head>
<body>
<form id="forminlog" action="" method="post">
    <br><br>
    <p id="name">Naam:</p>
    <input type="text" name="name" required>
    <br><br>
    <p>Wachtwoord:</p>
    <input type="password" name="password" required>
    <br><br>
    <button type="submit">Verzenden</button>
</form>
</body>
<footer>
<a id="registreren" href="http://localhost/website/phpfullstack/registreren.php">Registreren</a>
</footer>
</html>