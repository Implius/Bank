<?php
global$cnx;
include("../include/connexion.inc.php");
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="PO.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
  <div class="navbar">
    <nav>
      <a class="link" href="PO_compte.php">Compte</a>
      <a class="link" href="PO_remise.php">Remise</a>
      <div class="onit">Impayé</div>
      <a class="link" href="PO_creation.php">Création</a>
    </nav>
    <a class="deco" href="index.php"></a>
  </div>
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
  $code = [
          "01" => 0, "02" => 0, "03" => 0, "04" => 0, "05" => 0, "06" => 0, "07" => 0, "08" => 0,
  ];
  $sql = "SELECT code_motif,montant FROM impaye;";
  $req = $cnx->query("SELECT code_motif,montant FROM bank.impaye;");
  while ($ligne = $req->fetch(PDO::FETCH_OBJ)) {
      $code[$ligne->code_motif] += $ligne->montant;
  }

  ?>
  <script>
    const ctx = document.getElementById('myChart').getContext('2d');
    const xValues = ["Code 01", "Code 02", "Code 03", "Code 04", "Code 05", "Code 06", "Code 07", "Code 08"];
    const yValues = [<?php echo $code["01"] . "," . $code["02"] . "," . $code["03"] . "," . $code["04"] . "," . $code["05"] . "," . $code["06"] . "," . $code["07"] . "," . $code["08"]; ?>];
    
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

    new Chart(ctx, {
      type: "pie",
      data: {
        labels: xValues,
        datasets: [{
          backgroundColor: [
          createRadialGradient("#F44336", "#E81E63"),  // Coral to Light Orange to Light Yellow Orange
    createRadialGradient("#9c27b0", "#673ab7"),  // Light Blue to Sky Blue to Dark Blue
    createRadialGradient("#3f51b5", "#2196f3"),  // Purple to Light Purple to Light Pink Purple
    createRadialGradient("#03a9f4", "#00bcd4"),  // Light Red to Light Orange to Light Yellow Orange
    createRadialGradient("#009688", "#4caf50"),  // Teal to Light Teal to Light Green Teal
    createRadialGradient("#8BC34A", "#cddc39"),  // Light Yellow to Golden Yellow to Dark Yellow
    createRadialGradient("#ffeb3b", "#ffc107"),  // Indigo to Light Indigo to Dark Indigo
    createRadialGradient("#ff9800", "#ff5722")
          ],
          data: yValues,
          borderWidth: 0,
        }]

      },
      options: {
        responsive: false,
        title: {
          display: true,
          text: "World Wide Wine Production 2018"
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
              color: 'black',
              font: {
                size: 19 // Change the font size for legend labels
              }
            }
          }
        }
      }
    });
  </script>
</body>

</html>
