<?php 

/*
* Building some link for working both pages and sorter
*/
function buildLink($sort) {
    $link = '';
    if(isset($_GET['page'])) {
        $link .= '?page='.$_GET['page'];
    }

    if(isset($_GET['sort'])) {
        $splitted = explode('_', $_GET['sort']);

        $sort_now = $splitted[0];
        $by = $splitted[1];
    }

    $link .= (strstr($link, '?') ? '&' : '?') . 'sort=' .$sort.'_'.(isset($sort_now) && $sort_now === $sort && $by === 'asc' ? 'desc' : 'asc');

    return $link;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todoip</title>
    <link rel="stylesheet" href="/assets/css/style.css" />
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css" />
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>
    <script src="/assets/js/app.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Todoip</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarColor01">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="/">Главная</a>
                </li>
            </ul>
            <div class="float-right">
                <?php if(isset($_SESSION['logged'])):?>
                <a href="logout" class="btn btn-outline-info my-2 my-sm-0">Выйти</a>
                <?php else: ?>
                <button class="btn btn-outline-info my-2 my-sm-0" data-toggle="modal" data-target="#authmodal" type="button">Войти</button>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <div class="container-fluid">
        <div class="container mt-3">
            <div class="col-lg-11 card">
                <div class="px-3 mt-3">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="login">Логин</span>
                        </div>
                        <input type="text" id="login-text" class="form-control" aria-label="Username" aria-describedby="login" required>
                        <div class="invalid-feedback">
                            Введите корректный логин
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="email">Почта</span>
                        </div>
                        <input type="email" id="email-text" class="form-control" aria-label="Username" aria-describedby="login" required>
                        <div class="invalid-feedback">
                            Введите корректную почту
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="task">Задача</span>
                        </div>
                        <input type="text" id="task-text" class="form-control" aria-label="Username" aria-describedby="login" required>
                        <div class="invalid-feedback">
                            Введите корректную задачу
                        </div>
                    </div>

                    <div class="float-right">
                        <div class="row">
                            <span id="addfine" class="mr-2 mt-1" style="display: none; color: green;">Вы успешно добавили задачу</span>
                            <button type="button" class="btn btn-primary mb-3 add-task">Добавить</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container mt-3">
            <div class="col-lg-11 card">
                <div class="mt-3 mb-3">
                    <div class="table-responsive">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th><a href="<?php echo buildLink('completed'); ?>">#</a></th>
                                    <th><a href="<?php echo buildLink('username'); ?>">Логин</a></th>
                                    <th><a href="<?php echo buildLink('email'); ?>">Почта</a></th>
                                    <th><a href="<?php echo buildLink('task'); ?>">Задача</a></th>
                                    <?php if(isset($_SESSION['logged'])):?>
                                    <th>#</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach($data['data']->tasks as $task): ?>
                                <tr task-id="<?php echo $task->id; ?>">
                                    <td><input <?php echo isset($_SESSION['logged']) ? '' : 'disabled'; ?> <?php echo $task->completed ? 'checked' : ''; ?> type="checkbox"><?php echo ($task->edited ? '<small> (Отредактировано)</small>' : ''); ?></td>
                                    <td><?php echo $task->username; ?></td>
                                    <td><?php echo $task->email; ?></td>
                                    <td class="task-text"><?php echo $task->task; ?></td>
                                    <?php if(isset($_SESSION['logged'])):?>
                                    <td class="edit-task"><a href="#">Редактировать</a></td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>
                    <div class="paggin">
                        <?php echo $data['data']->pages; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="authmodal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Авторизация</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="username">Логин</span>
                    </div>
                    <input type="text" id="authuser-text" class="form-control" aria-label="Username" aria-describedby="login" required>
                    <div class="invalid-feedback">
                        Введите логин
                    </div>
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="password">Пароль</span>
                    </div>
                    <input type="password" id="authpassword-text" class="form-control" aria-label="Username" aria-describedby="login" required>
                    <div class="invalid-feedback">
                        Введите пароль
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <span id="loginerror" style="display: none; color: red;">Логин или пароль неверные</span>
                <span id="authfine" style="display: none; color: green;">Вы успешно авторизовались</span>
                <button type="button" class="btn btn-primary authuser">Войти</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
            </div>
            </div>
        </div>
    </div>
</body>
</html>