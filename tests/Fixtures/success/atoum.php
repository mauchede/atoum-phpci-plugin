<?php

$runner
    ->setBootstrapFile(__DIR__.'/app/autoload.php')
    ->addTestsFromDirectory('tests')
;
