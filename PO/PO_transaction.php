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
      &:nth-child(4n-1) { background: #b3b3b3; }
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
                Transaction N°
            </th>
            <th class="table-darkblue">
                Date
            </th>
            <th class="table-blue">
                Émetteur
            </th>
            <th class="table-darkblue">
                N° SIREN Émetteur
            </th>
            <th class="table-blue">
                Objet
            </th>
            <th class="table-darkblue">
                N° Auto
            </th>
            <th class="table-blue">
                Bénéficiaire
            </th>
            <th class="table-darkblue">
                N° SIREN Bénéficiaire
            </th>
            <th class="table-blue">
                Montant
            </th>
        </tr>
        </thead>
        <tbody>
                <tr class="view">
                    <td>
                        0005
                    </td>
                    <td>
                        14/09/2024
                    </td>
                    <td>
                        Dupont
                    </td>
                    <td>
                        425 682 301
                    </td>
                    <td>
                        Vente de produit
                    </td>
                    <td>
                        985621
                    </td>
                    <td>
                        E.Leclerc
                    </td>
                    <td>
                        572 183 994
                    </td>
                    <td>
                        87152.09 €
                    </td>
                </tr>
             <tr class="fold">
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
                  <tr>
                    <td>Sony</td>
                    <td>13245</td>
                  </tr>
                  <tr>
                    <td>Sony</td>
                    <td>13288</td>
                  </tr>
                </tbody>
              </table>          
            </div>
          </td>
        </tr>
        <tr class="view">
          <td>
              0005
          </td>
          <td>
              14/09/2024
          </td>
          <td>
              Dupont
          </td>
          <td>
              425 682 301
          </td>
          <td>
              Vente de produit
          </td>
          <td>
              985621
          </td>
          <td>
              E.Leclerc
          </td>
          <td>
              572 183 994
          </td>
          <td>
              87152.09 €
          </td>
      </tr>
        <tr class="fold">
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
                  <tr>
                    <td>Sony</td>
                    <td>13245</td>
                  </tr>
                  <tr>
                    <td>Sony</td>
                    <td>13288</td>
                  </tr>
                </tbody>
              </table>          
            </div>
          </td>
        </tr>
        <!--Mettre code PHP ici-->
        </tbody>
    </table>
</div>
</body>

</html>
