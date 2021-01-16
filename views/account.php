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

        <div class="col-md-8">
            <!-- Error message -->
            <?php if (isset($error_msg)){echo $error_msg;} ?>
            <h1><?= $page_title ?></h1>
            <h5><?= $page_subtitle ?></h5>

            <br/><br/>
            <h5>Personal Information</h5>

            <form action="/ddwt20_project/register/">
                <div class="form-group">
                    <label for="inputUsername">Username</label>
                    <input type="text" class="form-control" id="inputUsername" value="<?=$user_info['username']?>" name="username" readonly>
                </div>
                <div class="form-group">
                    <label for="inputPassword">Password</label>
                    <input type="password" class="form-control" id="inputPassword" placeholder="" name="password" readonly>
                </div>
                <div class="form-group">
                    <label for="inputRole">Role</label>
                    <input type="text" class="form-control" id="inputRole" value="<?=$user_info['role']?>" name="role" readonly>
                </div>
                <div class="form-group">
                    <label for="inputFullname">Full name</label>
                    <input type="text" class="form-control" id="inputFullname" value="<?=$user_info['full_name']?>" name="full_name" readonly>
                </div>
                <div class="form-group">
                    <label for="inputBirthdate">Birth date</label>
                    <input type="date" class="form-control" id="inputBirthdate" value="<?=$user_info['birth_date']?>" name="birth_date" readonly>
                </div>
                <div class="form-group">
                    <label for="inputBiography">Write something about yourself</label>
                    <input type="text" class="form-control" id="inputBiography" name="biography" value="<?=$user_info['biography']?>" readonly>
                </div>
                <div class="form-group">
                    <label for="inputStudies">Enter your studies/profession</label>
                    <input type="text" class="form-control" id="inputStudies" value="<?=$user_info['stud_prof']?>" name="stud_prof" readonly>
                </div>
                <div class="form-group">
                    <label for="inputLanguage">Enter your language</label>
                    <input type="text" class="form-control" id="inputLanguage" value="<?=$user_info['language']?>" name="language" readonly>
                </div>
                <div class="form-group">
                    <label for="inputEmail">E-mail address</label>
                    <input type="email" class="form-control" id="inputEmail" value="<?=$user_info['email']?>" name="email" readonly>
                </div>
                <div class="form-group">
                    <label for="inputPhonenum">Phone number</label>
                    <input type="tel" class="form-control" id="inputPhonenum" value="<?=$user_info['phone']?>" name="phone" readonly>
                </div>
            </form>

            <!-- Remove and edit button to edit profile of logged in user-->

            <div class="row">
                <div class="col-sm-2">
                    <a href="/ddwt20_project/myaccount/?user_id=<?= $_SESSION['user_id']?>" role="button" class="btn btn-warning">Edit profile</a>
                </div>

                <div class="col-sm-2">
                    <form action="/ddwt20_project/myaccount/remove" method="POST">
                        <input type="hidden" value="<?= $_SESSION['user_id'] ?>" name="user_id">
                        <button type="submit" class="btn btn-danger">Delete profile</button>
                    </form>
                </div>
            </div>

        </div>

        <!-- Right column -->
        <div class="col-md-4">

            <?php include $right_column ?>

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
