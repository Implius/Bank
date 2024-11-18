<?php

global $onit;
echo "<div class=\"navbar\">";

echo "<nav>";

switch ($onit) {

    case "Tresorerie":
        ?>
        <div class="onit">Trésorerie</div>
        <a class="link" href="User_remise.php">Remise</a>
        <a class="link" href="User_Impaye_tableau.php">Impayé</a>
        <?php
        break;

    case "Remise":
        ?>
        <a class="link" href="User_tresorerie.php">Trésorerie</a>
        <div class="onit">Remise</div>
        <a class="link" href="User_Impaye_tableau.php">Impayé</a>
        <?php
        break;

    case "Impaye":
        ?>
        <a class="link" href="User_tresorerie.php">Trésorerie</a>
        <a class="link" href="User_remise.php">Remise</a>
        <div class="onit">Impayé</div>
        <?php
        break;

    default:
        break;
}

echo "</nav><a class=\"deco\" href=\"../include/deco.inc.php\"\"></a></div>";
?>
