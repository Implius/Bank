<?php
global $cnx;
include("../include/connexion.inc.php");
include('../include/verifyconnexion.inc.php');
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="po_compte.css">
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
$onit = "Remise";
include("../include/User_po_navbar.inc.php"); // Navbar
?>
<div class="Compte_tableau">
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
        $req = $cnx->query("SELECT * FROM remise WHERE num_siren='".$_SESSION["num_siren"]."'".$tri);
        while ($ligne = $req->fetch(PDO::FETCH_OBJ)) {
            ?>
            <tr onclick="document.location = 'PO_Compte_Transaction.php?id_remise=<?php echo $ligne->id_remise; ?>';">
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
</body>
</html>
