<?php
/**
 * Model
 * User: reinardvandalen
 * Date: 05-11-18
 * Time: 15:25
 */

/* Enable error reporting */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
 * Connects to the database using PDO
 * @param string $host database host
 * @param string $db database name
 * @param string $user database user
 * @param string $pass database password
 * @return pdo object
 */
function connect_db($host, $db, $user, $pass){
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
    } catch (\PDOException $e) {
        echo sprintf("Failed to connect. %s",$e->getMessage());
    }
    return $pdo;
}

/**
 * Check if the route exist
 * @param string $route_uri URI to be matched
 * @param string $request_type request method
 * @return bool
 *
 */
function new_route($route_uri, $request_type){
    $route_uri_expl = array_filter(explode('/', $route_uri));
    $current_path_expl = array_filter(explode('/',parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)));
    if ($route_uri_expl == $current_path_expl && $_SERVER['REQUEST_METHOD'] == strtoupper($request_type)) {
        return True;
    }
}

/**
 * Creates a new navigation array item using url and active status
 * @param string $url The url of the navigation item
 * @param bool $active Set the navigation item to active or inactive
 * @return array
 */
function na($url, $active){
    return [$url, $active];
}

/**
 * Creates filename to the template
 * @param string $template filename of the template without extension
 * @return string
 */
function use_template($template){
    $template_doc = sprintf("views/%s.php", $template);
    return $template_doc;
}

/**
 * Creates breadcrumb HTML code using given array
 * @param array $breadcrumbs Array with as Key the page name and as Value the corresponding url
 * @return string html code that represents the breadcrumbs
 */
function get_breadcrumbs($breadcrumbs) {
    $breadcrumbs_exp = '
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb">';
    foreach ($breadcrumbs as $name => $info) {
        if ($info[1]){
            $breadcrumbs_exp .= '<li class="breadcrumb-item active" aria-current="page">'.$name.'</li>';
        }else{
            $breadcrumbs_exp .= '<li class="breadcrumb-item"><a href="'.$info[0].'">'.$name.'</a></li>';
        }
    }
    $breadcrumbs_exp .= '
    </ol>
    </nav>';
    return $breadcrumbs_exp;
}

/**
 * Pretty Print Array
 * @param $input
 */
function p_print($input){
    echo '<pre>';
    print_r($input);
    echo '</pre>';
}

/**
 * Creates HTML alert code with information about the success or failure
 * @param array $feedback Array with keys 'type' and 'message'.
 * @return string
 */
function get_error($feedback){
    $feedback = json_decode($feedback, True);
    $error_exp = '
        <div class="alert alert-'.$feedback['type'].'" role="alert">
            '.$feedback['message'].'
        </div>';
    return $error_exp;
}

/**
 * Creates navigation HTML code using given array
 * @param array $navigation Array with as Key the page name and as Value the corresponding url
 * @return string html code that represents the navigation
 */
function get_navigation($template, $active_id){
    $navigation_exp = '
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand">Series Overview</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">';
    foreach ($template as $id => $info) {
        if ($id == $active_id){
            $navigation_exp .= '<li class="nav-item active">';
            $navigation_exp .= '<a class="nav-link" href="'.$info['url'].'">'.$info['name'].'</a>';
        }else{
            $navigation_exp .= '<li class="nav-item">';
            $navigation_exp .= '<a class="nav-link" href="'.$info['url'].'">'.$info['name'].'</a>';
        }

        $navigation_exp .= '</li>';
    }
    $navigation_exp .= '
    </ul>
    </div>
    </nav>';
    return $navigation_exp;
}

function redirect($location){
    header(sprintf('Location: %s', $location));
    die();
}


function register_user($pdo, $form_data){
    /* Check if all fields are set */
    if (
        empty($form_data['username']) or
        empty($form_data['password']) or
        empty($form_data['role']) or
        empty($form_data['fullname']) or
        empty($form_data['birthdate']) or
        empty($form_date['biography']) or
        empty($form_data['studies']) or
        empty($form_data['language']) or
        empty($form_data['email']) or
        empty($form_data['phonenum'])
    ) {
        return [
            'type' => 'danger',
            'message' => 'All fields need to be filled in order for you to register!'
        ];
    }

    /* Check if user already exists */
    try {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
        $stmt->execute([$form_data['username']]);
        $user_exists = $stmt->rowCount();
    } catch (\PDOException $e) {
        return [
            'type' => 'danger',
            'message' => sprintf('There was an error: %s', $e->getMessage())
        ];
    }

    /* Return error message for existing username */
    if ( !empty($user_exists) ) {
        return [
            'type' => 'danger',
            'message' => 'The username you entered does already exists!'
        ];
    }

    /* Hash password */
    $password = password_hash($form_data['password'], PASSWORD_DEFAULT);
    /* Save user to the database */
    try {
        $stmt = $pdo->prepare('INSERT INTO users (username, password, firstname, lastname) VALUES (?, ?, ?, ?)');
        $stmt->execute([$form_data['username'], $password, $form_data['firstname'],
            $form_data['lastname']]);
        $user_id = $pdo->lastInsertId();
    } catch (PDOException $e) {
        return [
            'type' => 'danger',
            'message' => sprintf('There was an error: %s', $e->getMessage())
        ];
    }

    /* Login user and redirect */
    session_start();
    $_SESSION['user_id'] = $user_id;
    $feedback = [
        'type' => 'success',
        'message' => sprintf('%s, your account was successfully created!', get_username($pdo, $_SESSION['user_id']))
    ];
    redirect(sprintf('/ddwt20_project/myaccount/?error_msg=%s',
        json_encode($feedback)));

}

