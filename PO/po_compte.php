<!doctype html>
<html lang="en">

<?php
global$cnx;
include('../include/connexion.inc.php');
include('../include/verifyconnexion.inc.php');
if (isset($_SESSION['num_siren'])) {
    unset($_SESSION['num_siren']);
}
?>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="PO.css">
  <title>JeFinance</title>
    <script>
        function sortTable() {
            const select = document.getElementById('sort_by');
            const selectedValue = document.getElementById('sort_by');
            // Redirige vers la même page avec le paramètre de tri
            window.location.href = `?sort_by=${selectedValue.value}`;
        }
  </script>
</head>

<body>

<?php
$onit = "Compte";
include("../include/po_navbar.inc.php"); // Navbar
?>

  <div class="Compte_tableau">
    <div class="sorting">
      Trier par :
      <select name="sort_by" id="sort_by" onchange="sortTable()">
          <option value="" disabled selected><?php
              if (isset($_GET["sort_by"])) {
                  $tri = $_GET["sort_by"];
                  echo match ($_GET["sort_by"]) {
                    "tresorerie" => "Montant du compte",
                    "nb_remises" => "Nombre de remises",
                    "nb_impayes" => "Montant des impayés",
                    "num_siren" => "Numéro SIREN",
                    default => "Aucun",
                  };
            } else {
                  echo "Aucun";
            }
            ?></option>
        <option value="tresorerie">Montant du compte</option>
        <option value="nb_remises">Nombre de remises</option>
        <option value="nb_impayes">Montant des impayés</option>
        <option value="num_siren">Numéro SIREN</option>
      </select>
    </div>

    <table class="tableau">
      <thead>
        <tr>
          <th class="table-blue">
            Raison Social
          </th>
          <th class="table-darkblue">
            N° de compte
          </th>
          <th class="table-blue">
            N° SIREN
          </th>
          <th class="table-darkblue">
            Montant du compte
          </th>
        </tr>
      </thead>
      <tbody>
      <?php
      if (isset($_GET["sort_by"])) {
          $tri = match ($_GET["sort_by"]) {
                "tresorerie" => "ORDER BY tresorerie DESC",
                "nb_remises" => "ORDER BY (select count(*) from remise where remise.num_siren = compte.num_siren) DESC",
                "nb_impayes" => "ORDER BY (select count(*) from impaye where impaye.num_siren = compte.num_siren) DESC",
                "num_siren" => "ORDER BY num_siren",
          };
      } else {
          $tri = "";
      }
        $req = $cnx->query("SELECT * FROM compte ".$tri);
        while ($ligne = $req->fetch(PDO::FETCH_OBJ)) {
            ?>
            <tr onclick="document.location = 'PO_Compte_Tresorerie.php?num_siren=<?php echo $ligne->num_siren; ?>';">
                <td>
                    <?php echo $ligne->raison_social; ?>
                </td>
                <td>
                    <?php echo '************' . substr($ligne->num_compte, -4); ?>
                </td>
                <td>
                    <?php echo $ligne->num_siren; ?>
                </td>
                <td class="montant">
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
