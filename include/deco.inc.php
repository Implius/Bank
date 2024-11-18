<?php
session_start();
session_destroy();
header("location: ../po_login.php?error=3");
