<?php

namespace Src\App\Services;

use App\Exceptions\ModelNotFoundException;
use App\Models\Model;
use App\Queue\Queue;
use Src\App\Jobs\FetchStatusFromAIJob;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Src\App\Models\Task;
use Symfony\Component\HttpFoundation\JsonResponse;

class TaskService
{
    /**
     * @param string $name
     * @param UploadedFile $file
     * @return array
     */
    public function store(string $name, UploadedFile $file): array
    {
        $task = $this->checkExistingFile($file);

        if ($task) {
            return [
                'status' => 'ready',
                'task' => $task->id,
                'result' => $task->result,
            ];
        }

        $task = $this->create($name, $file);

        \queue()->addCommand(new FetchStatusFromAIJob($task->id));

        return [
            'status' => 'received',
            'task' => $task->id,
            'result' => $task->result,
        ];
    }

    public function get(string $id)
    {
        try {
            $task = Task::firstOrFail($id);
        } catch (ModelNotFoundException $exception) {
            return new JsonResponse([
                'status' => 'not_found',
                'result' => null
            ], 404);
        }

        if (!$task->result) {
            return new JsonResponse([
                'status' => 'wait',
                'result' => null,
            ]);
        }

        return new JsonResponse([
            'status' => 'ready',
            'result' => $task->result
        ]);
    }

    public function create(string $name, UploadedFile $file): Model
    {
        list($filePath, $originalName) = $this->storeFile($file);

        $task = new Task([
            'name' => $name,
            'file_path' => $filePath,
            'file_original_name' => $originalName,
        ]);

        $task->save();

        return $task;
    }

    /**
     * @param UploadedFile $file
     * @return Model
     */
    public function checkExistingFile(UploadedFile $file): Model|null
    {
        return Task::getFirst("SELECT * FROM tasks WHERE file_original_name = ?", [$file->getClientOriginalName()]);
    }

    /**
     * @param UploadedFile $file
     * @return array<string>
     */
    private function storeFile(UploadedFile $file): array
    {
        $fileName = "/images/" . md5((new \DateTime())->format("H:i:s")).'.'.$file->getClientOriginalExtension();
        $originalName = $file->getClientOriginalName();

        move_uploaded_file($file->getRealPath(), PUBLIC_DIR.$fileName);

        return [$fileName, $originalName];
    }
}