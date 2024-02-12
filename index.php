<?php

session_start();

function loadClass($class)
{
    require $class . '.class.php';
}

spl_autoload_register('loadClass');

$taskManager = new TaskManager();

$tasks = $taskManager->getAllTasks();

if (isset($_POST["task_create"])) {
    $taskCreate = $_POST["task_create"];

    if ($taskManager->addTask($taskCreate)) {
        $_SESSION['success_message'] = "Task added successfully.";
    } else {
        $_SESSION['error_message'] = "Error adding task.";
    }

    header('location: index.php');
    exit();
}

if (isset($_POST["task_delete"])) {
    $taskDelete = $_POST["task_delete"];
    if ($taskManager->delTask($taskDelete)) {
        $_SESSION['success_message'] = "Task deleted successfully.";
    } else {
        $_SESSION['error_message'] = "Error deleting task.";
    }

    header('location: index.php');
    exit();
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ToDoList - OOP - Oslo418</title>
    <link rel="icon" type="image/png" href="./assets/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="./assets/style.css">
</head>

<body>
    <?php include 'includes/header.php' ?>

    <?php
    if (isset($_SESSION['success_message'])) {
        echo '<div id="success_message" class="alert alert-success">' . htmlspecialchars($_SESSION['success_message']) . '</div>';
        unset($_SESSION['success_message']);
    } elseif (isset($_SESSION['error_message'])) {
        echo '<div id="error_message" class="alert alert-danger">' . htmlspecialchars($_SESSION['error_message']) . '</div>';
        unset($_SESSION['error_message']);
    }
    ?>

    <div class="container">
        <div class="container mt-3">
            <form method="post">
                <div class="input-group mb-3">
                    <input type="text" name="task_create" class="form-control" placeholder="Buy tomatoes" aria-label="ToDo" aria-describedby="button-addon2" required>
                    <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Create</button>
                </div>
            </form>
        </div>

        <div class="container">
            <table class="table table-striped">
                <thead class=".thead-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Tasks</th>
                        <th scope="col" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php foreach ($tasks as $task) : ?>
                        <tr>
                            <th scope="row" class="w-50"><?php echo $task->getId(); ?></th>
                            <td class="w-50"><?php echo $task->getTask(); ?></td>
                            <td class="w-25">
                                <form method="post">
                                    <input type="hidden" name="task_delete" value="<?php echo $task->getId(); ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function hideMessage(messageId) {
            var message = document.getElementById(messageId);
            if (message) {
                setTimeout(function() {
                    message.classList.add('hide');
                }, 4000);
            }
        }
        hideMessage('success_message');
        hideMessage('error_message');
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>

</html>