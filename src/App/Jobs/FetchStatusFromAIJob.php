<?php

namespace Src\App\Jobs;

use App\Contracts\CommandContract;
use App\Queue\Command;
use Src\App\Models\Task;

class FetchStatusFromAIJob extends Command implements CommandContract
{
    private $taskId;

    public function __construct($id)
    {
        $this->taskId = $id;
    }

    public function execute()
    {
        $task = Task::firstOrFail($this->taskId);

        if (!$task->retry_id) {
            $post = ['name' => $task->name, 'photo' => $this->createCurlFile($task)];

            $response = $this->requestAI($post);

            $task->update([
                'result' => $response['result'],
                'retry_id' => $response['retry_id'],
            ]);
        } else {
            $response = $this->requestAI([
                'retry_id' => $task->retry_id,
            ]);

            $task->update([
                'result' => $response['result'],
                'retry_id' => $response['retry_id'],
            ]);
        }

        if ($response['retry_id']) {
            queue()->addCommand(new FetchStatusFromAIJob($this->taskId));
        }

        $this->complete();
    }

    private function requestAI(array $post): array
    {
        $curl = curl_init('http://merlinface.com:12345/api/');
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type:multipart/form-data',
        ]);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        $result = curl_exec($curl);
        curl_close($curl);

        return json_decode($result, true);
    }

    private function createCurlFile(Task $task)
    {
        $filePath = realpath(PUBLIC_DIR.$task->file_path);
        return curl_file_create($filePath, mime_content_type($filePath), $task->file_original_name);
    }
}