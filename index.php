<?php
require("db.inc.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$errors = [];
$success = false;

$begin = new DateTime('2025-02-06');
$end = new DateTime('2025-05-06');
$interval = DateInterval::createFromDateString('1 day');
$period = new DatePeriod($begin, $interval, $end);

$name = "";
$email = "";
$appdate = "";

if (isset($_POST['formSubmit'])) {
  $name = $_POST['inputName'];
  $email = $_POST['inputEmail'];
  $appdate = $_POST['appdate'];

  if (strlen($name) > 100) {
    $errors[] = "Name is too long.";
  }

  if (strlen($name) <= 3) {
    $errors[] = "Please enter your full name.";
  }

  if (preg_match("/[\^<,\"@\/\{\}\(\)\*\$%\?=>:\|;#]+/i", $name)) {
    $errors[] = "Name cannot contain special characters.";
  }

  if (strlen($email) == 0) {
    $errors[] = "Please enter your email address.";
  }

  if (strlen($email) > 100) {
    $errors[] = "Email address is too long.";
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($email) > 0) {
    $errors[] = "Please enter a valid email address.";
  }

  if ($appdate == "") {
    $errors[] = "Please select a date.";
  }

  if (count($errors) == 0) {
    $success = newAppointment(
      $name,
      $email,
      $appdate,
    );
    header("Location: confirmation.php");
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
    crossorigin="anonymous" />
  <title>Schedule an appointment - FELInt</title>
</head>

<body>
  <div class="container">
    <main>
      <h2>Schedule an appointment with FELInt</h2>
      <hr />

      <?php if (count($errors) > 0): ?>
        <div class="alert alert-danger" role="alert">
          <ul>
            <?php foreach ($errors as $error): ?>
              <li><?= $error; ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif ?>

      <form method="post" action="index.php">

        <div class="form-group mt-3">
          <label for="inputName" class="col-sm-2 col-form-label">Name:</label>
          <div>
            <input type="text" class="form-control" id="inputName" name="inputName" placeholder="Display Name" value="<?php echo isset($name) ? $name : ''; ?>">
          </div>
        </div>

        <div class="form-group mt-3">
          <label for="inputEmail" class="col-sm-2 col-form-label">Email Address:</label>
          <div>
            <input type="text" class="form-control" id="inputEmail" name="inputEmail" placeholder="Email" value="<?php echo isset($email) ? $email : ''; ?>">
          </div>
        </div>

        <div class="form-group mt-3">
          <label for="appdate" class="col-sm-2 col-form-label">Appointment Date:</label>
          <div>
            <select name="appdate" id="appdate" class="form-select">
              <option value="">Select a date</option>
              <?php foreach ($period as $date): ?>
                <option value="<?= $date->format('Y-m-d') ?>"><?= $date->format('l, j F Y') ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="form-group mt-5">
          <div>
            <button type="submit" class="btn btn-primary" name="formSubmit" style="width: 100%">Schedule Appointment</button>
          </div>
        </div>
      </form>

    </main>

  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>