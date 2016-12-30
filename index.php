<?php

require_once "vendor/autoload.php";

require_once "config/database.php";

define('POSTCODE_API_KEY', 'e4py1TCtbnaQQqvznKROL8zu0jjP1Lu13OI3kK0H');

function autoloader($class_name) {
    if (is_file('app/controllers/' . $class_name . '.php')) {
        require_once 'app/controllers/' . $class_name . '.php';
    } else if (is_file('app/helpers/' . $class_name . '.php')) {
        require_once 'app/helpers/' . $class_name . '.php';
    } else if (is_file('app/models/' . $class_name . '.php')) {
        require_once 'app/models/' . $class_name . '.php';
    }
}
spl_autoload_register("autoloader");

$users = new UserController();

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $response = $users->create();
}

?>

<html>
<head>
    <title>Almanapp case</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2>Registreer</h2>
            <?php if(isset($response) && is_array($response)) { ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach($response as $msg) { ?>
                        <li><?php echo $msg; ?></li>
                    <?php } ?>
                </ul>
            </div>
            <?php } elseif(isset($response) && is_string($response)) { ?>
            <div class="alert alert-success">
                <?php echo $response; ?>
            </div>
            <?php } ?>
            <form method="post" action="/">
            <div class="col-md-1">
                <label for="">Voorletters</label>
                <input name="initials" type="text" class="form-control" value="<?php echo $_POST['initials']; ?>">
            </div>
            <div class="col-md-4">
                <label for="">Voornaam</label>
                <input name="first_name" type="text" class="form-control" value="<?php echo $_POST['first_name']; ?>">
            </div>
            <div class="col-md-4">
                <label for="">Achternaam</label>
                <input name="last_name" type="text" class="form-control" value="<?php echo $_POST['last_name']; ?>">
            </div>
            <div class="col-md-3">
                <label for="">Wachtwoord</label>
                <input name="password" type="password" class="form-control">
            </div>
            <div class="col-md-2">
                <label for="">Postcode</label>
                <input name="postcode" type="text" class="form-control" value="<?php echo $_POST['postcode']; ?>">
            </div>
            <div class="col-md-1">
                <label for="">Huisnummer</label>
                <input name="house_number" type="text" class="form-control" value="<?php echo $_POST['house_number']; ?>">
            </div>
            <div class="col-md-5">
                <label for="">E-mailadres</label>
                <input name="email" type="email" class="form-control" value="<?php echo $_POST['email']; ?>">
            </div>
            <div class="col-md-4">
                <label for="">Telefoonnummer</label>
                <div class="input-group">
                    <span class="input-group-addon">+316</span>
                    <input name="phone_number" type="text" class="form-control" value="<?php echo $_POST['phone_number']; ?>">
                </div>
            </div>
            <div class="col-md-2">
                <input type="submit" value="Opslaan" class="btn btn-primary" />
            </div>
            </form>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2>Overzicht</h2>
            <table class="table">
                <thead>
                    <th>Naam</th>
                    <th>Adres</th>
                    <th>E-mailadres</th>
                    <th>Telefoonnummer</th>
                </thead>
                <tbody>
                    <?php foreach($users->all() as $user) { ?>
                    <tr>
                        <td><?php echo $user['initials'] . ' ' . $user['first_name'] . ' ' . $user['last_name']; ?></td>
                        <td><?php echo $user['street_name'] . ' ' . $user['house_number'] . ' ' . $user['postcode'] . ' ' . $user['city']; ?></td>
                        <td><?php echo $user['email']; ?></td>
                        <td>+316<?php echo $user['phone_number']; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>
</html>
