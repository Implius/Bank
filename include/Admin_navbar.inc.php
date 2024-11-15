<?php

global $onit;
echo "<div class=\"navbar\"><nav>";

switch ($onit) {

    case "Creation":
        ?>
        <div class="onit">Création</div>
        <a class="link" href="../Admin/Admin_Supression.php">Supression</a>
        <?php
        break;

    case "Supression":
        ?>
        <a class="link" href="../Admin/Admin_Creation.php">Création</a>
        <div class="onit">Supression</div>
        <?php
        break;


    default:
        break;
}

echo "</nav><a class=\"deco\" href=\"../include/deco.inc.php\"></a></div>";
?>
