<?php
/**
 * Class YandexСleanWeb
 */
class YandexСW
{
    public static $api_key = 'undefined';

    const GET_CAPTCHA = 'http://cleanweb-api.yandex.ru/1.0/get-captcha';
    const CHECK_CAPTCHA = 'http://cleanweb-api.yandex.ru/1.0/check-captcha';
    const CHECK_SPAM = 'http://cleanweb-api.yandex.ru/1.0/check-spam';


    /* Отправка запроса сервису */
    private function xmlQuery ($url, $parameters = array(), $post = false) {
        if (!isset($parameters[ 'key' ]))
            $parameters[ 'key' ] = self::$api_key;

        $parameters_query = http_build_query($parameters);

        if ($post) {
            $http_options = array(
                'http' => array(
                    'method' => 'POST',
                    'content' => $parameters_query
                )
            );

            $context = stream_context_create($http_options);
            $contents = file_get_contents($url, false, $context);

        } else $contents = file_get_contents($url . '?' . $parameters_query);

        if (!$contents)
            return false;

        $xml_data = new SimpleXMLElement($contents);

        return $xml_data;
    }


    public function isSpam ($message_data, $return_full_data = false) {

        if (!isset($message_data[ 'ip' ]))
            $ip = $_SERVER[ 'REMOTE_ADDR' ];

        $response = self::xmlQuery(self::CHECK_SPAM, $message_data, true);
        $spam_detected = (isset($response->text[ 'spam-flag' ]) && $response->text[ 'spam-flag' ] == 'yes');

        if (!$return_full_data)
            return $spam_detected;

        return array(
            'detected' => $spam_detected,
            'request_id' => (isset($response->id)) ? $response->id : null,
            'spam_links' => (isset($response->links)) ? $response->links : array()
        );
    }


    /**
     * @param $error_image_url
     * @param $captcha_type
     * @return array
     */
    public static function getCaptcha ($error_image_url, $captcha_type) {

        $params = array(
            'type' => $captcha_type
        );

        $response = self::xmlQuery(self::GET_CAPTCHA, $params);

        if (!$response || !isset($response->captcha)) {
            return array(
                'id' => '4xx-captcha-error',
                'url' => $error_image_url
            );
        }

        return array(
            'id' => (string) $response[0]->captcha,
            'url' => (string) $response[0]->url
        );
    }


    /**
     * @param $captcha_id идентификатор капчи пришедший из ответа от сервиса при получении Капчи
     * @param $captcha_value Значение капчи введенное пользователем
     * @param null $id не требуется для работы этого метода
     * @return bool             Угадана капча или нет
     */
    public static function checkCaptcha ($captcha_id, $captcha_value, $id = null) {

        $params = array(
            'captcha' => $captcha_id,
            'value' => $captcha_value,
            'id' => $id
        );
        $response = self::xmlQuery(self::CHECK_CAPTCHA, $params);

        return isset($response->ok);
    }
}