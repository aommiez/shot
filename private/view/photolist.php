<?php
use Main\Helper;
use Main\DB\Medoo\MedooFactory;
$this->import('/layout/header');
$db = MedooFactory::getInstance();
$photo = $db->select('photo','*');
?>
<div class="container">
photolist
</div>
