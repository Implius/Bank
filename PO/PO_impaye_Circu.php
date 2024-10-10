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
      <a class="link" href="PO_compte.html">Compte</a>
      <a class="link" href="PO_remise.html">Remise</a>
      <div class="onit">Impayé</div>
      <a class="link" href="PO_creation.html">Création</a>
    </nav>
    <a class="deco" href="index.html"></a>
  </div>
  <div class="mini_navbar">
    <a class="link" href="PO_impaye_tableau.html">Tableau</a>
    <a class="link" href="PO_impaye_Histo.html">Histogramme</a>
    <div class="onit">Circulaire</div>
  </div>
  <div class="canva">
    <canvas id="myChart" style="width:100%;max-width:650px;"></canvas>
  </div>
  <script>
    const ctx = document.getElementById('myChart').getContext('2d');
    const xValues = ["Raison 1", "Raison 2", "Raison 3", "Raison 4", "Raison 5", "Raison 6", "Raison 7", "Raison 8"];
    const yValues = [55, 49, 44, 24, 15, 18 ,21, 69];

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
