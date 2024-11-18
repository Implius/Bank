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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
    <script>
        function sortTable() {
            const selectedValue = document.getElementById('sort_by');
            // Si l'url contient un paramètre search
            if (window.location.href.includes("?")) {
                // On redirige vers la même page avec le paramètre sort_by
                if (window.location.href.includes("&sort_by=")) {
                    window.location.href = window.location.href.split("&sort_by=")[0] + "&sort_by=" + selectedValue.value;
                } else {
                    window.location.href = `?search=${window.location.href.split("?search=")[1]}&sort_by=${selectedValue.value}`;
                }
            } else {
                window.location.href = `?sort_by=${selectedValue.value}`;
            }
        }
  </script>
</head>
²
<body>

<?php
$onit = "Compte";
include("../include/po_navbar.inc.php"); // Navbar
if (!isset($_GET['search']) || $_GET['search'] == "") {
    $search = "";
} else {
    $search = "WHERE raison_social LIKE '%".$_GET['search']."%' ";
}
?>

  <div class="Compte_tableau">

      <div class="sorting">
            <form action="po_compte.php" method="get">
                <input type="text" name="search" placeholder="<?php if ($search == "") { echo "Rechercher"; } else { echo $_GET['search']; } ?>">
                <button type="submit"><?php if ($search == "") { echo "Rechercher"; } else { echo "Supprimer"; } ?></button>
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
        $req = $cnx->query("SELECT * FROM compte ".$search.$tri);
      echo "<p class='nb_lignes'>Nombre de comptes : ".$req->rowCount()."</p>";
      ?>

    <table class="tableau" id="table">
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
            <tr onclick="document.location = 'PO_Compte_Tresorerie.php?num_siren=<?php echo $ligne->num_siren; ?>';" class="line">
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

<div class="button_tel">
    <button id="btn_csv">Exporter format CSV</button>
    <button id="btn_pdf">Exporter format PDF</button>
    <button id="btn_xls">Exporter format XLS</button>
</div>

<script src="../script_compte.js">
</script>


<script>
    document.getElementById('btn_pdf').addEventListener('click', () => {
        const element = document.getElementById('table');

        // Obtenir les dimensions avec getBoundingClientRect
        const rect = element.getBoundingClientRect();
        const contentWidth = rect.width;
        const contentHeight = rect.height;

        const opt = {
            margin: 0, //pas de marge
            filename: 'table.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2, width: contentWidth, height: contentHeight+100 }, // Meilleure qualité
            jsPDF: { unit: 'px', format: [contentWidth, contentHeight+100], orientation: 'landscape' }
        };
        // Génération du PDF
        html2pdf().set(opt).from(element).save();
    });
</script>
<script>
    function exportTableToExcel(tableId) {

        // Get the table element using the provided ID
        const table = document.getElementById(tableId);

        // Extract the HTML content of the table
        const html = table.outerHTML;

        // Create a Blob containing the HTML data with Excel MIME type
        const blob = new Blob([html], {type: 'application/vnd.ms-excel'});

        // Create a URL for the Blob
        const url = URL.createObjectURL(blob);

        // Create a temporary anchor element for downloading
        const a = document.createElement('a');
        a.href = url;

        // Set the desired filename for the downloaded file
        a.download = 'table.xls';

        // Simulate a click on the anchor to trigger download
        a.click();

        // Release the URL object to free up resources
        URL.revokeObjectURL(url);
    }

    document.getElementById('btn_xls').addEventListener('click', function() {
        exportTableToExcel('table');
    });
</script>

</html>
