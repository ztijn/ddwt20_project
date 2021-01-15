<?php
/**
 * Controller
 * User: Stijn Wijnen
 * Date: 14-11-2020
 * Time: 15:25
 */

/* Include model.php */
include 'model.php';

/* Connect to database */
$database = connect_db('localhost', 'ddwt20_project', 'ddwt20', 'ddwt20');

/**
 * all variables to remove redundant code
 */
$nbr_rooms = count_rooms($database);
$nbr_users = count_users($database);
$right_column = use_template('cards');

$nav_template = Array(

    1 => Array(
        'name' => 'Home',
        'url' => '/ddwt20_project/'
    ),
    2 => Array(
        'name' => 'Overview',
        'url' => '/ddwt20_project/overview/'
    ),
    /*3 => Array(
        'name' => 'Add rooms',
        'url' => '/ddwt20_project/add_room/'
    ),
    4 => Array(
        'name' => 'My Account',
        'url' => '/ddwt20_project/myaccount/'
    ),*/
    5 => Array(
        'name' => 'Register',
        'url' => '/ddwt20_project/register/'
    ),
    6 => Array(
        'name' => 'Login',
        'url' => '/ddwt20_project/login/'
    ));

if ( check_login() ) {
    $nav_template = Array(
        1 => Array(
            'name' => 'Home',
            'url' => '/ddwt20_project/'
        ),
        2 => Array(
            'name' => 'Overview',
            'url' => '/ddwt20_project/overview/'
        ),
        3 => Array(
            'name' => 'Add rooms',
            'url' => '/ddwt20_project/add_room/'
        ),
        4 => Array(
            'name' => 'My Account',
            'url' => '/ddwt20_project/myaccount/'
        ));
}

/* Landing page */
if (new_route('/ddwt20_project/', 'get')) {
    /* Page info */
    $page_title = 'Home';
    $breadcrumbs = get_breadcrumbs([
        'Home' => na('/ddwt20_project/', True)
    ]);
    $navigation = get_navigation($nav_template, 1);

    /* Page content */
    $page_subtitle = 'The online platform to lease rooms you own and find rooms available to rent!';
    $page_content = 'On this platform you can find available rooms for rent. If you are interested in the room, you can
     opt in and send the owner of the room a message. The owner will then be able to see your profile, send you messages
      and if you are lucky, you will get chosen by the owner to rent the room.';

    if ( isset($_GET['error_msg']) ) {
        $error_msg = get_error($_GET['error_msg']);
    }
    /* Choose Template */
    include use_template('main');
}

/* Register page */
/* Register get */
elseif (new_route('/ddwt20_project/register/', 'get')){
    /* Get error msg from POST route */
    if ( isset($_GET['error_msg']) ) {
        $error_msg = get_error($_GET['error_msg']);
    }

    /* Page info */
    $page_title = 'Register';
    $breadcrumbs = get_breadcrumbs([
        'Home' => na('/ddwt20_project/', False),
        'Register' => na('/ddwt20_project/register', True)
    ]);
    $navigation = get_navigation($nav_template, 5);

    /* Page content */
    $page_subtitle = 'Registration page';

    /* Get error msg from POST route */
    if ( isset($_GET['error_msg']) ) {
        $error_msg = get_error($_GET['error_msg']); }

    /* Choose Template */
    include use_template('register');
}

/* Register POST */
elseif (new_route('/ddwt20_project/register/', 'POST')) {
    $feedback = register_user($database, $_POST);
    redirect(sprintf('/ddwt20_project/register/?error_msg=%s', json_encode($feedback)));
}

/* Overview page */
elseif (new_route('/ddwt20_project/overview/', 'get')) {
    /* Page info */
    $page_title = 'Overview';
    $breadcrumbs = get_breadcrumbs([
        'Home' => na('/ddwt20_project/', False),
        'Overview' => na('/ddwt20_project/overview', True)
    ]);

    $navigation = get_navigation($nav_template, 2);

    /* Page content */
    $page_subtitle = 'The overview of all rooms';
    $page_content = 'Here you find all rooms listed.';
    $left_content = get_room_table(get_rooms($database), $database);

    if ( isset($_GET['error_msg']) ) {
        $error_msg = get_error($_GET['error_msg']);
    }

    /* Choose Template */
    include use_template('main');
}

/* Single room */
elseif (new_route('/ddwt20_project/rooms/', 'get')) {
    /* Check if logged in */
    if ( !check_login() ) {
        redirect('/ddwt20_project/login/');
    }
    /* Get rooms from db */
    $room_id = $_GET['room_id'];
    $room_info = get_room_info($database, $room_id);

    /* get the 'added by' from the database */
    $user_id = $room_info['owner'];
    $added_by = get_user($database, $user_id);

    /* Page info */
    $page_title = "Room Information";
    $breadcrumbs = get_breadcrumbs([
        'Home' => na('/ddwt20_project/', False),
        'Overview' => na('/ddwt20_project/overview/', False),
        $room_info['address'] => na('/ddwt20_project/rooms/?room_id='.$room_id, True)
    ]);

    $navigation = get_navigation($nav_template, 2);

    /* Page content */
    $page_subtitle = sprintf("Information about room %s", $room_info['address']);
    $address = $room_info['address'];
    $type = $room_info['type'];
    $price = $room_info['price'];
    $size = $room_info['size'];
    $status = $room_info['status'];

    /* Checking if user_id is equal to current logged in user */
    if (get_user_id() === $user_id) {
        $display_buttons = True;}
    else {
        $display_buttons = False;}

    /* Choose Template */
    include use_template('rooms');
}


