<?php

require_once '../vendor/autoload.php';
use PHPAnsiCode\Ansi;

getName:
echo "Please enter your name: ";
$name = fgets(STDIN);
if (trim($name) <> '') {
    echo Ansi::green("Your name is: $name");
}
else {
    echo Ansi::red('Enter name!') . PHP_EOL;
    goto getName;
}