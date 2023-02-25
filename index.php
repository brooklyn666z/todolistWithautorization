<?php $link = mysqli_connect("localhost", "root", "root123", "testdb");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>to-do</title>
</head>
<header class="main__header">
    <a href="/login.php">Войти</a>
</header>

<body>
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
                    <p><?= $branch_desc; ?></p>
                </li>

        <?php
            }
        }
        ?>
    </ul>
</body>

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
</style>

<script>
    const menu = document.querySelector('#list');

    menu.addEventListener('click', (event) => {
        const target = event.target;

        if (target.tagName === 'BUTTON' && target.parentNode.querySelector('ul')) {
            const submenu = target.parentNode.querySelector('ul');

            if (submenu.style.display === 'none' || submenu.style.display === '') {
                submenu.style.display = 'flex';
            } else {
                submenu.style.display = 'none';
            }
        }
    });
</script>

</html>