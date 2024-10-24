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
        var dict = {
            "sort_by": "",
            "search": ""
        };

        const urlParams = new URLSearchParams(window.location.search);

        function sortTable() {
            var selectedValue = document.getElementById('sort_by');
            var url = String(window.location.href).split('?')[0];
            url = url.concat("?sort_by=".concat(selectedValue.value));
            var search = urlParams.get('search');
            if (search == null) {
                search = "";
            }
            url = url.concat("&search=".concat(search));
            window.location.href = url;
        }

        function search() {
            var selectedValue = document.getElementById('search');
            var url = String(window.location.href).split('?')[0];
            url = url.concat("?search=".concat(selectedValue.value));
            var sort_by = urlParams.get('sort_by');
            if (sort_by == null) {
                sort_by = "";
            }
            url = url.concat("&sort_by=".concat(sort_by));
            window.location.href = url;
        }
  </script>
</head>

<body>

<?php
$onit = "Compte";
include("../include/po_navbar.inc.php"); // Navbar
?>

  <div class="Compte_tableau">
      <div class="search">
            <form onchange="search()">
                <input type="text" name="search" id="search" <?php if(isset($_GET["search"])) { echo "placeholder=\"".$_GET["search"]."\""; } else { echo "placeholder=\"Rechercher un compte\""; } ?>>
                <button type="submit">Rechercher</button>
            </form>
      </div>
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
      if (isset($_GET["search"])) {
          $search = "where raison_social like '%" . $_GET["search"] . "%' ";
      } else {
          $search = "";
      }
      $req = $cnx->query("SELECT * FROM compte ".$search.$tri);
      echo "Nombre de lignes : ".$req->rowCount();
      ?>

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