function add_room($pdo, $room_info, $current_user){
    /* Check if all fields are set */
    if (
        empty($room_info['address']) or
        empty($room_info['type']) or
        empty($room_info['price']) or
        empty($room_info['size']) or
        empty($room_info['status'])
    ) {
        return [
            'type' => 'danger',
            'message' => 'There was an error. Not all fields were filled in.'
        ];
    }

    /* Check data type */
    if (!is_numeric($room_info['price'])) {
        return [
            'type' => 'danger',
            'message' => 'There was an error. You should enter a number in the field price.'
        ];
    }

    if (!is_numeric($room_info['size'])) {
        return [
            'type' => 'danger',
            'message' => 'There was an error. You should enter a number in the field size.'
        ];
    }

    /* Check if room already exists */
    $stmt = $pdo->prepare('SELECT * FROM rooms WHERE name = ?');
    $stmt->execute([$room_info['address']]);
    $serie = $stmt->rowCount();
    if ($serie){
        return [
            'type' => 'danger',
            'message' => 'This series was already added.'
        ];
    }

    /* Add room */
    $stmt = $pdo->prepare("INSERT INTO rooms (owner, address, type , price, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
        $current_user,
        $room_info['address'],
        $room_info['type'],
        $room_info['price'],
        $room_info['size'],
        $room_info['status']
    ]);
    $inserted = $stmt->rowCount();
    if ($inserted ==  1) {
        return [
            'type' => 'success',
            'message' => sprintf("room '%s' added to room page .", $room_info['address'])
        ];
    }
    else {
        return [
            'type' => 'danger',
            'message' => 'There was an error. The room was not added. Please try it again.'
        ];
    }
}

function get_user_id(){
    if (isset($_SESSION['user_id'])){
        return $_SESSION['user_id'];
    } else {
        return False;
    }
}

function get_user($pdo, $id){
    $stmt = $pdo->prepare('SELECT full_name FROM users where user_id = ?');
    $stmt->execute([$id]);
    $user_name = $stmt->fetch();
    return sprintf("%s", htmlspecialchars($user_name['full_name']));

}

function count_users($pdo){
    /* user count */
    $stmt = $pdo->prepare('SELECT * FROM users');
    $stmt->execute();
    $user_count = $stmt->rowCount();
    return $user_count;
}

function count_rooms($pdo){
    /* room count */
    $stmt = $pdo->prepare('SELECT * FROM rooms');
    $stmt->execute();
    $series = $stmt->rowCount();
    return $series;
}

function logout_user(){
    /* empty the session and than destroy it */
    session_unset();
    session_destroy();
    $feedback = [
        'type' => 'success',
        'message' => sprintf('You were logged out')
    ];

    /* Redirect to homepage */
    redirect(sprintf('/ddwt20_project/?error_msg=%s', json_encode($feedback)));
}

function check_login(){
    session_start();
    if (isset($_SESSION['user_id'])) {
        return True;
    } else {
        return False;
    }
}

function login_user($pdo, $form_data){
    /* Check if all fields are set */
    if (
        empty($form_data['username']) or
        empty($form_data['password'])
    ) {
        return [
            'type' => 'danger',
            'message' => 'You should enter a username and password.'
        ];
    }

    /* Check if user exists */
    try {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
        $stmt->execute([$form_data['username']]);
        $user_info = $stmt->fetch();
    } catch (PDOException $e){
        return [
            'type' => 'danger',
            'message' => sprintf('There was an error: %s', $e->getMessage())
        ];
    }

    /* Return error message for wrong username */
    if (empty($user_info)){
        return [
            'type' => 'danger',
            'message' => 'The entered username does not exist in the database'
        ];
    }

    /* Check password */
    if ( !password_verify($form_data['password'], $user_info['password']) ){
        return [
            'type' => 'danger',
            'message' => 'The password you entered is incorrect!'
        ];
    } else {
        session_start();
        $_SESSION['user_id'] = $user_info['id'];
        $feedback = [
            'type' => 'success',
            'message' => sprintf('%s, you were logged in successfully!', get_user($pdo, $_SESSION['user_id']))
        ];
        redirect(sprintf('/DDWT20/week2/myaccount/?error_msg=%s', json_encode($feedback)));
    }
}
