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
  <div class="navbar">
    <nav>
      <div class="onit">Compte</div>
      <a class="link" href="#">Remise</a>
      <a class="link" href="#">Impayé</a>
      <a class="link" href="#">Création</a>
    </nav>
    <a class="deco" href="#"></a>
  </div>
  <div class="Compte_tableau">
    <div class="sorting">
      Trier par :
      <select name="sort_by" id="sort_by" onchange="sortTable()">
        <option value="montant du compte">Montant du compte</option>
        <option value="nombre de remise">Nombre de remises</option>
        <option value="montant des impayés">Montant des impayés</option>
        <option value="Numéro SIREN">Numéro SIREN</option>
      </select>
    </div>
    <table class="tableau">
      <thead>
        <tr>
          <th class="table-blue">
            Raison Social
          </th>
          <th class="table-green">
            N° de compte
          </th>
          <th class="table-yellow">
            N° SIREN
          </th>
          <th class="table-darkblue">
            Montant du compte
          </th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="white">
            Picard
          </td>
          <td class="grey">
            **** 1018
          </td>
          <td class="white">
            784 939 688
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
