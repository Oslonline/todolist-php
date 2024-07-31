<?php

abstract class AbstractTaskManager
{
    abstract public function addTask(string $task);
    abstract public function delTask(int $id);
    abstract public function getAllTasks();
}

class TaskManager extends AbstractTaskManager
{
    protected $_id;
    protected $_name;
    protected $_dbh;

    public function __construct($dbh = null)
    {
        if(isset($dbh)){
            $this->_dbh=$dbh;
        }
        else{
            $this->_dbh = new Database();
        }
    }    

    // --------- Setters ---------
    public function setId(int $id)
    {
        $this->_id = $id;
    }

    public function setName(string $name)
    {
        $this->_name = $name;
    }

    // --------- Getters ---------
    public function getId(): int
    {
        return $this->_id;
    }

    public function getTask(): string
    {
        return $this->_name;
    }

    public function addTask(string $task): bool
    {
        $filteredTask = self::filterTaskInput($task);

        $stmt = $this->_dbh->prepare("INSERT INTO tasks (task_description) VALUES (:task)");
        $stmt->bindParam(':task', $filteredTask);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function delTask(int $id): bool
    {
        $stmt = $this->_dbh->prepare("DELETE FROM tasks WHERE id = :id");
        $stmt->bindParam(':id', $id);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }


    public function getAllTasks()
    {
        $stmt = $this->_dbh->query("SELECT * FROM tasks");
        $tasks = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $task = new TaskManager($this->_dbh);
            $task->setId($row['id']);
            $task->setName($row['task_description']);
            $tasks[] = $task;
        }
        return $tasks;
    }

    public static function filterTaskInput($input)
    {
        $input = strip_tags($input);
        $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
        $input = preg_replace("/[^a-zA-Z0-9\- 'À-ÿ]/u", '', $input);
        return $input;
    }
}
