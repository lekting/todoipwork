<?php

class ControllerTask extends Controller {

    /*
    * Adding a task to DB
    */
    public function add() {
        if(!isset($_POST['username']) || !isset($_POST['email']) || !isset($_POST['task']))
            return 'need more parameters';

        $username = htmlentities($this->db->escape($_POST['username']));
        $email = htmlentities($this->db->escape($_POST['email']));
        $task = htmlentities($this->db->escape($_POST['task']));

        $this->db->query("INSERT INTO `tasks`(`username`, `email`, `task`) VALUES('{$username}', '{$email}', '{$task}')");

        return 'success';
    }


    /*
    * Marking a task as completed in DB
    */
    public function mark() {
        if(!isset($_SESSION['logged']))
            return 'need_auth';

        if(!isset($_POST['task_id']) || !isset($_POST['checked']))
            return 'need_task_id';

        $task_id = htmlentities($this->db->escape($_POST['task_id']));
        $checked = htmlentities($this->db->escape($_POST['checked']));

        $this->db->query("UPDATE `tasks` SET `completed`={$checked} WHERE `id`={$task_id}");

        return 'success';
    }


    /*
    * Edditing a task in DB
    */
    public function edit() {
        if(!isset($_SESSION['logged']))
            return 'need_auth';

        if(!isset($_POST['task_id']) || !isset($_POST['text']))
            return 'need_task_id';

        $task_id = htmlentities($this->db->escape($_POST['task_id']));
        $text = htmlentities($this->db->escape($_POST['text']));

        $this->db->query("UPDATE `tasks` SET `task`='{$text}', `edited`=1 WHERE `id`={$task_id}");

        return 'success';
    }

    /*
    * Getting all tasks and creating a pagination
    */
    public function get() {

        $sql = 'SELECT * FROM `tasks`';

        //applying sorting
        if(isset($_GET['sort']) && strstr($_GET['sort'], '_')) {
            $splitted = explode('_', $_GET['sort']);
            $sort = $splitted[0];
            $by = $splitted[1];
            $sql .= " ORDER BY `{$sort}` {$by} ";
        }

        $tasks = $this->db->query($sql);

        $total = $tasks->num_rows;

        $on_page = 3;

        $max_pagination = 4;

        $pages = ceil($total / $on_page);

        $page = 1;

        if(isset($_GET['page']))
            $page = (int)$_GET['page'];

        $offset = ($page -1) * $on_page;
        
        $start = (($page - $max_pagination ) > 0) ? $page - $max_pagination : 1;
        $end = (($page + $max_pagination) < $pages) ? $page + $max_pagination : $pages;

        $pagesb = '<nav><ul class="pagination"><li class="page-item">';
        
        if($page > 1) {
            $pagesb .= '<a class="page-link" href="?page=1'.(isset($_GET['sort']) ? '&sort='.$_GET['sort'] : '').'" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>';
        }

        for($i = $start; $i <= $end; $i++) {
            $pagesb .= '<li class="page-item '.($page === $i ? 'active' : '').'"><a class="page-link" href="?page='.$i.(isset($_GET['sort']) ? '&sort='.$_GET['sort'] : '').'">'.$i.'</a></li>';
        }

        if($page < $pages) {
            $pagesb .= '<li class="page-item"><a class="page-link" href="?page='.$pages.(isset($_GET['sort']) ? '&sort='.$_GET['sort'] : '').'" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li></ul></nav>';
        }

        $data['pages'] = $pagesb;

        //splice all tasks to match needed for us count of tasks
        $data['tasks'] = array_splice($tasks->rows, $offset, $on_page);

        return json_encode($data);
    }


}