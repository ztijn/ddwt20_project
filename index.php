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
        'name' => 'Add series',
        'url' => '/DDWT20/week2/add/'
    ),
    4 => Array(
        'name' => 'My Account',
        'url' => '/DDWT20/week2/myaccount/'
    ),
    5 => Array(
        'name' => 'Register',
        'url' => '/DDWT20/week2/register/'
    ),
    6 => Array(
        'name' => 'Login',
        'url' => '/DDWT20/week2/login/'
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



else {
    http_response_code(404);
}