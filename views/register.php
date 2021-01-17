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

        <!-- Left column -->
        <div class="col-md-12">
            <!-- Error message -->
            <?php if (isset($error_msg)){echo $error_msg;} ?>

            <h1><?= $page_title ?></h1>
            <h5><?= $page_subtitle ?></h5>

            <div class="pd-15">&nbsp;</div>

            <form action="<?= $form_action ?>" method="POST">
                <div class="form-group">
                    <label for="inputUsername">Username</label>
                    <input type="text" class="form-control" id="inputUsername" placeholder="j.jansen" name="username" <?php if (isset($user_info)){ ?> value="<?= $user_info['username'] ?>"<?php } ?> required>
                </div>
                <?php if (!isset($user_info)){ ?>
                <div class="form-group">
                    <label for="inputPassword">Password</label>
                    <input type="password" class="form-control" id="inputPassword" placeholder="" name="password"  required>
                </div>
                <div class="form-group">
                    <label for="inputRole">Role</label>
                    <input type="text" class="form-control" id="inputRole" placeholder="owner or tenant" name="role" <?php if (isset($user_info)){ ?> value="<?= $user_info['role'] ?>"<?php } ?> required>
                </div> <?php } ?>
                <div class="form-group">
                    <label for="inputFullname">Full name</label>
                    <input type="text" class="form-control" id="inputFullname" placeholder="Jan Janssen" name="full_name" <?php if (isset($user_info)){ ?> value="<?= $user_info['full_name'] ?>"<?php } ?> required>
                </div>
                <div class="form-group">
                    <label for="inputBirthdate">Birth date</label>
                    <input type="date" class="form-control" id="inputBirthdate" placeholder="01-01-2000" name="birth_date" <?php if (isset($user_info)){ ?> value="<?= $user_info['birth_date'] ?>"<?php } ?> required>
                </div>
                <div class="form-group">
                    <label for="inputBiography">Write something about yourself</label>
                    <input type="text" class="form-control" id="inputBiography" name="biography" placeholder="Write something here..." <?php if (isset($user_info)){ ?> value="<?= $user_info['biography'] ?>"<?php } ?> required>
                </div>
                <div class="form-group">
                    <label for="inputStudies">Enter your studies/profession</label>
                    <input type="text" class="form-control" id="inputStudies" placeholder="Information Science" name="stud_prof" <?php if (isset($user_info)){ ?> value="<?= $user_info['stud_prof'] ?>"<?php } ?> required>
                </div>
                <div class="form-group">
                    <label for="inputLanguage">Enter your language</label>
                    <input type="text" class="form-control" id="inputLanguage" placeholder="Dutch" name="language" <?php if (isset($user_info)){ ?> value="<?= $user_info['language'] ?>"<?php } ?> required>
                </div>
                <div class="form-group">
                    <label for="inputEmail">E-mail address</label>
                    <input type="email" class="form-control" id="inputEmail" placeholder="janjanssen@gmail.com" name="email" <?php if (isset($user_info)){ ?> value="<?= $user_info['email'] ?>"<?php } ?> required>
                </div>
                <div class="form-group">
                    <label for="inputPhonenum">Phone number</label>
                    <input type="tel" class="form-control" id="inputPhonenum" placeholder="0612345678" name="phone" <?php if (isset($user_info)){ ?> value="<?= $user_info['phone'] ?>"<?php } ?> required>
                </div>
                <?php if (isset($user_info)){ ?><input type="hidden" name="user_id" value="<?= $user_info['user_id'] ?>"><?php } ?>
                <?php if (isset($user_info)){ ?>
                <button type="submit" class="btn btn-primary">Submit</button> <?php } else { ?>
                <button type="submit" class="btn btn-primary">Register now</button> <?php } ?>
            </form>

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