#!/usr/bin/env php
<?php

use Symfony\Component\Console\Application;
 
require 'vendor/autoload.php';

$application = new Application('Battle Console Application', '1.0.0');

$application->add(new Iulyanp\Battle\BattleManagerCommand());

$application->run();
