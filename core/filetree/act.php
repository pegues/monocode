<?php
if(isset($_POST['action']) &&  $_POST['action']=='save'){
file_put_contents($_POST['fileName'],$_POST['fileContent']);

}?>