<?php
require "authenticate.php";
require "../db_connect.php";

// Function to sanitize and validate input
function sanitizeInput($input)
{
    return filter_var($input, FILTER_SANITIZE_STRING);
}

// Update user information
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update"])) {
    $username = sanitizeInput($_POST["username"]);
    $name = sanitizeInput($_POST["name"]);
    $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
    $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

    if ($email === false || $id === false) {
        // Invalid input
        echo "Invalid input.";
        exit;
    }

    $updateQuery = $db->prepare("UPDATE users SET `email` = :email , `username` = :username,`name` = :name WHERE id=:id");
    $updateQuery->bindParam(':email', $email);
    $updateQuery->bindParam(':username', $username);
    $updateQuery->bindParam(':name', $name);
    $updateQuery->bindParam(':id', $id);
    $updateQuery->execute();
    echo 'done';
}

// ... (similar modifications for other blocks)

// Add input validation for new user addition
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add"])) {
    $newUsername = sanitizeInput($_POST["newUsername"]);
    $newName = sanitizeInput($_POST["newName"]);
    $newEmail = filter_input(INPUT_POST, "newEmail", FILTER_VALIDATE_EMAIL);
    $newPassword = sanitizeInput($_POST["newPassword"]);

    if ($newEmail === false) {
        // Invalid email
        echo "Invalid email.";
        exit;
    }

    // Hash the password using password_hash
    $hashedPassword = password_hash($newEmail . $newPassword . $newEmail, PASSWORD_DEFAULT);

    // Insert new user into the database
    $insertQuery = $db->prepare("INSERT INTO users (`username`, `email`,`name`, `hashedPassword`) VALUES (:username, :email,:name, :hashedPassword)");
    $insertQuery->bindParam(':name', $newName);
    $insertQuery->bindParam(':username', $newUsername);
    $insertQuery->bindParam(':email', $newEmail);
    $insertQuery->bindParam(':hashedPassword', $hashedPassword);
    $insertQuery->execute();
}

// ... (similar modifications for other blocks)

// Delete user
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete"])) {
    $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

    if ($id === false) {
        // Invalid input
        echo "Invalid input.";
        exit;
    }

    $deleteQuery = $db->prepare("DELETE FROM users WHERE id = :id");
    $deleteQuery->bindParam(':id', $id);
    $deleteQuery->execute();
}

// Fetch users
$statement = "SELECT *, (SELECT COUNT(*) FROM projects WHERE creator = users.username) AS 'tutorialCount' FROM users";
if (isset($_GET["query"])) {
    $statement .= " WHERE username LIKE CONCAT('%', :username, '%')";
}
$query = $db->prepare($statement);
if (isset($_GET["query"])) {
    $searchQuery = filter_var($_GET["query"], FILTER_SANITIZE_STRING);
    $query->bindParam(":username", $searchQuery);
}
$query->execute();
$users = $query->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Admin</title>
</head>

