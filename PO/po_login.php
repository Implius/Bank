<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="po_login.css">
    <title>JeFinance</title>
  </head>
  <body>
    <div class="Large">
    <div class="connectcard" className="items-center">

        <form action="po_login_check.php" method="post">
          <input class="idconnexion" type="text" id="NumSiren" name="NumSiren" placeholder="Numéro de SIREN" required><br>
          <input class="passwordconnexion" type="password" id="password" name="password" placeholder="Mot de passe" required> 
            <label class="checkbox-container">
              <input class="hidden" type="checkbox" onclick="myFunction()">
              <span class="checkmark"></span>
          </label>
          <br>
          <button class="buttonconnexion" type="submit">Se connecter</button>
        </form>

        <?php
        if (isset($_GET['error'])) {
            if ($_GET['error'] == 1) {
                echo "<p class='error'>Erreur d'authentification</p>";
            }
            elseif ($_GET['error'] == 2) {
                echo "<p class='error'>Vous n'avez pas les droits nécessaires</p>";
            }
            elseif ($_GET['error'] == 3) {
                echo "<p class='error'>Vous avez été déconnecté</p>";
            }
        }
        ?>

    </div>
  </div>

  <script>
      function myFunction() {
    var x = document.getElementById("password");
    if (x.type === "password") {
      x.type = "text";
    } else {
      x.type = "password";
    }
  }
  </script>

  </body>
</html>
