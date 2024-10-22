<?php
global$cnx;
include("../include/connexion.inc.php");
?>
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
$onit = "Impaye";
include("../include/po_navbar.inc.php"); // Navbar
?>
  <div class="mini_navbar">
      <div class="mini_onit">Tableau</div>
      <a class="mini_link" href="PO_impaye_Histo.php">Histogramme</a>
      <a class="mini_link" href="PO_impaye_Circu.php">Circulaire</a>
  </div>
  <div class="Compte_tableau">
    <div class="sorting">
      Trier par :
      <select name="sort_by" id="sort_by" onchange="sortTable()">
        <option value="date_plusrecent">Date (plus récent)</option>
        <option value="date_plusancient">Date (plus ancient)</option>
        <option value="numero_remise">Numéro d'impayé</option>
        <option value="Numero_SIREN">Numéro SIREN</option>
      </select>
    </div>
    <table class="tableau">
      <thead>
        <tr>
          <th class="table-blue">
            Impayé N°
          </th>
          <th class="table-darkblue">
            Date
          </th>
          <th class="table-blue">
            Autre parti
          </th>
          <th class="table-darkblue">
            N° SIREN Autre parti
          </th>
          <th class="table-blue">
            Objet
          </th>
          <th class="table-darkblue">
            Raison
          </th>
          <th class="table-blue">
            Montant
          </th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>
            0001
          </td>
          <td>
            12/10/2023
          </td>
          <td>
            Jules
          </td>
          <td>
            842 017 349
          </td>
          <td>
            KFC
          </td>
          <td>
            05
          </td>
          <td>
            50 €
          </td>
        </tr>
        <!--Mettre code PHP ici-->
      </tbody>
    </table>
  </div>
</body>

</html>