<body>
    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Home</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="collapsibleNavbar">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#addUserModal">Add User</a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="#">Link</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Link</a>
                    </li> -->
                </ul>
            </div>
        </div>
    </nav>
    <br><br><br><br><br>
    <?php if (isset($_GET["query"])): ?>
        <h3> Showing results for "
            <?= filter_var($_GET["query"], FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?>", <a href='index.php'>view all</a>
        </h3>
    <?php endif ?>
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Add User</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post">
                        <div class="mb-3">
                            <label for="newUsername" class="form-label">Username</label>
                            <input type="text" class="form-control" id="newUsername" name="newUsername" required>
                        </div>
                        <div class="mb-3">
                            <label for="newName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="newName" name="newName" required>
                        </div>
                        <div class="mb-3">
                            <label for="newEmail" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="newEmail" name="newEmail" required>
                        </div>
                        <div class="mb-3">
                            <label for="newPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                        </div>
                        <button type="submit" class="btn btn-primary" name="add">Add User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div id="reactComponent"></div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>

    <!-- import the react library -->
    <script src="https://unpkg.com/react@16/umd/react.development.js" crossorigin></script>
    <script src="https://unpkg.com/react-dom@16/umd/react-dom.development.js" crossorigin></script>
    <script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .navbar{
            position: absolute;
            top: 0px;
            width: 100%;
            height: 65px;
        }
        .addUserButton {
            height: 50px;
            margin: 10px;
            margin-left: auto;
        }

        .form {
            max-width: 80vw;
            min-width: 40vw;
            position: absolute;
            top: 70px;
            right: 5px;
        }

        .form .fa-search {

            position: absolute;
            top: 20px;
            left: 20px;
            color: #9ca3af;

        }

        .form span {

            position: absolute;
            right: 17px;
            top: 13px;
            padding: 2px;
            border-left: 1px solid #d1d5db;

        }

        .left-pan {
            padding-left: 7px;
        }

        .left-pan i {

            padding-left: 10px;
        }

        .form-input {

            height: 55px;
            text-indent: 33px;
            border-radius: 10px;
        }

        .form-input:focus {

            box-shadow: none;
            /* border: none; */
        }

        /* @media (min-width:500px) {
            .form {
                right: auto;
            }
        } */

        .delete {
            position: absolute;
            right: 5px;
            top: 3px;
            margin-inline: 10px;
        }

        .usersGrid {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-evenly;
        }

        .usersGrid>li {
            max-width: 450px;
            margin: 20px;
            border-width: 1px 1px 1px 1px;
            margin-bottom: auto;
        }

        .card-header {
            padding: 10px;
        }
    </style>
    <!-- setup react root component other components -->
    <script type="text/babel">

        class RootComponent extends React.Component {
            render() {
                return <div>
                    <MyComponent />
                </div>;
            }
        }

        function MyComponent() {
            const [edit, setEdit] = React.useState('');
            const users = <?= json_encode($users) ?>

            return (<><div class="usersSearch container">

                <div class="row height d-flex justify-content-center align-items-center">

                    <div class="col-md-6">

                        <form class="form" >
                            <i class="fa fa-search"></i>
                            <input name='query' type="text" class="form-control form-input" placeholder="Search username..." />
                        </form>

                    </div>

                </div>

            </div>
                <ul className="usersGrid">
                    {users.map((user) => (
                        <li key={user.username} className="card">
                            <div>
                                <div className="card-header">{user.name}
                                    <form action="" method='post'>
                                        <input type="hidden" name="id" value={user.id} />
                                        <button
                                            type="submit"
                                            className="btn btn-danger delete"
                                            name="delete"
                                            onClick={(e) => {
                                                const res = window.confirm('Are you sure you want to delete this user?');
                                                if (!res) e.preventDefault();
                                            }}
                                        >
                                            Delete
                                        </button>
                                    </form>
                                </div>
                                <div className="card-body">
                                    <h5 className="card-title">Tutorials Created: {user.tutorialCount}</h5>
                                    <p className="card-text">With supporting text below as a natural lead-in to additional content.</p>
                                    <a href="#" className="btn btn-primary" data-bs-toggle="collapse" data-bs-target={`#${user.username}Information`}>
                                        View Profile
                                    </a>
                                </div>
                                <div className="collapse list-group-item" id={`${user.username}Information`}>
                                    <div className="card card-body">
                                        <form method="post">
                                            <div className="mb-3">
                                                <label htmlFor={`${user.username}InputUsername`} className="form-label">Username</label>
                                                <input
                                                    type="text"
                                                    className="form-control"
                                                    id={`${user.username}InputUsername`}
                                                    name="username"
                                                    defaultValue={user.username}
                                                    disabled={edit !== user.username}
                                                />
                                            </div>
                                            <div className="mb-3">
                                                <label htmlFor={`${user.username}InputName`} className="form-label">Name</label>
                                                <input
                                                    type="text"
                                                    className="form-control"
                                                    id={`${user.username}InputEmail`}
                                                    name="name"
                                                    defaultValue={user.name}
                                                    disabled={edit !== user.username}
                                                />
                                            </div>
                                            <div className="mb-3">
                                                <label htmlFor={`${user.username}InputEmail`} className="form-label">Email address</label>
                                                <input
                                                    type="email"
                                                    className="form-control"
                                                    id={`${user.username}InputEmail`}
                                                    name="email"
                                                    defaultValue={user.email}
                                                    disabled={edit !== user.username}
                                                />
                                            </div>
                                            <input type="hidden" name="id" value={user.id} />
                                            <div className="mb-3 form-check">
                                                <input
                                                    type="checkbox"
                                                    className="form-check-input"
                                                    id={`${user.username}Check`}
                                                    name="edit"
                                                    onClick={(e) => setEdit(e.target.checked ? user.username : '')}
                                                />
                                                <label className="form-check-label" htmlFor={`${user.username}Check`}>Edit</label>
                                            </div>
                                            <button
                                                type="submit"
                                                className="btn btn-primary"
                                                name="update"
                                                disabled={edit !== user.username}
                                            >
                                                Update
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </li>
                    ))}
                </ul></>
            );
        }


        const domContainer = document.querySelector('#reactComponent');
        ReactDOM.render(React.createElement(RootComponent), domContainer);
    </script>
</body>

</html>