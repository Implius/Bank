<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="po.css">
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
    $onit = "Remise";
    include("../include/po_navbar_w_return.inc.php"); // Navbar
?>

<div class="Compte_tableau">
    <table class="tableau" style="width:90%">
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
        <a href="PO_transaction_detail.php">
            <div>
                <tr>
                    <td class="white">
                        0005
                    </td>
                    <td class="grey">
                        14/09/2024
                    </td>
                    <td class="white">
                        Dupont
                    </td>
                    <td class="grey">
                        425 682 301
                    </td>
                    <td class="white">
                        Vente de produit
                    </td>
                    <td class="grey">
                        985621
                    </td>
                    <td class="white">
                        E.Leclerc
                    </td>
                    <td class="grey">
                        572 183 994
                    </td>
                    <td class="white montant">
                        87152.09 €
                    </td>
                </tr>
            </div>
        <!--Mettre code PHP ici-->
        </tbody>
    </table>
</div>
</body>

</html>