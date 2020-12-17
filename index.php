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
        'url' => '/DDWT20/week2/'
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
        'DDWT20' => na('/DDWT20/', False),
        'Week 2' => na('/DDWT20/week2/', False),
        'Home' => na('/DDWT20/week2/', True)
    ]);
    $navigation = get_navigation($nav_template, 1);

    /* Page content */
    $page_subtitle = 'The online platform to list your favorite series';
    $page_content = 'On Series Overview you can list your favorite series. You can see the favorite series of all Series Overview users. By sharing your favorite series, you can get inspired by others and explore new series.';

    if ( isset($_GET['error_msg']) ) {
        $error_msg = get_error($_GET['error_msg']);
    }
    /* Choose Template */
    include use_template('main');
}



else {
    http_response_code(404);
}