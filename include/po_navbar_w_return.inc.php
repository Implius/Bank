<?php

global $onit;
echo "<div class=\"navbar\">";

echo "<div class=\"backArrow\" onclick=\"history.back()\"></div><nav>";

switch ($onit) {

    case "Compte":
        ?>
        <div class="onit">Compte</div>
        <a class="link" href="../PO/PO_remise.php">Remise</a>
        <a class="link" href="../PO/PO_impaye_Circu.php">Impayé</a>
        <a class="link" href="../PO/PO_creation.php">Création</a>
        <?php
        break;

    case "Remise":
        ?>
        <a class="link" href="../PO/po_compte.php">Compte</a>
        <div class="onit">Remise</div>
        <a class="link" href="../PO/PO_impaye_Circu.php">Impayé</a>
        <a class="link" href="../PO/PO_creation.php">Création</a>
        <?php
        break;

    case "Impaye":
        ?>
        <a class="link" href="../PO/po_compte.php">Compte</a>
        <a class="link" href="../PO/PO_remise.php">Remise</a>
        <div class="onit">Impayé</div>
        <a class="link" href="../PO/PO_creation.php">Création</a>
        <?php
        break;

    case "Creation":
        ?>
        <a class="link" href="../PO/po_compte.php">Compte</a>
        <a class="link" href="../PO/PO_remise.php">Remise</a>
        <a class="link" href="../PO/PO_impaye_Circu.php">Impayé</a>
        <div class="onit">Création</div>
        <?php
        break;

    default:
        break;
}

echo "</nav><a class=\"deco\" href=\"../include/deco.inc.php\"></a></div>";
?>
