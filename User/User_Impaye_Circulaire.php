<?php
// Connexion à la base de données, vérication de l'authentification

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
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <link rel="stylesheet" href="po_compte.css">
    <title>JeFinance</title>
</head>
<body>
<?php
$onit = "Impaye"; // Page actuelle
include("../include/User_navbar.inc.php"); // Navbar
?>
<div class="mini_navbar">
    <a class="mini_link" href="User_Impaye_tableau.php">Tableau</a>
    <a class="mini_link" href="User_Impaye_Histo.php">Histogramme</a>
    <div class="mini_onit">Circulaire</div>
</div>
<div class="canva">
    <canvas id="myChart" style="width:100%;max-width:650px;"></canvas>
</div>
<?php
// Tous les codes de motifs possibles, associés à un montant initial de 0
$req = $cnx->query("SELECT devise FROM compte where num_siren='".$_SESSION['NumSiren']."';");
$devise = $req->fetch(PDO::FETCH_OBJ)->devise;
switch ($devise) {
    case "EUR":
        $devise= " € ";
        break;
    case "USD":
        $devise= " $ ";
        break;
    case "GBP":
        $devise= " £ ";
        break;
    default:
        $devise= " ? ";
        break;
};
$code = [
    "01" => 0, "02" => 0, "03" => 0, "04" => 0, "05" => 0, "06" => 0, "07" => 0, "08" => 0,
];
$sql = "SELECT code_motif,montant FROM impaye;";
$req = $cnx->query("SELECT code_motif,montant FROM impaye where num_siren='".$_SESSION['NumSiren']."';");
$total=0;
while ($ligne = $req->fetch(PDO::FETCH_OBJ)) { // Pour tous les impayés d'un numéro SIREN, on ajoute le montant à la somme du code de motif
    $code[$ligne->code_motif] += $ligne->montant;
    $total += $ligne->montant;
}

$percentages = [];
foreach ($code as $key => $value) {
    $percentages[$key] = ($total > 0) ? (($value / $total) * 100) : 0;
}
?>
<div class="button_tel">
    <button id="btn_pdf">Exporter format PDF</button>
</div>
<script>
    const ctx = document.getElementById('myChart').getContext('2d');
    const xValues = ["Fraude à la carte", "Compte à découvert", "Compte clôturé", "Compte bloqué", "Provision insuffisante", "Opération contestée par le débiteur", "Titulaire décédé", "Raison non communiquée"];
    const yValues = [<?php echo implode(',', array_values($code)); ?>];
    function createRandomGradient() {
        const color1 = barColors[Math.floor(Math.random() * barColors.length)];
        const color2 = barColors[Math.floor(Math.random() * barColors.length)];
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, color1);
        gradient.addColorStop(1, color2);
        return gradient;
    }
    function createRadialGradient(color1, color2) {
        const gradient = ctx.createLinearGradient(200, 200, 50, 200, 200, 200);
        gradient.addColorStop(0, color1);
        gradient.addColorStop(1, color2);
        return gradient;
    }
    const barColors = [
        "#FF0000",
        "#FF4500",
        "#FF7F00",
        "#FFB300",
        "#FFFF00",
        "#B3FF00",
        "#00FF00",
        "#00FF7F",
        "#00FFFF",
        "#007FFF",
        "#0000FF",
        "#4500FF",
        "#8000FF",
        "#FF00FF",
        "#FF007F",
        "#FF9999",
        "#FFCC99",
        "#FFFF99",
        "#99FF99",
        "#99FFFF",
        "#9999FF",
        "#D699FF",
    ];
    const gradients = [
        createRadialGradient("#F44336", "#E81E63"),
        createRadialGradient("#9c27b0", "#673ab7"),
        createRadialGradient("#3f51b5", "#2196f3"),
        createRadialGradient("#03a9f4", "#00bcd4"),
        createRadialGradient("#009688", "#4caf50"),
        createRadialGradient("#8BC34A", "#cddc39"),
        createRadialGradient("#ffeb3b", "#ffc107"),
        createRadialGradient("#ff9800", "#ff5722")
    ];
    new Chart(ctx, {
        type: "pie",
        data: {
            labels: xValues,
            datasets: [{
                data: yValues,
                backgroundColor: gradients,
                borderWidth: 0,
            }]
        },
        options: {
            responsive: false,
            title: {
                display: true,
                text: "Impayé"
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: (context) => {
                            const value = context.raw;
                            const total = context.dataset.data.reduce((sum, val) => sum + val, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return `${value}<?php echo $devise ?>(${percentage}%)`;
                        }
                    },
                    bodyFont: {
                        size: 25 // Change the font size for tooltip body
                    },
                    titleFont: {
                        size: 25 // Change the font size for tooltip title
                    },
                },
                legend: {
                    labels: {
                        color: 'black',
                        font: {
                            size: 19 // Change the font size for legend labels
                        }
                    }
                },
            }
        }
    });
</script>
<script>
    document.getElementById('btn_pdf').addEventListener('click', () => {
        const element = document.getElementById('myChart');

        // Obtenir les dimensions avec getBoundingClientRect
        const rect = element.getBoundingClientRect();
        const contentWidth = rect.width;
        const contentHeight = rect.height;

        const opt = {
            margin: 0, //pas de marge
            filename: 'table.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2, width: contentWidth, height: contentHeight }, // Meilleure qualité
            jsPDF: { unit: 'px', format: [contentWidth, contentHeight], orientation: 'landscape' }
        };
        // Génération du PDF
        html2pdf().set(opt).from(element).save();
    });
</script>
</body>
</html>

