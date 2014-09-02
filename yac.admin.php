<?php

add_action('admin_menu', 'yac_admin_add_page');

function yac_admin_add_page () {
    add_options_page(__('Yandex.Clean Web'), __('Yandex.Clean Web'), 'manage_options', 'yandexCleanWeb', 'yac_options_page');
}

function yac_options_page () {
    ?>
    <div class="wrap">
        <h2><?php echo __('Yandex.Clean Web - Settings', 'yandex-captcha') ?></h2>

        <form method="post">
            <?php settings_fields('yac_options'); ?>
            <?php do_settings_sections('yac'); ?>
            <?php

            if ( isset( $_POST[ 'yac_api_key' ] ) || isset( $_POST[ 'captcha-type' ] )) {
                update_option('yac_api_key', $_POST[ 'yac_api_key' ]);
                update_option('captcha-type', $_POST[ 'captcha-type']);
                echo '<div class="updated"><p>Settings updated.</p></div>';
            }

            ?>

            <table class="form-table yac-table">
                <tbody>
                <tr>
                    <th><label for="yac_api_key"><?php echo __('API-key', 'yandex-captcha'); ?></label></th>
                    <td>
                        <input name="yac_api_key" id="yac_api_key" type="text" value="<?php echo get_option('yac_api_key'); ?>" class="regular-text">
                        <p class="description"><?php echo __('Get <a href="http://api.yandex.ru/key/form.xml?service=cw" title="Get API-key" target="_blank">API-key</a>', 'yandex-captcha'); ?></p>
                    </td>
                </tr>
                </tbody>
            </table>


            <h3><?php echo __('Choose a captcha type', 'yandex-captcha')?></h3>
            <table>
                <tbody>

                <tr>
                    <td>
                        <label>
                            <input type="radio" name="captcha-type" value="std" <?php if ( get_option('captcha-type') == 'std' ) echo 'checked'; ?>/>
                            <?php echo __('Figures, Russian logo', 'yandex-captcha'); ?>
                        </label>
                    </td>
                    <td>
                        <img alt="" src="<?php echo YAC_PLUGIN_URL; ?>images/examples/std.gif" width="200" height="60">
                    </td>
                </tr>

                <tr>
                    <td>
                        <label>
                            <input type="radio" name="captcha-type" value="estd" <?php if ( get_option('captcha-type') == 'estd' ) echo 'checked'; ?>/>
                            <?php echo __('Digits, English logo', 'yandex-captcha'); ?>
                        </label>
                    </td>
                    <td>
                        <img alt="" src="<?php echo YAC_PLUGIN_URL; ?>images/examples/estd.gif" width="200" height="60">
                    </td>
                </tr>

                <tr>
                    <td>
                        <label>
                            <input type="radio" name="captcha-type" value="lite" <?php if ( get_option('captcha-type') == 'lite' ) echo 'checked'; ?>/>
                            <?php echo __('Easy-to-read digits, Russian logo', 'yandex-captcha'); ?>
                        </label>
                    </td>
                    <td>
                        <img alt="" src="<?php echo YAC_PLUGIN_URL; ?>images/examples/lite.gif" width="200" height="60">
                    </td>
                </tr>

                <tr>
                    <td>
                        <label>
                            <input type="radio" name="captcha-type" value="elite" <?php if ( get_option('captcha-type') == 'elite' ) echo 'checked'; ?>/>
                            <?php echo __('Easy to read numbers, English logo', 'yandex-captcha'); ?>
                        </label>
                    </td>
                    <td>
                        <img alt="" src="<?php echo YAC_PLUGIN_URL; ?>images/examples/elite.gif" width="200" height="60">
                    </td>
                </tr>

                <tr>
                    <td>
                        <label>
                            <input type="radio" name="captcha-type" value="rus" <?php if ( get_option('captcha-type') == 'rus' ) echo 'checked'; ?>/>
                            <?php echo __('Russian letters, Russian logo', 'yandex-captcha'); ?>
                        </label>
                    </td>
                    <td>
                        <img alt="" src="<?php echo YAC_PLUGIN_URL; ?>images/examples/rus.gif" width="200" height="60">
                    </td>
                </tr>

                <tr>
                    <td>
                        <label>
                            <input type="radio" name="captcha-type" value="latl" <?php if ( get_option('captcha-type') == 'latl' ) echo 'checked'; ?>/>
                            <?php echo __('Latin lowercase, Russian logo', 'yandex-captcha'); ?>
                        </label>
                    </td>
                    <td>
                        <img alt="" src="<?php echo YAC_PLUGIN_URL; ?>images/examples/latl.gif" width="200" height="60">
                    </td>
                </tr>

                <tr>
                    <td>
                        <label>
                            <input type="radio" name="captcha-type" value="elatl" <?php if ( get_option('captcha-type') == 'elatl' ) echo 'checked'; ?>/>
                            <?php echo __('Latin lower-case letters, English logo', 'yandex-captcha'); ?>
                        </label>
                    </td>
                    <td>
                        <img alt="" src="<?php echo YAC_PLUGIN_URL; ?>images/examples/elatl.gif" width="200" height="60">
                    </td>
                </tr>

                <tr>
                    <td>
                        <label>
                            <input type="radio" name="captcha-type" value="latu" <?php if ( get_option('captcha-type') == 'latu' ) echo 'checked'; ?>/>
                            <?php echo __('Latin capital letter, Russian logo', 'yandex-captcha'); ?>
                        </label>
                    </td>
                    <td>
                        <img alt="" src="<?php echo YAC_PLUGIN_URL; ?>images/examples/latu.gif" width="200" height="60">
                    </td>
                </tr>

                <tr>
                    <td>
                        <label>
                            <input type="radio" name="captcha-type" value="elatu" <?php if ( get_option('captcha-type') == 'elatu' ) echo 'checked'; ?>/>
                            <?php echo __('Latin capital letter, English logo', 'yandex-captcha'); ?>
                        </label>
                    </td>
                    <td>
                        <img alt="" src="<?php echo YAC_PLUGIN_URL; ?>images/examples/elatu.gif" width="200" height="60">
                    </td>
                </tr>

                <tr>
                    <td>
                        <label>
                            <input type="radio" name="captcha-type" value="latm" <?php if ( get_option('captcha-type') == 'latm' ) echo 'checked'; ?>/>
                            <?php echo __('Latin letters in mixed case, Russian logo', 'yandex-captcha'); ?>
                        </label>
                    </td>
                    <td>
                        <img alt="" src="<?php echo YAC_PLUGIN_URL; ?>images/examples/latm.gif" width="200" height="60">
                    </td>
                </tr>

                <tr>
                    <td>
                        <label>
                            <input type="radio" name="captcha-type" value="elatm" <?php if ( get_option('captcha-type') == 'elatm' ) echo 'checked'; ?>/>
                            <?php echo __('Latin letters in mixed case English logo', 'yandex-captcha'); ?>
                        </label>
                    </td>
                    <td>
                        <img alt="" src="<?php echo YAC_PLUGIN_URL; ?>images/examples/elatm.gif" width="200" height="60">
                    </td>
                </tr>
                </tbody>
            </table>

            <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
        </form>
    </div>

<?php
}

?>