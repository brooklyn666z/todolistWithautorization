<?php
// Скрипт проверки

// Соединямся с БД
$link = mysqli_connect("localhost", "root", "root123", "testdb");


if (isset($_POST['delete_branch_id'])) {
    $delete_id = $_POST['delete_branch_id'];
    $sql = "DELETE FROM `tree` WHERE `branch_id` = $delete_id LIMIT 1;";
    if ($link->query($sql)) {
        echo "Ветка успешно удалена";
    } else {
        echo "Ошибка: " . $link->error;
    }
};

if (isset($_COOKIE['id'])) {
    $query = mysqli_query($link, "SELECT *,INET_NTOA(user_ip) AS user_ip FROM users WHERE user_id = '" . intval($_COOKIE['id']) . "' LIMIT 1");
    $userdata = mysqli_fetch_assoc($query);
} else {
    print "Включите куки";
}

if (isset($_POST['delete'])) {
    $sql = "TRUNCATE tree";
    if ($link->query($sql)) {
        echo "Ветки успешно удалены";
    } else {
        echo "Ошибка: " . $link->error;
    }
}
if (isset($_POST['submit'])) {
    $branch_name = $_POST['branch_name'];
    $branch_desc = $_POST['branch_desc'];
    $branch_parrent_id = $_POST['branch_parrent_id'] ? $_POST['branch_parrent_id'] : NULL;
    if ($branch_parrent_id) {
        $sql = "INSERT INTO tree (branch_name, branch_desc, branch_parrent_id) VALUES ('$branch_name', '$branch_desc', '$branch_parrent_id')";
    } else {
        $sql = "INSERT INTO tree (branch_name, branch_desc) VALUES ('$branch_name', '$branch_desc')";
    }
    if ($link->query($sql)) {
        echo "Данные успешно добавлены";
    } else {
        echo "Ошибка: " . $link->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Админ-панель</title>
</head>

<body>
    <header class="main__header">
        <div id="name"><?= $userdata['user_login']; ?></div>
        <a id="out" href="/login.php">Выйти</a>
    </header>
    <ul id="list">
        <?php
        $sql = "SELECT * FROM tree WHERE branch_parrent_id IS NULL";
        if ($result = $link->query($sql)) {
            foreach ($result as $row) {
                $branch_id = $row["branch_id"];
                $branch_name = $row["branch_name"];
                $branch_desc = $row["branch_desc"];
                $branch_parrent_id = $row["branch_parrent_id"];
        ?>
                <li>
                    <button class='list__button-element'><?= $branch_name; ?> (ИД=<?= $branch_id; ?>)</button>
                    <button class='list__button-change'>Изменить описание</button>
                    <button class='list__button-del' data-id="<?= $branch_id; ?>">Удалить</button>
                    <p><?= $branch_desc; ?></p>
                </li>

        <?php
            }
        }
        ?>
    </ul>

    <div id="modal">
        <div class="popup change__description">
            <div class="popup__header">
                <div class="popup__title-container">
                    <h3 class="popup__title">Изменить описание</h3>
                </div>
                <button class="popup__close-btn" type="button">X</button>
            </div>

            <form class="popup__form change__description">
                <input placeholder="Описание к элементу" type="text" class="input-login" />
                <button class="send-auth-form">Отправить</button>
            </form>
        </div>

        <div class="popup add__description">
            <div class="popup__header">
                <div class="popup__title-container">
                    <h3 class="popup__title">Добавить описание</h3>
                </div>
                <button class="popup__close-btn" type="button">X</button>
            </div>

            <form class="popup__form add__description">
                <input placeholder="Добавить описание" type="text" class="input-login" />
                <button class="send-auth-form">Отправить</button>
            </form>
        </div>
        <div id="background-modal"></div>
    </div>
    <form method="POST" id="deleteForm" style="display:none;">
        <input type="text" name="delete_branch_id" id="delete_branch_id">
    </form>
    <form method="POST">
        Название ветки <input name="branch_name" type="text" required><br>
        Описание ветки <input name="branch_desc" type="text" required><br>
        ИД родителя(опционально) <input name="branch_parrent_id" type="text"><br>
        <input name="submit" type="submit" value="Добавить ветку">
    </form>
    <form method="POST">
        <input name="delete" type="submit" value="Удалить все ветки">
    </form>

    <script>
        const menu = document.querySelector('#list');
        const modal = document.querySelector('#modal');
        const modalBackground = document.querySelector('#background-modal');
        const closeModalButtons = document.querySelectorAll('.popup__close-btn');
        const deleteBranch = document.querySelector('#delete_branch_id');
        const deleteForm = document.querySelector('#deleteForm');

        function toggleModal() {
            if (modal) {
                modal.classList.toggle('open');
                document.body.classList.toggle('hidden');
            }
        }

        function openModalChangeDescription() {
            toggleModal();

            const popupChangeDescription = document.querySelector('.popup.change__description');
            popupChangeDescription.classList.add('open');
        }

        function openModalAddDescription() {
            toggleModal();

            const popupAddDescription = document.querySelector('.popup.add__description');
            popupAddDescription.classList.add('open');
        }



        menu.addEventListener('click', (event) => {
            const target = event.target;
            if (target.tagName === 'BUTTON' && target.parentNode.querySelector('ul') && target.classList.contains('list__button-element')) {
                const submenu = target.parentNode.querySelector('ul');

                if (submenu.style.display === 'none' || submenu.style.display === '') {
                    submenu.style.display = 'flex';
                } else {
                    submenu.style.display = 'none';
                }
            }

            if (target.tagName === 'BUTTON' && target.classList.contains('list__button-change')) {
                openModalChangeDescription()
            }
            if (target.tagName === 'BUTTON' && target.classList.contains('list__button-del')) {
                deleteBranch.setAttribute('value', target.dataset.id);
                deleteForm.submit();

            }
            if (target.tagName === 'BUTTON' && target.classList.contains('list__button-add')) {
                openModalAddDescription()
            }
        });

        function closeModalBtn() {
            const popups = document.querySelectorAll('.popup.open');

            for (let i = 0; popups.length > i; i++) {
                popups[i].classList.remove('open');
            }
            toggleModal();
        }


        for (let i = 0; closeModalButtons.length > i; i++) {
            closeModalButtons[i].addEventListener('click', closeModalBtn);
        }

        modalBackground.addEventListener('click', closeModalBtn);
    </script>
    <style>
        .main__header {
            display: flex;
            gap: 10px;
            align-items: center;
            justify-content: flex-end;
        }

        ul {
            list-style: none;
            padding-left: 20px;
        }

        ul li ul {
            display: none;
        }

        ul li.open {
            display: block;
        }

        #list {
            margin: 0;
            padding: 0 0 0 20px;
            border-left: 1px solid #999;
            display: flex;
            flex-direction: column;
            gap: 10px;
            list-style: none;
            margin-top: 40px;
        }

        #list li {
            display: inline-flex;
            flex-direction: column;
            position: relative;
            gap: 10px;
        }

        #list li button {
            background-color: #2e465c;
            border: 0;
            border-radius: 4px;
            color: #fff;
            cursor: pointer;
            padding: 6px 12px;
            position: relative;
        }

        #list .list__button-del {
            background-color: rgb(255, 229, 231);
            color: rgb(255, 88, 95);
        }

        #list .list__button-change {
            background-color: #0d87f7;
        }

        #list .list__button-add {
            background-color: #407db5;
        }

        #list li ul {
            border-left: 1px solid #999;

            flex-direction: column;
            gap: 10px;
            list-style: none;
            margin: 0 0 0 20px;
            padding: 10px 0 0 20px;
        }

        #list p {
            border: 1px solid #f3f3f3;
            padding: 10px;
            margin: 0;
            margin-top: 10px;
        }

        #modal {
            display: none;
            position: fixed;
            z-index: var(--joy-zIndex-modal);
            inset: 0px;
        }

        #modal.open {
            display: block;
            border-radius: 6px;
        }

        .popup__form {
            margin-top: 30px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .popup__form input {
            padding: 10px;
            border: 1px solid #b9b5b5;
            border-radius: 6px;
        }

        .popup {
            background-color: #fff;
        }

        #background-modal {
            position: fixed;
            display: flex;
            -webkit-box-align: center;
            align-items: center;
            -webkit-box-pack: center;
            justify-content: center;
            inset: 0px;
            background-color: rgba(190, 190, 190, 0.31);
            -webkit-tap-highlight-color: transparent;
            z-index: -1;
        }

        .popup__header {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .popup__title {
            margin: 0;
        }

        .popup__close-btn {
            background-color: transparent;
            padding: 5px;
            border-color: transparent;
            cursor: pointer;
            position: absolute;
            right: 20px;
            top: 20px;
        }

        .popup {
            display: none;
        }

        .popup.open {
            display: block;
        }

        .send-auth-form {
            padding: 10px;
            border: 1px solid #b9b5b5;
            border-radius: 6px;
        }

        @media screen and (min-width: 0px) {
            .popup {
                position: fixed;
                inset: 90px 0px 0px;
                border-radius: 0px;
                padding: 0px 0px 30px;
                width: 100%;
                max-height: 100%;
                overflow: unset;
                border-top: 1px solid rgb(242, 243, 244);
            }
        }

        @media screen and (min-width: 480px) {
            .popup {
                position: absolute;
                top: 50%;
                left: 50%;
                bottom: auto;
                transform: translate(-50%, -50%);
                box-shadow: var(--joy-shadows-21);
                border-radius: 20px;
                width: 400px;
                max-height: 500px;
                overflow: hidden;
                border-top: unset;
            }
        }

        @media screen and (min-width: 768px) {
            .popup {
                width: 700px;
                padding: 20px;
            }
        }
    </style>
</body>

</html>