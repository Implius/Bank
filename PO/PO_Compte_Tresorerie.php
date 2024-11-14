<?php
global $cnx;
include("../include/connexion.inc.php");
include('../include/verifyconnexion.inc.php');
if (isset($_GET['num_siren'])) {
    $_SESSION["num_siren"] = $_GET["num_siren"];
}
?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <link rel="stylesheet" href="po_compte.css">
        <title>JeFinance</title>
    </head>
<body>
<?php
$onit = "Tresorerie";
include("../include/User_po_navbar.inc.php"); // Navbar
?>
    <div class="Compte_title_sup">
        <div class="Compte_title">
            <h1 class="tresorerie">Nom de l'entreprise</h1>
            <h2 class="subtitle1">Num SIREN: <?php echo $_SESSION["num_siren"]?></h2>
            <h2 class="subtitle2">Num Compte: 2222</h2>
            <h2 class="green">Total argent</h2>
        </div>
        <div>
        <form action="PO_Compte_Tresorerie.php" method="POST">
            <button name="supprimer" class="buttonsup" type="submit">Supprimer</button>
        </form>
        <?php
        if (isset($_POST["supprimer"])) {

            // Récupération de l'ID de demande suivant
            $nextid = $cnx->query("SELECT max(id_demande) as nextid from demande_compte;")->fetch(PDO::FETCH_OBJ)->nextid;
            $nextid = (int)$nextid + 2;
            $nextid = (string)$nextid;
            $num_siren = $_SESSION["num_siren"];
            $check = $cnx->prepare("SELECT COUNT(*) as count FROM suppression WHERE num_siren = :num_siren");
            $check->bindParam(':num_siren', $_SESSION["num_siren"]);
            $check->execute();
            $count = $check->fetch(PDO::FETCH_OBJ)->count;

            // Si la requête existe déjà
            if ($count > 0) {
                echo "Cette requête a déjà été faite.";
            } else {
                // Récupération du nom du compte en utilisant une requête préparée
                $stmt = $cnx->prepare("SELECT raison_social FROM compte WHERE num_siren = :num_siren");
                $stmt->bindParam(':num_siren', $_SESSION["num_siren"]);
                $stmt->execute();
                $name = $stmt->fetch(PDO::FETCH_OBJ)->raison_social;

                $date_demande = date('Y-m-d');

                // Insertion dans la table `demande_compte`
                $req = $cnx->prepare("INSERT INTO demande_compte (id_demande, date_demande, libelle_demande) VALUES (:nextid, :date_demande, :name)");
                $req->bindParam(':nextid', $nextid, PDO::PARAM_STR);
                $req->bindParam(':date_demande', $date_demande, PDO::PARAM_STR);
                $req->bindParam(':name', $name, PDO::PARAM_STR);
                $req->execute();

                // Insertion dans la table `suppression`
                $req = $cnx->prepare("INSERT INTO suppression (id_demande, num_siren) VALUES (:nextid, :num_siren)");
                $req->bindParam(':nextid', $nextid, PDO::PARAM_STR);
                $req->bindParam(':num_siren', $_SESSION["num_siren"], PDO::PARAM_STR);
                $req->execute();

                // Nettoyage du formulaire
                unset($_POST["supprimer"]);

                // Redirection
                header("Location: PO_Compte_Tresorerie.php?deleted=1");

            }
        }
        ?>
        </div>
    </div>
    <div class="canva">
        <canvas id="mixedChart" style="width:100%;max-width:1200px;background-color: rgb(252, 248, 244);"></canvas>
    </div>
    <script>
        const ctx = document.getElementById('mixedChart').getContext('2d');
        const mixedChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                datasets: [{
                    label: 'Nom client 1',
                    data: [300, 200, 400, 500, 150, 100, 50],
                    backgroundColor: 'rgba(255,7,7,0.5)',
                    font: {
                        size: 14
                    }
                },
                    {
                        label: 'Nom client 2',
                        data: [200, 200, 200, 200, 150, 100, 50],
                        backgroundColor: 'rgba(7, 201, 255, 0.5)',
                        font: {
                            size: 14
                        }
                    },
                    {
                        label: 'Nom client 3',
                        data: [400, 250, 600, 150, 350, 100, 85], // Ce dataset est pour garder la structure
                        backgroundColor: 'rgba(181, 255, 7, 0.5)',
                        font: {
                            size: 14
                        }
                    },
                    {
                        label: 'Montant Total Dû',
                        type: 'line',
                        data: [400 + 200 + 300, 250 + 200 + 200, 600 + 200 + 400, 500 + 200 + 150, 350 + 150 + 150, 100 +
                        100 + 100, 85 + 50 + 50
                        ],
                        fill: false,
                        borderColor: 'black',
                        tension: 0.1,
                        font: 14
                    },
                ],
            },
            options: {
                responsive: false,
                scales: {
                    x: {
                        stacked: true,
                        ticks: {
                            font: {
                                size: 25 // Change the font size for x-axis labels
                            }
                        }
                    },
                    y: {
                        stacked: true,
                        ticks: {
                            font: {
                                size: 25 // Change the font size for x-axis labels
                            }
                        }
                    },
                },
                plugins: {
                    tooltip: {
                        bodyFont: {
                            size: 25 // Change the font size for tooltip body
                        },
                        titleFont: {
                            size: 25 // Change the font size for tooltip title
                        },
                    },
                    legend: {
                        labels: {
                            font: {
                                size: 25 // Change the font size for legend labels
                            }
                        }
                    }
                }
            },
        });
    </script>
</body>
