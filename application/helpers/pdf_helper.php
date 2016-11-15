<?php
function dompdf() {
    require_once('dompdf/autoload.inc.php');
    return new \Dompdf\Dompdf();
}