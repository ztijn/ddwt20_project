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
        'url' => '/DDWT20/week2/overview/'
    ),
    3 => Array(
        'name' => 'Add rooms',
        'url' => '/ddwt20_project/add_room/'
    ),
    4 => Array(
        'name' => 'My Account',
        'url' => '/ddwt20_project/myaccount/'
    ),
    5 => Array(
        'name' => 'Register',
        'url' => '/ddwt20_project/register/'
    ),
    6 => Array(
        'name' => 'Login',
        'url' => '/ddwt20_project/login/'
    ));

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
        'DDWT20' => na('/DDWT20/', False),
        'Week 2' => na('/DDWT20/week2/', False),
        'Register' => na('/ddwt20_project/register', True)
    ]);
    $navigation = get_navigation($nav_template, 5);

    /* Page content */
    $page_subtitle = 'Registration page';
    $form_action = '/ddwt20_project/register/';

    /* Get error msg from POST route */
    if ( isset($_GET['error_msg']) ) { $error_msg = get_error($_GET['error_msg']); }

    /* Choose Template */
    include use_template('register');

}

/* register post */
elseif (new_route('/ddwt20_project/register/', 'post')){
    /* Register user */
    $error_msg = register_user($database, $_POST);
    /* Redirect to homepage */
    redirect(sprintf('/ddwt20_project/register/?error_msg=%s',
        json_encode($error_msg)));

    /* Page info */
    $page_title = 'My Account';
    $breadcrumbs = get_breadcrumbs([
        'DDWT20' => na('/DDWT20/', False),
        'Week 2' => na('/DDWT20/week2/', False),
        'Register' => na('/DDWT20/week2/myaccount', True)
    ]);
    $navigation = get_navigation($nav_template, 5);

    /* Page content */
    $page_subtitle = 'Registration page';
    $page_content = 'Here you can register your account';

    /* Register user */
    $error_msg = register_user($database, $_POST);
    /* Redirect to homepage */
    redirect(sprintf('/ddwt20_project/register/?error_msg=%s',
        json_encode($error_msg)));

    /* Choose Template */
    include use_template('register');

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
    $page_subtitle = 'Here are the details of your current account';
    $page_content = 'Account:';
    $user = get_user($database, get_user_id());

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
    /* Page info */
    $page_title = 'Add Rooms';
    $breadcrumbs = get_breadcrumbs([
        'Home' => na('/ddwt20_project/', False),
        'Add room' => na('/ddwt20_project/add_room/', True)
    ]);
    $navigation = get_navigation($nav_template, 2);

    /* Page content */
    $page_subtitle = 'Here you can add a room for listing as a tenant';
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

    $feedback = add_room($database, $_POST, $_SESSION['user_id']);
    redirect(sprintf('/ddwt20_project/add_room/?error_msg=%s', json_encode($feedback)));
}

/* Login GET */
elseif (new_route('/ddwt20_project/login/', 'get')) {
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


else {
    http_response_code(404);
}