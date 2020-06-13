$(document).ready(() => {

    let timer;
    function addFine() {
        if(timer) {}
            clearTimeout(timer);

        $('#addfine').show();

        timer = setTimeout(() => $('#addfine').hide(), 4000);
    }

    /*
    *  Processing click oo checkbox
    */
    $('input[type="checkbox"]').change((e) => {
        let task_id = $(e.currentTarget).parent().parent().attr('task-id');
        $.post('/task/mark', {
            task_id: task_id,
            checked: e.currentTarget.checked
        });
    });

    /*
    *  Processing click on auth button
    */
    $('.authuser').on('click', (e) => {
        let parent = $(e.currentTarget).parent().parent();

        let username = parent.find('#authuser-text'),
            password  = parent.find('#authpassword-text');
            
        username.parent().find('.invalid-feedback').hide();
        password.parent().find('.invalid-feedback').hide();
        $('#autherror').hide();
        $('#loginerror').hide();

        if(!username.val()) {
            username.parent().find('.invalid-feedback').show();
            return;
        }

        if(!password.val()) {
            password.parent().find('.invalid-feedback').show();
            return;
        }

        $.post('/login/', {
            username: username.val(),
            password: password.val()
        }).done((data) => {
            if(data === 'success') {
                $('#authfine').show();

                setTimeout(() => location.reload(), 1000);

                return;
            }

            $('#loginerror').show();
        });
    });

    /*
    *  Processing click on 'редактировать'
    */
    $('.edit-task').on('click', (e) => {
        e.stopPropagation();
        let parent = $(e.currentTarget).parent();

        let task_id = parent.attr('task-id');

        $(e.currentTarget).html('<a class="done_edit" href="#">Готово</a> <a class="cancel_edit" href="#">Отменить</a>');

        let text = parent.find('.task-text');

        let task_text = text.html();

        text.html(`<input type="text" value="${task_text}">`);

        $('.done_edit').on('click', (ev) => {
            ev.stopPropagation();
            
            let new_text = text.find('input').val();
            
            text.html(new_text);
            $(e.currentTarget).html('<a href="#">Редактировать</a>');

            $.post('/task/edit', {
                task_id: task_id,
                text: new_text
            }).done((data) => {
                if(data === 'need_auth') {
                    location.reload();
                    return;
                }
            });
        });
        
        $('.cancel_edit').on('click', (ev) => {
            ev.stopPropagation();
            text.html(task_text);
            $(e.currentTarget).html('<a href="#">Редактировать</a>');
        });
    }); 
    
    /*
    *  Processing click on adding button
    */
    $('.add-task').on('click', (e) => {
        let parent = $(e.currentTarget).parent().parent().parent();

        let username = parent.find('#login-text'),
            email  = parent.find('#email-text'),
            task = parent.find('#task-text');
        
        username.parent().find('.invalid-feedback').hide();
        email.parent().find('.invalid-feedback').hide();
        task.parent().find('.invalid-feedback').hide();

        if(!username.val()) {
            username.parent().find('.invalid-feedback').show();
            return;
        }

        if(!email.val() || !validateEmail(email.val())) {
            email.parent().find('.invalid-feedback').show();
            return;
        }

        if(!task.val()) {
            task.parent().find('.invalid-feedback').show();
            return;
        }

        $.post('/task/add', {
            username: username.val(),
            email: email.val(),
            task: task.val(),
        }).done((data) => {
            if(data === 'success') {
                addFine();
                username.val('');
                email.val('');
                task.val('');
                setTimeout(() => location.reload(), 1000);
                return;
            }
        });

    });

    function validateEmail(email) {
        const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }
});