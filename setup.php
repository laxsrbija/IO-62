<?php require_once  "functions.php" ?>
<!DOCTYPE html>
<html>
    <head>
        <title>Database setup - <?php echo $appname ?></title>
    </head>
    <body>
        <h3>Database setup...</h3>
        <?php

            createTable("members",
                "id INT UNSIGNED AUTO_INCREMENT,
                user VARCHAR(16),
                pass VARCHAR(50),
                INDEX(user(6)),
                PRIMARY KEY(id)"
            );

            createTable("profiles",
                "id INT UNSIGNED AUTO_INCREMENT,
                user_id INT UNSIGNED NOT NULL,
                text VARCHAR(4096),
                PRIMARY KEY(id),
                FOREIGN KEY(user_id) REFERENCES members(id)
                    ON UPDATE CASCADE
                    ON DELETE NO ACTION"
            );

            createTable("friends",
                "id INT UNSIGNED AUTO_INCREMENT,
                user_id INT UNSIGNED NOT NULL,
                friend_id INT UNSIGNED NOT NULL,
                PRIMARY KEY(id),
                FOREIGN KEY(user_id) REFERENCES members(id)
                    ON UPDATE CASCADE
                    ON DELETE NO ACTION,
                FOREIGN KEY(friend_id) REFERENCES members(id)
                    ON UPDATE CASCADE
                    ON DELETE NO ACTION"
            );

            createTable("images",
                "id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user_id INT UNSIGNED,
                file_path VARCHAR(150),
                date_uploaded DATETIME,
                FOREIGN KEY (user_id) REFERENCES members(id)
                    ON UPDATE CASCADE
                    ON DELETE NO ACTION"
            );

            createTable("roles",
                "id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                role_name VARCHAR(50) NOT NULL"
            );

            createTable("role_perm",
                "id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                role_id INT UNSIGNED NOT NULL,
                perm_id INT UNSIGNED NOT NULL,
                FOREIGN KEY (role_id) REFERENCES roles(id)
                    ON UPDATE CASCADE
                    ON DELETE NO ACTION,
                FOREIGN KEY (perm_id) REFERENCES permissions(id)
                    ON UPDATE CASCADE
                    ON DELETE NO ACTION"
            );

            createTable("member_role",
                "id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                member_id INT UNSIGNED NOT NULL,
                role_id INT UNSIGNED NOT NULL,
                FOREIGN KEY (member_id) REFERENCES members(id)
                    ON UPDATE CASCADE
                    ON DELETE NO ACTION,
                FOREIGN KEY (role_id) REFERENCES roles(id)
                    ON UPDATE CASCADE
                    ON DELETE NO ACTION"
            );
        
        ?>
    <p>...done!</p>
    </body>
</html>