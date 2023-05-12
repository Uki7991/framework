<?php
if (!defined('ROOT_DIR')) define('ROOT_DIR', __DIR__.'/../');
if (!defined('SRC_DIR')) define('SRC_DIR', __DIR__.'/../src');

require ROOT_DIR."/vendor/autoload.php";
require ROOT_DIR."/src/routes.php";
require ROOT_DIR."/bootstrap/app.php";

use App\Http\Kernel;
use Symfony\Component\HttpFoundation\Request;

$kernel = new Kernel();

$response = $kernel->handle($request = Request::createFromGlobals())->send();
