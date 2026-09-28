<?php 

$listTableSQL = <<<SQL
CREATE TABLE IF NOT EXISTS `listItem` (
  `id` varchar(255) PRIMARY KEY,
  `name` varchar(255) NOT NULL,
  `price` DECIMAL(6, 2),
  `collected` BOOL,
  `listOrder` INT
  )
SQL;

define("LIST_TABLE_SQL", $listTableSQL);