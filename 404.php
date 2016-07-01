<?php
/**
* 404 Generic Response Error
*/
include_once './rest/core/rest.php';

use Rest\Errors\NotFound as NotFound;
use Rest\Response as Response;

$response = new Response();
$response->setError(NotFound::error());
$response->render();

