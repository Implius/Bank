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
<?php
$onit = "Impaye";
include("../include/po_navbar.inc.php"); // Navbar
?>
  <div class="mini_navbar">
    <a class="link" href="PO_impaye_tableau.php">Tableau</a>
    <div class="onit">Histogramme</div>
    <a class="link" href="PO_impaye_Circu.php">Circulaire</a>
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

</html>
