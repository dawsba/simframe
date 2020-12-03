<?php

use Models\City\City;

require_once 'Src/Engine/Config/bootstrap.php';

$city = new City();

/** @var \Twig\Environment $twig */
$twig = $app->twig;

echo $twig->render('./Board/board.html', ['cityrows' => $city->generateRowsHtml($twig)]);
