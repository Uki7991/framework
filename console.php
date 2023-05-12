<?php

use App\Console\Kernel;

if (!defined('ROOT_DIR')) define('ROOT_DIR', __DIR__);
if (!defined('SRC_DIR')) define('SRC_DIR', __DIR__.'/src');

require __DIR__."/vendor/autoload.php";

require_once __DIR__."/bootstrap/app.php";

$kernel = new Kernel();

$job = new \Src\App\Jobs\FetchStatusFromAIJob(1);

$status = $kernel->handle($_SERVER['argv']);

exit($status);