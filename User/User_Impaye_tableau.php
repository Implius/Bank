<?php
global $cnx;
include("../include/connexion.inc.php");
include('../include/verifyconnexion_user.inc.php');
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
    <link rel="stylesheet" href="po_compte.css">
    <title>JeFinance</title>
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
    <style>
        .button_tel{
            margin-top: 30px;
            display: flex;
            justify-content: center;
        }
        .button_tel button{
            padding: 25px 25px;
            border-radius: 15px;
            background-color: #c1c1c1;
        }
    </style>
</head>
<body>
<?php
$onit = "Impaye";
include("../include/User_navbar.inc.php"); // Navbar
if (!isset($_GET['search']) || $_GET['search'] == "") {
    $search = "";
} else {
    $search = " AND id_impaye LIKE '%".$_GET['search']."%' ";
}
?>
<div class="mini_navbar">
    <div class="mini_onit">Tableau</div>
    <a class="mini_link" href="User_Impaye_Histo.php">Histogramme</a>
    <a class="mini_link" href="User_Impaye_Circulaire.php">Circulaire</a>
</div>
<div class="Compte_tableau" style="margin-top: 100px">

    <div class="sorting">
        <form action="User_Impaye_tableau.php" method="get">
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
                    echo match ($tri) {
                        "date_plusrecent" => "Date (plus récent)",
                        "date_plusancient" => "Date (plus ancient)",
                        "numero_impaye" => "Numéro d'impayé",
                        "montant" => "Montant d'impayé",
                        default => "Aucun",
                    };
                } else {
                    echo "Aucun";
                }

                ?></option>
            <option value="date_plusrecent">Date (plus récent)</option>
            <option value="date_plusancient">Date (plus ancient)</option>
            <option value="numero_impaye">Numéro d'impayé</option>
            <option value="montant">Montant impayé</option>
        </select>
    </div>

    <?php
    if (isset($_GET["sort_by"])) {
        $tri = match ($_GET["sort_by"]) {
            "date_plusrecent" => " ORDER BY date_impaye DESC",
            "date_plusancient" => " ORDER BY date_impaye ASC",
            "numero_impaye" => " ORDER BY id_impaye",
            "Numero_SIREN" => " ORDER BY num_siren",
            "montant" => " ORDER BY montant DESC",
            default => "",
        };
    } else {
        $tri = "";
    }
    if (isset($_SESSION['NumSiren'])) {
        $siren = $_SESSION['NumSiren'];
    }
    $req = $cnx->query("SELECT * FROM bank.impaye WHERE num_siren='$siren'".$search.$tri);
    $req_total = $cnx->query("SELECT sum(montant) as total FROM bank.impaye WHERE num_siren='$siren';");
    echo "Montant total des impayés : ".$req_total->fetch(PDO::FETCH_OBJ)->total."<br>";
    echo "Nombre de lignes : ".$req->rowCount();
    ?>

    <table class="tableau" id="table_imp">
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
                N° SIREN
            </th>
            <th class="table-blue">
                Raison sociale
            </th>
            <th class="table-darkblue">
                Objet
            </th>
            <th class="table-blue">
                Motif
            </th>
            <th class="table-darkblue">
                Montant
            </th>
        </tr>
        </thead>
        <tbody>
        <?php
        while ($ligne = $req->fetch(PDO::FETCH_OBJ)) {
            ?>
            <tr class="line">
                <td>
                    <?php echo $ligne->id_impaye; // id impaye ?>
                </td>
                <td>
                    <?php echo $ligne->date_impaye; // date ?>
                </td>
                <td>
                    <?php echo $ligne->raison_autre_parti; // autre parti ?>
                </td>
                <td>
                    <?php echo $ligne->num_siren; // num siren ?>
                </td>
                <td>
                    <?php
                    $join_query = $cnx->query("select compte.raison_social from compte where compte.num_siren = '".$ligne->num_siren."';");
                    $raison_social = $join_query->fetch(PDO::FETCH_OBJ);
                    echo $raison_social->raison_social; // raison sociale
                    ?>
                </td>
                <td>
                    <?php echo $ligne->libelle; // objet ?>
                </td>
                <td>
                    <?php echo $ligne->code_motif; // motif ?>
                </td>
                <td class="montant">
                    <?php echo $ligne->montant; // montant ?>
                </td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>


</div>
<div class="button_tel">
    <button id="btn_csv">Exporter format CSV</button>
    <button id="btn_pdf">Exporter format PDF</button>
    <button id="btn_xls">Exporter format XLS</button>
</div>
<script src="../script.js">
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
        exportTableToExcel('table_imp');
    });
</script>
<script>
    document.getElementById('btn_pdf').addEventListener('click', () => {
        const element = document.getElementById('table_imp');

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
</body>
</html>
