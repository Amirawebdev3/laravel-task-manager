<?php

namespace App\Services;

use App\Repositories\Interfaces\TaskRepositoryInterface;

class TaskService
{
    protected $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function getAllTasks()
    {
        return $this->taskRepository->all();
    }

    public function getTask($id)
    {
        return $this->taskRepository->find($id);
    }

    public function createTask(array $data)
    {
        return $this->taskRepository->create($data);
    }

    public function updateTask($id, array $data)
    {
        return $this->taskRepository->update($id, $data);
    }

    public function deleteTask($id)
    {
        return $this->taskRepository->delete($id);
    }

    public function completeTask($id)
    {
        return $this->taskRepository->complete($id);
    }

    public function getUserTasks($userId)
    {
        return $this->taskRepository->getByUser($userId);
    }
}