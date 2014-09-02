<?php
/*
Plugin Name: Yandex Captcha
Text Domain: yandex-captcha
Plugin URI: http://webgarret.net/
Description: This is not just a plugin, it symbolizes the hope and enthusiasm of an entire generation summed up in two words sung most famously by Louis Armstrong: Hello, Dolly. When activated you will randomly see a lyric from <cite>Hello, Dolly</cite> in the upper right of your admin screen on every page.
Author: Yunus Gaziev
Version: 0.6
Author URI: http://webgarret.net/
*/

define('YAC_PLUGIN_URL', plugin_dir_url(__FILE__));
define('YAC_PLUGIN_PTAH', plugin_dir_path(__FILE__));
define('YAC_CAPTCHA_ERROR', YAC_PLUGIN_URL . 'images/captcha-error.gif');


if ( is_admin() ) {
    require_once( YAC_PLUGIN_PTAH . 'yac.admin.php' );
}

require('yandexCW.class.php');
YandexСW::$api_key = get_option('yac_api_key');


function yandex_captcha_textdomain () {
    load_plugin_textdomain('yandex-captcha', false, dirname(plugin_basename(__FILE__)) . '/i18n/');
}
add_action('plugins_loaded', 'yandex_captcha_textdomain');


function login_ajaxurl () {
    echo '<script type="text/javascript">var ajaxurl = "' . admin_url('admin-ajax.php') . '";</script>';
}
add_action('login_head', 'login_ajaxurl');


function yac_frontend () {
    wp_enqueue_style('yac-login', YAC_PLUGIN_URL . 'styles/captcha-styles.css');
    wp_enqueue_script('yac-login', YAC_PLUGIN_URL . 'js/yac-script.js', array( 'jquery' ));
}
add_action('login_enqueue_scripts', 'yac_frontend');
add_action('admin_enqueue_scripts', 'yac_frontend');


function login_form_panel () {

    $getCaptcha = YandexСW::getCaptcha(YAC_CAPTCHA_ERROR, get_option('captcha-type'));

    echo '<div class="yac-container">'
        . '<a class="yac-link" tabindex="-1" href="http://api.yandex.ru/cleanweb/" target="_blank" title="'
        . __('Yandex.Clean Web', 'yandex-captcha') .
        '">'
        . __('Yandex.Clean Web', 'yandex-captcha')
        . '</a><br>' . '<div class="yac-picture-refresh clearfix">'
        . '<div class="yac-picture left"><img src="' . $getCaptcha[ 'url' ]
        . '"/></div>' . '<div class="yac-refresh right">'
        . '<button type="button" tabindex="-1" class="refresh-button" title="'
        . __('Update picture', 'yandex-captcha')
        . '"><i class="yac-icon refresh"></i><i class="yac-icon refreshing hidden"></i></button>'
        . '</div>'
        . '</div>'
        . '<input class="yac-input" type="text" name="captcha-input" id="captcha-input" size="20" placeholder="' . __('Type text from picture', 'yandex-captcha') . '">'
        . '<input class="hidden" type="hidden" name="captcha-code" id="captcha-code" value="' . $getCaptcha[ 'id' ] . '">'
        . '</div>';
}
add_action('login_form', 'login_form_panel');


//add_action('lostpassword_form', 'login_form_panel');



add_filter('authenticate', 'yac_auth_signon', 30, 3);
function yac_auth_signon ($user, $username, $password) {

    /**
     * если Логин или Пароль не введены, то, возвращаем ошибку "Не введены, логин или пароль"
     */
    if ( empty($username) || empty($password) ) {
        return new WP_Error('empty_request', sprintf( __("<strong>Sorry:</strong> Not entered login or password", "yandex-captcha") ));
    }

    /**
     * проверяем ввел ли пользователь Капчу
     */
    $captchaInput = isset($_POST[ 'captcha-input' ]) ? $_POST[ 'captcha-input' ] : '';

    if (empty($captchaInput)) {
        return new WP_Error('empty_captcha', sprintf( __("<strong>ERROR:</strong> Enter text from the picture", "yandex-captcha") ));
    }


    $checkCaptchaAjax = YandexСW::checkCaptcha(urlencode($_POST['captcha-code']), urlencode($_POST['captcha-input']));

    if (!$checkCaptchaAjax) {
        return new WP_Error('wrong_captcha', sprintf( __("<strong>ERROR:</strong> Wrong text from the picture", "yandex-captcha") ));
    }


    $user = get_user_by('login', $username);
    if (!$user) {
        return new WP_Error('login_error', sprintf( __( '<strong>ERROR</strong>: Invalid username or password <a href="%s" title="Password Lost and Found">Lost your password</a>?' ), wp_lostpassword_url() ));
    }

    if ( !wp_check_password($password, $user->user_pass, $user->ID) ) {
        return new WP_Error('login_error', sprintf( __( '<strong>ERROR</strong>: Invalid username or password <a href="%s" title="Password Lost and Found">Lost your password</a>?' ), wp_lostpassword_url() ));
    }

    return $user;
}


function get_captcha_ajax () {

    $getCaptchaAjax = YandexСW::getCaptcha(YAC_CAPTCHA_ERROR, get_option('captcha-type'));

    echo json_encode($getCaptchaAjax);

    exit;
}
add_action('wp_ajax_nopriv_get_captcha_ajax', 'get_captcha_ajax');
