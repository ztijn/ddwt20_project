<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <!-- Own CSS -->
    <link rel="stylesheet" href="/ddwt20_project/css/main.css">

    <title><?= $page_title ?></title>
</head>
<body>
<!-- Menu -->
<?= $navigation ?>

<!-- Content -->
<div class="container">
    <!-- Breadcrumbs -->
    <div class="pd-15">&nbsp</div>
    <?= $breadcrumbs ?>

    <div class="row">

        <div class="col-md-12">
            <!-- Error message -->
            <?php if (isset($error_msg)){echo $error_msg;} ?>
            <h1><?= $page_title ?></h1>
            <h5><?= $page_subtitle ?></h5>

            <br/><br/>
            <h5>Personal Information</h5>
            <table class="table table-striped">
                <tr>
                    <td>Username:</td>
                    <td>Role:</td>
                    <td>Fullname:</td>
                    <td>Birthday:</td>
                    <td>Biography:</td>
                    <td>Stud/Prof:</td>
                    <td>Language:</td>
                    <td>Email:</td>
                    <td>Phone:</td>
                </tr>
                <br/>
                <tr>
                    <td><?=$username?></td>
                    <td><?=$role?></td>
                    <td><?=$fullname?></td>
                    <td><?=$birthday?></td>
                    <td><?=$bio?></td>
                    <td><?=$stud_prof?></td>
                    <td><?=$language?></td>
                    <td><?=$email?></td>
                    <td><?=$phone?></td>
                </tr>
            </table>

            <!-- Remove and edit button to edit profile of logged in user-->

            <div class="row">
                <div class="col-sm-2">
                    <a href="/ddwt20_project/myaccount/?user_id=<?= $_SESSION['user_id']?>" role="button" class="btn btn-warning">Edit profile</a>
                </div>

                <div class="col-sm-2">
                    <form action="/ddwt20_project/myaccount/" method="GET">
                        <input type="hidden" value="<?= $_SESSION['user_id'] ?>" name="user_id">
                        <button type="submit" class="btn btn-danger">Delete profile</button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <div class="pd-15">&nbsp;</div>

    <div class="row">

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    Welcome, <?= $user ?>
                </div>
                <div class="card-body">
                    <p>You're logged in.</p>
                    <a href="/ddwt20_project/logout/" class="btn btn-primary">Logout</a>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>
