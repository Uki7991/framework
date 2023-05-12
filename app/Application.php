<?php

namespace App;

class Application
{
    public function __construct()
    {
        $this->loadEnv();
        define('PUBLIC_DIR', __DIR__.'/../public');
    }

    private function loadEnv()
    {
        $env = \Dotenv\Dotenv::createImmutable(ROOT_DIR);
        $env->load();
    }
}