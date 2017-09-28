<?php
/**
 * Expected parameters:
 * - title: The title shown on Facebook
 * - description: The description shown on Facebook
 * - imageUrl: The URL to the image shown on Facebook
 * - redirectUrl: The URL to redirect to when clicking on the shared article on Facebook
 *
 * How it works:
 * -------------
 * Because the Facebook Crawler does not recognize changing the og:xxx-tags dynamically via JavaScript, it is necessary
 * to offer a static page, that can be crawled instead. With the help of this script, this static page is built.
 * Depending on what is given with the GET-parameter "params" via the URL, specific og-tags will be set.
 * The redirect is used to push the user from the static site to the homepage again.
 *
 * This script can be located on any webserver running PHP.
 */
$params = json_decode($_GET['params']);

$html  = '<!doctype html>'.PHP_EOL;
$html .= '<html>'.PHP_EOL;
$html .= '<head>'.PHP_EOL;
// $html .= '<meta property="og:url" content="' . $params->redirectUrl . '"/>'.PHP_EOL;
$html .= '<meta property="og:type" content="article"/>'.PHP_EOL;
$html .= '<meta property="og:title" content="' . $params->title . '"/>'.PHP_EOL;
$html .= '<meta property="og:description" content="' . $params->description . '"/>'.PHP_EOL;
$html .= '<meta property="og:image" content="' . $params->imageUrl . '"/>'.PHP_EOL;
$html .= '<meta property="og:image:width" content="300"/>'.PHP_EOL;
$html .= '<meta property="og:image:height" content="300"/>'.PHP_EOL;
$html .= '<meta http-equiv="refresh" content="0;url=' . $params->redirectUrl . '">'.PHP_EOL;
$html .= '</head>'.PHP_EOL;
$html .= '<body></body>'.PHP_EOL;
$html .= '</html>';

echo $html;