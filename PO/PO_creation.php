<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="PO.css">
  <title>JeFinance</title>
  <script>
    function sortTable() {
      const select = document.getElementById('sort_by');
      const selectedValue = select.value;
      // Redirige vers la même page avec le paramètre de tri
      window.location.href = `?sort_by=${selectedValue}`;
    }
  </script>
</head>

<body>
    <?php
    $onit = "Creation";
    include("../include/po_navbar.inc.php"); // Navbar
    ?>

  <div class="Compte_tableau">
    <form>
      <div class="formcrea">
        <input class="forminput" type="text" id="Raison" name="Raison" placeholder="Raison social" required><br>
      </div>
      <div class="formcrea">
        <input class="forminput" type="text" id="NumCompte" name="NumCompte" placeholder="Numéro du compte"
          required><br>
      </div>
      <div class="formcrea">
        <input class="forminput" type="text" id="NumSiren" name="NumSiren" placeholder="Numéro de SIREN" required><br>
      </div>
      <div class="devise">
      <select name="money" id="money" required>
        <option value="" disabled selected>Devise</option>
        <option value="Euros">€</option>
        <option value="Dollars">$</option>
        <option value="Livre">£</option>
      </select>
    </div>
      <button class="buttoncreate" type="submit">Créer le compte</button>
    </form>
  </div>
</body>

</html>
