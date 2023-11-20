<?php
require "authenticate.php";
require "db_connect.php";

// Function to sanitize and validate input
function sanitizeInput($input)
{
    return filter_var($input, FILTER_SANITIZE_STRING);
}

// Update user information
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update"])) {
    $username = sanitizeInput($_POST["username"]);
    $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
    $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

    if ($email === false || $id === false) {
        // Invalid input
        echo "Invalid input.";
        exit;
    }

    $updateQuery = $db->prepare("UPDATE users SET email = :email , username = :username WHERE id=:id");
    $updateQuery->bindParam(':email', $email);
    $updateQuery->bindParam(':username', $username);
    $updateQuery->bindParam(':id', $id);
    $updateQuery->execute();
    echo 'done';
}

// ... (similar modifications for other blocks)

// Add input validation for new user addition
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add"])) {
    $newUsername = sanitizeInput($_POST["newUsername"]);
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
    $insertQuery = $db->prepare("INSERT INTO users (username, email, hashedPassword) VALUES (:username, :email, :hashedPassword)");
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
$query = $db->prepare("SELECT * FROM users");
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
    <H1>Users <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">+</button> </H1>
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

    <!-- import the react libraray -->
    <script src="https://unpkg.com/react@16/umd/react.development.js" crossorigin></script>
    <script src="https://unpkg.com/react-dom@16/umd/react-dom.development.js" crossorigin></script>
    <script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>

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
            return (
                <ul class="list-group">
                    <?php foreach ($users as $user): ?>
                        <li data-bs-toggle="collapse" data-bs-target="#<?= $user['username'] ?>Information"
                            class="list-group-item d-flex justify-content-between align-items-center">
                            <?= $user["username"] ?>
                        </li>
                        <div className="collapse list-group-item" id="<?= $user['username'] ?>Information">
                            <div className="card card-body">
                                <form method="post">
                                    <div className="mb-3">
                                        <label for="<?= $user["username"] ?>InputUsername" className="form-label">Username</label>
                                        <input type="text" className="form-control" id="<?= $user["username"] ?>InputUsername"
                                            name="username" defaultValue="<?= $user["username"] ?>" disabled={edit != '<?= $user['username'] ?>'} />
                                    </div>
                                    <div className="mb-3">
                                        <label for="<?= $user["username"] ?>InputUsername" className="form-label">Email address</label>
                                        <input type="email" className="form-control" id="<?= $user["username"] ?>InputEmail" name="email"
                                            defaultValue="<?= $user["email"] ?>" disabled={edit != '<?= $user['username'] ?>'} />
                                    </div>
                                    <input type="hidden" name="id" value='<?= $user["id"] ?>' />
                                    <div className="mb-3 form-check">
                                        <input type="checkbox" className="form-check-input" id="<?= $user["username"] ?>Check" name="edit" onClick={e => setEdit(e.target.checked ? '<?= $user['username'] ?>' : '')} />
                                        <label className="form-check-label" for="<?= $user["username"] ?>Check">Edit</label>
                                    </div>
                                    <button type="submit" className="btn btn-primary" name="update" disabled={edit != '<?= $user['username'] ?>'}>Update</button>
                                    <button type="submit" className="btn btn-danger" name="delete"
                                        onClick={(e) => {
                                            const res = confirm('Are you sure you want to delete this user?');
                                            if (!res) e.preventDefault();
                                        }}>Delete</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach ?>
                </ul>
            );
        }

        const domContainer = document.querySelector('#reactComponent');
        ReactDOM.render(React.createElement(RootComponent), domContainer);
    </script>
</body>

</html>

</html>