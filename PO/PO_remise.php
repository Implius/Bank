<?php
global$cnx;
include("../include/connexion.inc.php");
include('../include/verifyconnexion.inc.php');
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
$onit = "Remise";
include("../include/po_navbar.inc.php"); // Navbar
if (!isset($_GET['search']) || $_GET['search'] == "") {
    $search = "";
} else {
    $search = " WHERE id_remise LIKE '%".$_GET['search']."%' ";
}
?>

<div class="Compte_tableau">

    <div class="sorting">
        <form action="PO_remise.php" method="get">
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
                        "numero_remise" => "Numéro de remise",
                        "Numero_SIREN" => "Numéro SIREN",
                        default => "Aucun",
                    };
                } else {
                    echo "Aucun";
                }

                ?></option>
            <option value="date_plusrecent">Date (plus récent)</option>
            <option value="date_plusancient">Date (plus ancient)</option>
            <option value="numero_remise">Numéro de remise</option>
            <option value="Numero_SIREN">Numéro SIREN</option>
        </select>
    </div>

    <?php
    if (isset($_GET["sort_by"])) {
        $tri = match ($_GET["sort_by"]) {
            "date_plusrecent" => " ORDER BY date_remise DESC",
            "date_plusancient" => " ORDER BY date_remise ASC",
            "numero_remise" => " ORDER BY id_remise",
            "Numero_SIREN" => " ORDER BY num_siren",
            default => "",
        };
    } else {
        $tri = "";
    }
    $req = $cnx->query("SELECT * FROM remise".$search.$tri);
    echo "<p class='nb_lignes'>Nombre de comptes : ".$req->rowCount()."</p>";
    ?>

    <table class="tableau" id="table">
        <thead>
        <tr>
            <th class="table-blue">
                Remise N°
            </th>
            <th class="table-darkblue">
                Date
            </th>
            <th class="table-blue">
                Objet
            </th>
            <th class="table-darkblue">
                Raison sociale <!--Pas bénéficiaire car on parle juste d'une entreprise y'a pas de bénéficiaire en soit-->
            </th>
            <th class="table-blue">
                N° SIREN 
                <!--Donc pas bénéficaire pas bénéficiare en bas. ça évite les problème de confusion -->
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
            <tr onclick="document.location = 'PO_transaction.php?id_remise=<?php echo $ligne->id_remise; ?>';" class="line">
                <td>
                    <?php echo $ligne->id_remise; // id remise ?>
                </td>
                <td>
                    <?php echo $ligne->date_remise; // date remise ?>
                </td>
                <td>
                    <?php echo $ligne->libelle; // objet libelle  ?>
                </td>
                <td>
                    <?php
                    $join_query = $cnx->query("select raison_social from compte where num_siren = '".$ligne->num_siren."';");

                    echo $join_query->fetch(PDO::FETCH_OBJ)->raison_social; // beneficiaire ?>
                </td>
                <td>
                    <?php
                    echo $ligne->num_siren;
                    ?>
                </td>
                <td class="montant">
                    <?php
                    $join_query = $cnx->query("select compte.devise from compte join remise on compte.num_siren = remise.num_siren;");
                    $devise = $join_query->fetch(PDO::FETCH_OBJ)->devise;
                    switch ($devise) {
                        case "EUR":
                            $devise = " €";
                            break;
                        case "USD":
                            $devise = " $";
                            break;
                        case "GBP":
                            $devise = " £";
                            break;
                        default:
                            $devise = " ?";
                            break;
                    }

                    //permet de faire du montant une somme des montant des transactions
                    //Permet d'éviter tout problème de cohérence avec la bdd
                    $remise = $ligne->id_remise;
                    $sql = "SELECT * FROM bank.transaction WHERE id_remise='".$remise."';";
                    $req2 = $cnx->query($sql);
                    $montant = 0;
                    while ($donnees = $req2->fetch(PDO::FETCH_OBJ)) {
                        $montant += $donnees->montant;
                    }

                    if ($ligne->sens == '-') {
                        echo "<p class=\"red\">";
                        echo "- ".$montant.$devise; // montant
                        echo "</p>";
                    }
                    else {
                        echo $montant.$devise; // montant
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

<div class="button_tel">
    <button id="btn_csv">Exporter format CSV</button>
    <button id="btn_pdf">Exporter format PDF</button>
    <button id="btn_xls">Exporter format XLS</button>
</div>
<script src="../script_remise.js">
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
</body>

</html>
