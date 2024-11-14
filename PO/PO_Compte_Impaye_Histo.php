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
            const select = document.getElementById('month_by');
            const selectedValue = select.value;
            // Redirige vers la même page avec le paramètre de tri
            window.location.href = `?month_by=${selectedValue}`;
        }
    </script>

</head>
<body>
<?php
$onit = "Impaye";
include("../include/User_po_navbar.inc.php"); // Navbar
?>
<div class="mini_navbar">
    <a class="mini_link" href="PO_Compte_Impaye_tableau.php">Tableau</a>
    <div class="mini_onit">Histogramme</div>
    <a class="mini_link" href="PO_Compte_Impaye_Circulaire.php">Circulaire</a>
</div>
<div class="canva">
    <canvas id="mixedChart" style="width:100%;max-width:1200px;background-color: rgb(252, 248, 244);"></canvas>
</div>

<div class="sorting">
    Trier par :
    <select name="month_by" id="month_by" onChange="sortTable()">
        <option value="" disabled selected><?php
            if (isset($_GET["month_by"])) {
                $tri = $_GET["month_by"];
                echo match ($tri) {
                    "4month" => "4 months",
                    "6month" => "6 months",
                    "12month" => "12 months",
                    default => "$tri months",
                };
            } else {
                echo "6 months";
                $tri = "6";
            }

            ?></option>
        <option value="4">4 months</option>
        <option value="6">6 months</option>
        <option value="12">12 months</option>
    </select>
</div>

<?php
global $cnx;
include("../include/connexion.inc.php");

function addMonth($year,$month,$nb){
    //Ajoute $nb aux mois et 1 à l'année si elle est passé en ajoutant le mois
    for ($i=1; $i <= $nb; $i++) {
        if (($month + 1) > 12) {
            $month = 1;
            $year++;
        } else {
            $month += 1;
        }
    }
    return [$month,$year]; //return un tableau car impossible de renvoyer plusieurs valeurs
}

function getMonthMin($year,$month,$i){
    //Permet d'avoir le premier mois de la borne
    //$i permet de savoir l'intervalle entre les 2 (6 mois, 12 mois, etc...)
    for ($j = 0; $j < $i; $j++){
        if (($month - 1) <= 0) {
            $month = 12;
            $year--;
        } else {
            $month -= 1;
        }
    }
    return [$month,$year];
}

function monthToString($month){
    //Return le mois en string au lieu de son numero
    $months = ["Jan","Feb","March","Avr","May","June","July","Aout","Sep","Oct","Nov","Dec"];
    return $months[$month-1];
}

//permet d'avoir le derniers mois enregistrer dans la bdd
$sql = "Select max(impayé.date_impaye) FROM bank.impayé;";
$datemax = $cnx->query($sql)->fetch();

//La partie qui permet d'avoir les données de départ pour commencer
//L'initialisation en gros
$yearmax = substr($datemax[0],0,4);
$yearmin = $yearmax;
$monthend = substr($datemax[0],5,2);
$monthmin = $monthend;
$inter = (int)$tri;
$tmp = getMonthMin($yearmax,$monthend,$inter-1);
$monthmin = $tmp[0];
$yearmin = $tmp[1];
$year = $yearmin;
$day = 1;
$hour = 0;
$min = 0;
$sec = 0;

//Début du traitement des infos

//Initialisation d'un tableau qui contiendra les num siren et leur valeurs associé le tout associer à un mois (1 les premiers, 2 le deuxième, etc...)
$annee = [];
for ($i = 1; $i <= $inter; $i++) {
    $annee[$i] = [];
}

//On initialise un tableau qui gardera en mémoire les différents siren sur les derniers mois
$num_siren = array();

//traitement
for ($m = 1; $m != $inter+1; $m++){
    //permet la borne entre le premiers mois et le suivant pour la requête sql
    $tmp2 = addMonth($year,$monthmin,1);

    $month = $tmp2[0];
    $year = $tmp2[1];

    //requête sql (utilisant make_timestamp pour pourvoir faire un between en utilisant des type timestamp)
    $numsiren = $_SESSION['num_siren'];
    $sql = "SELECT * FROM bank.impayé WHERE date_impaye BETWEEN make_timestamp($yearmin,$monthmin,$day,$hour,$min,$sec) AND make_timestamp($year,$month,$day,$hour,$min,$sec) AND num_siren='$numsiren' ORDER BY date_impaye;";
    $req = $cnx->query($sql);
    $montant = 0;
    while ($row = $req->fetch(PDO::FETCH_OBJ)) {
        $siren = $row->num_siren;
        if (!in_array($siren,$annee[$m])){
            $annee[$m][$siren] = 0;
        }
        $annee[$m][$siren] += $row->montant; //Ajoute le montant
        //Prend le siren associer à ce montant
        if (!in_array($siren,$num_siren)) {array_push($num_siren,$siren);} //Ajoute le siren dans liste des sirens (si il y est pas déjà)
    }
    // On augmente la borne de 1 mois
    $monthmin = $month;
    $yearmin = $year;
}
?>
<script>
    const ctx = document.getElementById('mixedChart').getContext('2d');

    const mixedChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [<?php for ($m = 1; $m != $inter+1; $m++){
                if ($m != $inter){
                    echo '"'.monthToString(addmonth(getMonthMin($year,$monthend,$inter)[1],getMonthMin($year,$monthend,$inter)[0],$m)[0])." ".addmonth(getMonthMin($year,$monthend,$inter)[1],getMonthMin($year,$monthend,$inter)[0],$m)[1].'",';
                } else {
                    echo '"'.monthToString(addmonth(getMonthMin($year,$monthend,$inter)[1],getMonthMin($year,$monthend,$inter)[0],$m)[0])." ".addmonth(getMonthMin($year,$monthend,$inter)[1],getMonthMin($year,$monthend,$inter)[0],$m)[1].'"';    }
            }?>],
            datasets: [
                //On va faire les données dans l'histogramme
                <?php
                //On arrive à la parti où on copie le js en mettant nos valeurs des différentes listes
                $color = 7;
                $compte = 0;
                //foreach pour faire les num sirens enregistrer une seule fois
                foreach ($num_siren as $siren){
                    $compte++;
                    echo "{";
                    echo "label: '$siren',\n";
                    echo "data: [";
                    //la boucle for qui permet de crée le dataset avec les montant des différents mois associer au siren
                    for ($m = 1; $m != $inter+1; $m++){
                        $data = 0;
                        if (!empty($annee[$m][$siren])) {
                            $data = $annee[$m][$siren];
                        }
                        if ($m == $inter){
                            echo $data;
                        } else {
                            echo $data.", ";
                        }

                    }
                    echo "],\n";
                    echo "backgroundColor: 'rgba(255, $color, $color, 0.5)',\n";
                    $color += 20;
                    echo "font: { size: 14 }";
                    if ($compte == count($num_siren)){
                        echo "}";
                    } else {
                        echo "},";
                    }

                }

                ?>
                ,{
                <?php
                echo "label: 'Montant Total',\n";
                echo "type: 'line',\n";
                echo "data: [";
                for ($m = 1; $m != $inter+1; $m++){
                    $data = 0;
                    if (!empty($annee[$m])){
                        foreach($annee[$m] as $montant){
                            $data += $montant;
                        }
                    }
                    if ($m == $inter){
                        echo $data;
                    } else {
                        echo $data.", ";
                    }
                }
                echo "],\n";
                echo "fill: false,\n";
                echo "borderColor: 'black',\n";
                echo "tension: 0.1,\n";
                echo "font: 14},";
                ?>
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

</html>
