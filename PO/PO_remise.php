<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="po.css">
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
    $onit = "Remise";
    include("../include/po_navbar.inc.php"); // Navbar
    ?>

  <div class="Compte_tableau">
    <div class="sorting">
      Trier par :
      <select name="sort_by" id="sort_by" onchange="sortTable()">
        <option value="date_plusrecent">Date (plus récent)</option>
        <option value="date_plusancient">Date (plus ancient)</option>
        <option value="numero_remise">Numéro de remise</option>
        <option value="Numero_SIREN">Numéro SIREN</option>
      </select>
    </div>
    <table class="tableau">
      <thead>
        <tr>
          <th class="table-blue">
            Remise N°
          </th>
          <th class="table-darkblue">
            Date
          </th>
          <th class="table-blue">
            Émetteur
          </th>
          <th class="table-darkblue">
            N° SIREN Émetteur
          </th>
          <th class="table-blue">
            Objet
          </th>
          <th class="table-darkblue">
            Bénéficiaire
          </th>
          <th class="table-blue">
            N° SIREN Bénéficiaire
          </th>
          <th class="table-darkblue">
            Montant du compte
          </th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="white">
            0005
          </td>
          <td class="grey">
            14/09/2024
          </td>
          <td class="white">
            Dupont
          </td>
          <td class="grey">
            425 682 301
          </td>
          <td class="white">
            Vente de produit
          </td>
          <td class="grey">
            E.Leclerc
          </td>
          <td class="white">
            572 183 994
          </td>
          <td class="grey montant">
            87152.09 €
          </td>
        </tr>
        <!--Mettre code PHP ici-->
      </tbody>
    </table>
  </div>
</body>

</html>