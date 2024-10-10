<?php
global$cnx;
include("../include/connexion.inc.php");
?>
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
          <th class="table-green">
            Date
          </th>
          <th class="table-red">
            Objet
          </th>
          <th class="table-lightpurple">
            Bénéficiaire
          </th>
          <th class="table-purple">
            N° SIREN Bénéficiaire
          </th>
          <th class="table-darkblue">
            Montant
          </th>
        </tr>
      </thead>
      <tbody>

      <?php
      $req = $cnx->query("SELECT * FROM remise");
      while ($ligne = $req->fetch(PDO::FETCH_OBJ)) {
          ?>
          <tr>
            <td class="white">
                <?php echo $ligne->id_remise; // id remise ?>
            </td>
            <td class="white">
                <?php echo $ligne->date_remise; // date remise ?>
            </td>
            <td class="white">
                <?php echo $ligne->libelle; // objet libelle  ?>
            </td>
            <td class="white">
                <?php
                $join_query = $cnx->query("select raison_social from compte join remise on compte.num_siren = remise.num_siren;");

                echo $join_query->fetch(PDO::FETCH_OBJ)->raison_social; // beneficiaire ?>
            </td>
            <td class="white">
                <?php
                $join_query = $cnx->query("select compte.num_siren from compte join remise on compte.num_siren = remise.num_siren;");

                echo $join_query->fetch(PDO::FETCH_OBJ)->num_siren; // num siren beneficiaire ?>
            </td>
            <td class="white montant">
                <?php
                if ($ligne->sens == '-') {
                    echo "<p class=\"red\">";
                }
                    echo $ligne->montant; // montant
                if ($ligne->sens == '-') {
                    echo "</p>";
                }
                ?>
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