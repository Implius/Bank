<?php
// Connexion à la base de données et vérification de l'authentification

global $cnx;
include("../include/connexion.inc.php");
include('../include/verifyconnexion_user.inc.php');
if (isset($_SESSION['NumSiren'])) {
    $numsiren = $_SESSION["NumSiren"];
}
$date_begin = NULL;
$date_end = NULL;
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
$onit = "Tresorerie"; // Page active
include("../include/User_navbar.inc.php"); // Navbar
?>
<div class="Compte_title_sup">
    <div class="Compte_title">
        <h1 class="tresorerie"><?php
            $stmt = $cnx->prepare("SELECT * FROM compte WHERE num_siren = :num_siren"); // Information sur le compte de l'utilisateur, avec le numéro SIREN
            $stmt->bindParam(':num_siren', $_SESSION["NumSiren"]);
            $stmt->execute();
            $ligne = $stmt->fetch(PDO::FETCH_OBJ);
            $name = $ligne->raison_social;
            $num_compte = $ligne->num_compte;
            $tresorerie = $ligne->tresorerie;
            $devise = $ligne->devise;
            $sens = $ligne->sens;
            echo $name;
            ?></h1>
        <h2 class="subtitle1">Num SIREN: <?php echo $_SESSION["NumSiren"]?></h2>
        <h2 class="subtitle2">Num Compte: <?php echo $num_compte ?></h2>
         <?php
        if ($sens == "-") { // Si la trésorerie est négative, affiche en rouge
            echo "<h2 class=\"titlered\">";
            echo $tresorerie;
            switch ($devise) {
                case "EUR":
                    echo " €";
                    break;
                case "USD":
                    echo " $";
                    break;
                case "GBP":
                    echo " £";
                    break;
                default:
                    echo " ?";
                    break;
            }
            echo "</h2>";
        } else { // Sinon, affiche en vert
            echo "<h2 class=\"green\">";
            echo $tresorerie;
            switch ($devise) {
                case "EUR":
                    echo " €";
                    break;
                case "USD":
                    echo " $";
                    break;
                case "GBP":
                    echo " £";
                    break;
                default:
                    echo " ?";
                    break;
            }
        }
        echo "</h2>";
        ?>
    </div>
</div>
<div class="Compte_histo">
    <div class="sorting">
    Trier par :
    <select name="month_by" id="month_by" onChange="sortTable()">
        <option value="" disabled selected><?php
            if (isset($_GET["month_by"])) {  // Nombre de mois à afficher
                $tri = $_GET["month_by"];
                switch($tri) {
                    case "4month":
                        echo "4 months";
                        break;
                    case "6month":
                        echo "6 months";
                        break;
                    case "12month":
                        echo "12 months";
                        break;
                    default:
                        echo "$tri months";
                        break;
                }
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
</div>
<div class="canva">
    <canvas id="mixedChart" style="width:100%;max-width:1200px;background-color: rgb(252, 248, 244);"></canvas>
</div>

<?php
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
function compareMonth($monthA,$yearA,$monthB,$yearB,$monthmax,$yearmax) : int
{
    $count = 0;
    if ($yearmax < $yearB || ($yearB == $yearmax && $monthmax < $monthB)) {
        $monthB = $monthmax;
    }
    while ($monthA != $monthB) {
        $count++;
        $monthA = addMonth($yearA,$monthA,1)[0];
    }
    return $count + 1 ;
}

if ($date_begin != null){
    $date_begin = (string)$date_begin;
    $year_begin = substr($date_begin, 0, 4);
    $month_begin = substr($date_begin, 5, 2);
    $day_begin = substr($date_begin, 8, 2);
}
if ($date_end != null){
    $date_end = (string)$date_end;
    $year_end = substr($date_end, 0, 4);
    $month_end = substr($date_end, 5, 2);
    $day_end = substr($date_end, 8, 2);
}
if ($date_end < $date_begin){
    echo "You choose an end date anterior to the start date";
}

//permet d'avoir le derniers mois
if ($date_begin == null && $date_end == null){
    $timsptamp = strtotime("now");
}
else if ($date_end == null){
    $timsptamp = strtotime("now");
}
else if ($date_end != null){
    $timsptamp = strtotime($date_end);

}
$datemax = date("Y-m-d",$timsptamp);

//La partie qui permet d'avoir les données de départ pour commencer
//L'initialisation en gros
if ($date_end >= $date_begin){
    $yearmax = substr($datemax, 0, 4);
    $yearmin = $yearmax;
    $monthend = substr($datemax, 5, 2);
    $monthmin = $monthend;
    if ($date_begin == null && $date_end != null) {
        $inter = compareMonth($monthend,$yearmax,$month_end,$year_end,$monthend,$yearmax);
    } else if ($date_begin != null && $date_end != null) {
        $inter = compareMonth($month_begin,$year_begin,$month_end,$year_end,$monthend,$yearmax);
    } else {
        $inter = (int)$tri;
    }
    $tmp = getMonthMin($yearmax, $monthend, $inter - 1);
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
    for ($m = 1; $m != $inter + 1; $m++) {
        if (isset($_SESSION['NumSiren'])) {
            $numsiren = $_SESSION['NumSiren'];
            $siren = $numsiren;
        }
        $annee[$m][$siren] = 0;
        //permet la borne entre le premiers mois et le suivant pour la requête sql
        $tmp2 = addMonth($year, $monthmin, 1);

        $month = $tmp2[0];
        $year = $tmp2[1];

        //requête sql (utilisant make_timestamp pour pourvoir faire un between en utilisant des type timestamp)

        $sql = "SELECT * FROM remise WHERE date_remise BETWEEN STR_TO_DATE('$yearmin-$monthmin-$day $hour:$min:$sec', '%Y-%m-%d %H:%i:%s') AND STR_TO_DATE('$year-$month-$day $hour:$min:$sec', '%Y-%m-%d %H:%i:%s') AND num_siren = '$numsiren' ORDER BY date_remise;";
        $req = $cnx->query($sql);
        $montant = 0;
        while ($row = $req->fetch(PDO::FETCH_OBJ)) {
            $annee[$m][$siren] += $row->montant; //Ajoute le montant
        }
        // On augmente la borne de 1 mois
        $monthmin = $month;
        $yearmin = $year;
    }
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
                {
                <?php
                echo "label: 'Montant Total',\n";
                echo "type: 'line',\n";
                echo "data: [";
                $data = 0;
                for ($m = 1; $m != $inter+1; $m++){
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
