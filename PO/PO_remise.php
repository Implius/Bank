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
            <th class="table-darkblue">
                Date
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
                    $join_query = $cnx->query("select raison_social from compte join remise on compte.num_siren = remise.num_siren;");

                    echo $join_query->fetch(PDO::FETCH_OBJ)->raison_social; // beneficiaire ?>
                </td>
                <td>
                    <?php
                    $join_query = $cnx->query("select compte.num_siren from compte join remise on compte.num_siren = remise.num_siren;");

                    echo $join_query->fetch(PDO::FETCH_OBJ)->num_siren; // num siren beneficiaire ?>
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

                    if ($ligne->sens == '-') {
                        echo "<p class=\"red\">";
                        echo "- ".$ligne->montant.$devise; // montant
                        echo "</p>";
                    }
                    else {
                        echo $ligne->montant.$devise; // montant
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