/* My Account */
elseif (new_route('/ddwt20_project/myaccount/', 'get')) {
    /* Check if logged in */
    if ( !check_login() ) {
        redirect('/ddwt20_project/login/');
    }
    /* Page info */
    $page_title = 'My Account';
    $breadcrumbs = get_breadcrumbs([
        'Home' => na('/ddwt20_project/', False),
        'myaccount' => na('/ddwt20_project/account/', True)
    ]);

    $navigation = get_navigation($nav_template, 4);

    /* Page content */
    $page_subtitle = 'Here are the details of your current account:';
    $user = get_user($database, get_user_id());
    $user_info = user_information($database, get_user_id());

    /* user info */
    $username = $user_info['username'];
    $fullname = $user_info['full_name'];
    $role = $user_info['role'];
    $birthday = $user_info['birth_date'];
    $bio = $user_info['biography'];
    $stud_prof = $user_info['stud_prof'];
    $language = $user_info['language'];
    $email = $user_info['email'];
    $phone = $user_info['phone'];

    /* Get error from POST route */
    if ( isset($_GET['error_msg']) ) {
        $error_msg = get_error($_GET['error_msg']);
    }

    /* Choose Template */
    include use_template('account');
}

/* Logout GET*/
elseif (new_route('/ddwt20_project/logout/', 'GET')){
    /* logging out a user by clicking */
    if (!isset($_SESSION))
    {session_start();}
    $logout = logout_user();
}

/* add room get */
elseif (new_route('/ddwt20_project/add_room/', 'get')) {
    /* Check if logged in */
    if ( !check_login() ) {
        redirect('/ddwt20_project/login/');
    }

    /* Page info */
    $page_title = 'Add Rooms';
    $breadcrumbs = get_breadcrumbs([
        'Home' => na('/ddwt20_project/', False),
        'Add room' => na('/ddwt20_project/add_room/', True)
    ]);
    $navigation = get_navigation($nav_template, 3);

    /* Page content */
    $page_subtitle = 'Here you can add a room for listing';
    $page_content = 'Please fill in the form below, to make your room available';
    $submit_btn = "Add Room";
    $form_action = '/ddwt20_project/add_room/';

    if ( isset($_GET['error_msg']) ) {
        $error_msg = get_error($_GET['error_msg']);
    }

    /* Choose Template */
    include use_template('add_room');
}

/* add room post */
elseif (new_route('/ddwt20_project/add_room/', 'post')) {
    /* Check if logged in */
    if ( !check_login() ) {
        redirect('/ddwt20_project/login/');
    }

    $feedback = add_room($database, $_POST, $_SESSION['user_id']);
    redirect(sprintf('/ddwt20_project/add_room/?error_msg=%s', json_encode($feedback)));
}

/* Edit room GET */
elseif (new_route('/ddwt20_project/edit/', 'get')) {
    /* Check if logged in */
    if ( !check_login() ) {
        redirect('/ddwt20_project/login/');
    }

    /* Get room info from db */
    $room_id = $_GET['room_id'];
    $room_info = get_room_info($database, $room_id);

    /* Page info */
    $page_title = 'Edit Rooms';
    $breadcrumbs = get_breadcrumbs([
        'Home' => na('/ddwt20_project/', False),
        sprintf("Edit room %s", $room_info['address']) => na('/ddwt20_project/add_room/', True)
    ]);

    $navigation = get_navigation($nav_template, 0);

    /* Page content */
    $page_subtitle = sprintf("Edit %s", $room_info['address']);
    $page_content = 'Edit the room below.';
    $submit_btn = "Edit Rooms";
    $form_action = '/ddwt20_project/edit/';

    /* Get error from POST route */
    if ( isset($_GET['error_msg']) ) {
        $error_msg = get_error($_GET['error_msg']);
    }

    /* Choose Template */
    include use_template('add_room');
}

/* Edit room POST */
elseif (new_route('/ddwt20_project/edit/', 'post')) {
    /* Check if logged in */
    if ( !check_login() ) {
        redirect('/ddwt20_project/login/');
    }

    $feedback = update_room($database, $_POST, $_SESSION['user_id']);
    redirect(sprintf('/ddwt20_project/edit/?error_msg=%s&room_id=%d', json_encode($feedback), $_POST['room_id']));
}

/* Login GET */
elseif (new_route('/ddwt20_project/login/', 'get')) {
    /* Check if logged in */
    if ( check_login() ) {
        redirect('/ddwt20_project/myaccount/');
    }

    /* Page info */
    $page_title = 'Login';
    $breadcrumbs = get_breadcrumbs([
        'Home' => na('/DDWT20/', False),
        'myaccount' => na('/DDWT20/week2/login/', True)
    ]);

    $navigation = get_navigation($nav_template, 6);

    /* Page content */
    $page_subtitle = 'Please enter you username & password';

    /* Get error from POST route */
    if ( isset($_GET['error_msg']) ) {
        $error_msg = get_error($_GET['error_msg']);
    }

    /* Choose Template */
    include use_template('login');
}

/* Login POST */
elseif (new_route('/ddwt20_project/login/', 'post')) {
    $feedback = login_user($database, $_POST);
    redirect(sprintf('/ddwt20_project/login/?error_msg=%s', json_encode($feedback)));
}

/* Remove room */
elseif (new_route('/ddwt20_project/remove/', 'post')) {
    /* Check if logged in */
    if ( !check_login() ) {
        redirect('/ddwt20_project/login/');
    }

    $feedback = remove_serie($database, $_POST['room_id']);
    redirect(sprintf('/ddwt20_project/overview/?error_msg=%s', json_encode($feedback)));
}

else {
    http_response_code(404);
}