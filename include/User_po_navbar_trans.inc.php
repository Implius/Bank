<?php

global $onit;
echo "<div class=\"navbar\">";

echo "<div class=\"backArrow\" onclick=history.back()></div><nav>";

switch ($onit) {

    case "Tresorerie":
        ?>
        <div class="onit">Trésorerie</div>
        <a class="link" href="../PO/PO_Compte_Remise.php">Remise</a>
        <a class="link" href="../PO/PO_Compte_Impaye_tableau.php">Impayé</a>
        <?php
        break;

    case "Remise":
        ?>
        <a class="link" href="../PO/PO_Compte_Tresorerie.php">Compte</a>
        <div class="onit">Remise</div>
        <a class="link" href="../PO/PO_Compte_Impaye_tableau.php">Impayé</a>
        <?php
        break;

    case "Impaye":
        ?>
        <a class="link" href="../PO/PO_Compte_Tresorerie.php">Compte</a>
        <a class="link" href="../PO/PO_Compte_Remise.php">Remise</a>
        <div class="onit">Impayé</div>
        <?php
        break;

    default:
        break;
}

echo "</nav><a class=\"deco\" href=\"../include/deco.inc.php\"\"></a></div>";
?>