<!doctype html>
<html lang="en">

<?php
global$cnx;
include('../include/connexion.inc.php');
include('../include/verifyconnexion.inc.php');
?>

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
      <?php
        $req = $cnx->query("SELECT * FROM compte");
        while ($ligne = $req->fetch(PDO::FETCH_OBJ)) {
            ?>
            <tr>
                <td class="white">
                    <?php echo $ligne->raison_social; ?>
                </td>
                <td class="grey">
                    <?php echo '************' . substr($ligne->num_compte, -4); ?>
                </td>
                <td class="white">
                    <?php echo $ligne->num_siren; ?>
                </td>
                <td class="grey montant">
                    <?php echo $ligne->tresorerie; ?>
                </td>
            </tr>
            <?php
        }
      ?>
      </tbody>
    </table>

  </div>
</body>

</html>
