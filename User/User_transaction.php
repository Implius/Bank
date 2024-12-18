<?php
// Connexion à la base de données et vérification de l'authentification

global $cnx;
include("../include/connexion.inc.php");
include('../include/verifyconnexion_user.inc.php');
echo "<div class=\"backArrow\" onclick=\"window.location.href='User_remise.php'\"></div><nav>";
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="po_compte.css">
    <title>JeFinance</title>
    <script>
        function sortTable() {
            const select = document.getElementById('sort_by');
            const selectedValue = select.value;
            // Redirige vers la même page avec le paramètre de tri
            window.location.href = `?sort_by=${selectedValue}`;
        }
    </script>
    <script>
        $(function(){ // Affichage des détails de la transaction
            $(".fold-table tr.view").on("click", function(){
                $(this).toggleClass("open").next(".fold").toggleClass("open");
            });
        });
    </script>
    <style>
        @import url('https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css');
        body { padding: .2em 2em; }
        /* Style du tableau */
        table {
            width: 100%;
            th { text-align: left; border-bottom: 1px solid #ccc;}
            th, td { padding: .4em; }
        }
        table.fold-table {
            > tbody {
                > tr.view {
                    td, th {cursor: pointer;}
                    td:first-child,
                    th:first-child {
                        position: relative;
                        padding-left:20px;
                        &:before {
                            position: absolute;
                            top:50%; left:5px;
                            width: 9px; height: 16px;
                            margin-top: -8px;
                            font: 16px fontawesome;
                            color: #999;
                            content: "\f0d7";
                            transition: all 0.3s ease;
                        }
                    }
                    &:nth-child(2n-1) { background: #eee; }
                    &:nth-child(4n-1) { background: #cccccc; }
                    &:hover { background: #CDCDCD44; }
                    &.open {
                        background: #4e4e4e46;
                        color: white;
                        td:first-child, th:first-child {
                            &:before {
                                transform: rotate(-180deg);
                                color: #333;
                            }
                        }
                    }
                }
                > tr.fold {
                    display: none;
                    &.open { display:table-row;}
                }
            }
        }
        .fold-content {
            padding: .5em;
            h3 { margin-top:0; }
            > table {
                border: 2px solid #ccc;
                > tbody {
                    tr:nth-child(even) {
                        background: #eee;
                    }
                    tr:nth-child(odd) {
                        background: #b3b3b3;
                    }
                }
            }
        }
    </style>
</head>
<body>
<?php
$onit = "Remise"; // Page actuelle
include("../include/User_navbar.inc.php"); // Navbar
?>
<div class="Compte_tableau">
    <table class="fold-table">
        <thead>
        <tr>
            <th class="table-blue">
                Remise N°
            </th>
            <th class="table-darkblue">
                Date
            </th>
            <th class="table-darkblue">
                Num Carte
            </th>
            <th class="table-blue">
                Objet
            </th>
            <th class="table-darkblue">
                N° Auto
            </th>
            <th class="table-blue">
                Partie
            </th>
            <th class="table-darkblue">
                N° SIREN Partie
            </th>
            <th class="table-blue">
                Montant
            </th>
        </tr>
        </thead>
        <?php
        // Requête pour afficher les transactions de la remise cliquée
        $remise = $_GET["id_remise"];
        $sql = "SELECT * FROM transaction WHERE id_remise='".$remise."';";
        $req = $cnx->query($sql);
        while ($donnees = $req->fetch(PDO::FETCH_OBJ)) {
        ?>
        <tbody>
        <tr class="view">
            <td>
                <?php echo $donnees->id_remise; ?>
            </td>
            <td>
                <?php echo $donnees->date_trans; ?>
            </td>
            <td>
                <?php $carte = $donnees->num_carte;
                $ano = "**** **** **** ".substr($carte,-4,4); // Affiche les 4 derniers chiffres de la carte
                echo $ano;?>
            </td>
            <td>
                <?php echo $donnees->libelle; ?>
            </td>
            <td>
                <?php echo $donnees->num_autorisation; ?>
            </td>
            <td>
                <?php echo $donnees->raison_autre_parti; ?>
            </td>
            <td>
                <?php echo $donnees->siren_autre_parti; ?>
            </td>
            <td>
                <?php
                // Requête pour afficher le montant de la transaction
                $join_siren = $cnx->query("SELECT compte.num_siren FROM compte join remise on compte.num_siren = remise.num_siren join transaction on remise.id_remise = transaction.id_remise WHERE transaction.id_remise = '$donnees->id_remise';");
                $siren = $join_siren->fetch(PDO::FETCH_OBJ)->num_siren;
                $join_query = $cnx->query("select compte.devise from compte join remise on compte.num_siren = remise.num_siren WHERE remise.num_siren='$siren';");
                $devise = $join_query->fetch(PDO::FETCH_OBJ)->devise;
                switch ($devise) { // Affiche le symbole de la devise
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
                $trans = $donnees->id_trans;
                $sql = "SELECT * FROM detail WHERE id_trans='".$trans."';";
                $reqD = $cnx->query($sql);
                $montant = 0;
                while ($ligne = $reqD->fetch(PDO::FETCH_OBJ)) { // Affiche le montant de la transaction
                    $montant += $ligne->montant;
                }
                if ($donnees->sens == '-') {
                    echo "<p class=\"red\">";
                    echo "- ".$montant.$devise; // montant
                    echo "</p>";
                }
                else {
                    echo $montant.$devise; // montant
                }?>
            </td>
        </tr>
        <tr class="fold">
            <?php //requete pour les détails de la transactions cliqué
            $sql = "SELECT * FROM detail WHERE id_trans = '".$donnees->id_trans."';";
            $reqT = $cnx->query($sql);
            ?>
            <td colspan="7">
                <div class="fold-content">
                    <h3>Details</h3>
                    <table>
                        <thead>
                        <tr>
                            <th>Objet</th><th>Montant</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php while ($donneesT = $reqT->fetch(PDO::FETCH_OBJ)) { // Affiche les détails de la transaction
                            ?>
                            <tr>
                                <td><?php echo $donneesT->libelle; ?></td>
                                <td><?php
                                    if ($donneesT->sens == '+') { // Affiche le montant en vert si positif
                                        echo $donneesT->montant.$devise;
                                    }
                                    if ($donneesT->sens == '-') { // Affiche le montant en rouge si négatif
                                        echo "<p class=\"red\">";
                                        echo '- '.$donneesT->montant.$devise;
                                    } ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </td>
        </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>