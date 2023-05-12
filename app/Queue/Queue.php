<?php

namespace App\Queue;

use App\Contracts\CommandContract;
use App\Database\DB;
use App\Models\Job;
use Src\App\Models\Task;

class Queue
{
    private function __construct()
    {
    }

    public function work()
    {
        while (true) {
            if ($command = $this->getCommand()) {
                try {
                    $command->execute();

                    print $command::class . " command executed \n";
                } catch (\Throwable $exception) {
                    $this->errorCommand($command->id);

                    throw $exception;
                }
            } else {
                print "No command executed \n";
            }
            sleep(2);
        }
    }

    private function getCommand(): CommandContract|null
    {
        $job = Job::getFirst("SELECT * FROM jobs where status = ?", [0]);

        if (!$job) {
            return null;
        }

        $command = unserialize(base64_decode($job->command));
        $command->id = $job->id;

        $job->processing();

        return $command;
    }

    public function addCommand(CommandContract $command)
    {
        $job = new Job([
            'command' => base64_encode(serialize($command)),
        ]);

        $job->save();
    }

    public function completeCommand($id)
    {
        $job = Job::firstOrFail($id);

        $job->complete();
    }

    public function errorCommand($id)
    {
        $job = Job::firstOrFail($id);

        $job->error();
    }

    public static function getInstance()
    {
        static $instance;

        if (!$instance) {
            $instance = new Queue();
        }

        return $instance;
    }
}