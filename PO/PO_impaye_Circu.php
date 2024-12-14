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
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
    <title>JeFinance</title>
  <script>
    function sortTable() { 
      //Permet de trier les valeurs en renvoyant une variable
      const select = document.getElementById('sort_by');
      const selectedValue = select.value;
      // Redirige vers la même page avec le paramètre de tri
      window.location.href = `?sort_by=${selectedValue}`;
    }
  </script>
</head>

<body>
  <?php
    $onit = "Impaye";
    include("../include/po_navbar.inc.php"); // Navbar
    ?>
  <div class="mini_navbar">
    <a class="mini_link" href="PO_impaye_tableau.php">Tableau</a>
    <a class="mini_link" href="PO_impaye_Histo.php">Histogramme</a>
    <div class="mini_onit">Circulaire</div>
  </div>
  <div class="canva">
    <canvas id="myChart" style="width:100%;max-width:650px;"></canvas>
  </div>
  <?php
  global$cnx;
  include("../include/connexion.inc.php");
  //initialise un tableaux contenant les codes et associe un montant (0 au depart)
  $code = [
          "01" => 0, "02" => 0, "03" => 0, "04" => 0, "05" => 0, "06" => 0, "07" => 0, "08" => 0,
  ];

  //recupere les montant associer aux differents codes et les ajoutes a notre tableau
  $sql = "SELECT code_motif,montant FROM impaye;";
  $req = $cnx->query("SELECT code_motif,montant FROM impaye;");
  while ($ligne = $req->fetch(PDO::FETCH_OBJ)) {
      $code[$ligne->code_motif] += $ligne->montant;
  }

  ?>
  <div class="button_tel">
      <button id="btn_pdf">Exporter format PDF</button>
  </div>
 <?php
  global$cnx;
  include("../include/connexion.inc.php");
  //initialise un tableaux contenant les codes et associe un montant (0 au depart)
  $code = [
          "01" => 0, "02" => 0, "03" => 0, "04" => 0, "05" => 0, "06" => 0, "07" => 0, "08" => 0,
  ];

  //recupere les montant associer aux differents codes et les ajoutes a notre tableau
  $sql = "SELECT code_motif,montant FROM impaye;";
  $req = $cnx->query("SELECT code_motif,montant FROM impaye;");
  while ($ligne = $req->fetch(PDO::FETCH_OBJ)) {
      $code[$ligne->code_motif] += $ligne->montant;
  }

  ?>
  <div class="button_tel">
      <button id="btn_pdf">Exporter format PDF</button>
  </div>
  <script>
    //definit les options et les valeurs du graphique
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
                            return `${value}<?php echo $devise ?> (${percentage}%)`;
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
      //Permet l'export en pdf
      document.getElementById('btn_pdf').addEventListener('click', () => {
          const element = document.getElementById('myChart');

          // Obtenir les dimensions avec getBoundingClientRect
          const rect = element.getBoundingClientRect();
          const contentWidth = rect.width;
          const contentHeight = rect.height;

          //Les options du pdf
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
