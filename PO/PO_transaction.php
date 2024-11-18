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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function sortTable() {
            const select = document.getElementById('sort_by');
            const selectedValue = select.value;
            // Redirige vers la même page avec le paramètre de tri
            window.location.href = `?sort_by=${selectedValue}`;
        }
    </script>
    <script>
        $(function(){
            $(".fold-table tr.view").on("click", function(){
                $(this).toggleClass("open").next(".fold").toggleClass("open");
            });
        });
    </script>
    <style>
        @import url('https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css');

        * { box-sizing: border-box; }
        body { padding: .2em 2em; }

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
                            transition: all .3s ease;
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
                    &.open { display:table-row; }
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
$onit = "Remise";
include("../include/po_navbar_w_return.inc.php"); // Navbar
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
        global $cnx;
        include("../include/connexion.inc.php");

        $remise = $_GET["id_remise"];
        $sql = "SELECT * FROM bank.transaction WHERE id_remise='".$remise."';";
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
                $ano = "**** **** **** ".substr($carte,-4,4);
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
                $join_siren = $cnx->query("SELECT compte.num_siren FROM compte join remise on compte.num_siren = remise.num_siren join transaction on remise.id_remise = transaction.id_remise WHERE transaction.id_remise = '$donnees->id_remise';");
                $siren = $join_siren->fetch(PDO::FETCH_OBJ)->num_siren;
                $join_query = $cnx->query("select compte.devise from compte join remise on compte.num_siren = remise.num_siren WHERE remise.num_siren='$siren';");
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
                $trans = $donnees->id_trans;
                $sql = "SELECT * FROM bank.detail WHERE id_trans='".$trans."';";
                $reqD = $cnx->query($sql);
                $montant = 0;
                while ($ligne = $reqD->fetch(PDO::FETCH_OBJ)) {
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
            $sql = "SELECT * FROM bank.detail WHERE id_trans = '".$donnees->id_trans."';";
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
                        <?php while ($donneesT = $reqT->fetch(PDO::FETCH_OBJ)) { ?>
                        <tr>
                                <td><?php echo $donneesT->libelle; ?></td>
                                <td><?php
                                    if ($donneesT->sens == '+') {echo $donneesT->montant.$devise;}
                                    if ($donneesT->sens == '-') {
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
