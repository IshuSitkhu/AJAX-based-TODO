<?php

require_once "Task.php";

class TaskManager {

    private $file;

    public function __construct($filePath = null) {
        // Always safe absolute path
        $this->file = $filePath ?? __DIR__ . "/../data/tasks.json";

        //If file NOT exist
        if (!file_exists($this->file)) {
            if (!is_dir(dirname($this->file))) {
            //   Create folder if missing 
            mkdir(dirname($this->file), 0777, true);
            }
            //CREATE FILE
            file_put_contents($this->file, json_encode([]));
        }
    }

    private function getTasks() {
        $data = file_get_contents($this->file);
        //Converts JSON → PHP array  AND IF EMPTY RETURN []
        return $data ? json_decode($data, true) : [];
    }

    private function saveTasks($tasks) {
        //Converts JSON → PHP array
        file_put_contents($this->file, json_encode($tasks, JSON_PRETTY_PRINT));
    }

    public function getAllTasks() {
        return $this->getTasks();
    }

    public function addTask($title) {

    //CLEAN INPUT
        $title = trim($title); 

        // VALIDATION
        if (empty($title)) {
            return ["success" => false, "message" => "Title cannot be empty"];
        }

        //GET EXIXTING TASK
        $tasks = $this->getTasks();

        //CREATE TASK
        // TIME- UNQIE ID
        $taskObj = new Task(time(), $title);

        //CONVERT TO ARRRAY
        $tasks[] = $taskObj->toArray();

        $this->saveTasks($tasks);

        return ["success" => true, "message" => "Task added successfully"];
    }

    public function updateTask($id, $title, $status) {

        $title = trim($title); 

        //VALIDATION
        if (empty($title)) {
            return ["success" => false, "message" => "Title cannot be empty"];
        }

        if (!in_array($status, ["pending", "completed"])) {
            return ["success" => false, "message" => "Invalid status"];
        }

        $tasks = $this->getTasks();

        //GO THROUGH ALL TASK
        foreach ($tasks as &$task) {
            //IF ID MATCH
            if ($task["id"] == $id) {
                $task["title"] = $title;
                $task["status"] = $status;

                break;
            }
        }

        $this->saveTasks($tasks);

        return ["success" => true, "message" => "Task updated successfully"];
    }

    public function deleteTask($id) {

        $tasks = $this->getTasks();

        $tasks = array_filter($tasks, function($task) use ($id) {
            return $task["id"] != $id;
        });

        $this->saveTasks(array_values($tasks));

        return ["success" => true, "message" => "Task deleted successfully"];
    }

    public function deleteAllTasks() {
        file_put_contents($this->file, json_encode([]));

        return [
            "success" => true,
            "message" => "All tasks deleted successfully"
        ];
    }
}