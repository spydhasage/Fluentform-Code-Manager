<?php
/**
 * Plugin Name: Application Code Manager
 * Plugin URI: https://damilola.online
 * Description: Manage application codes and protect Fluent Form with code validation.
 * Version: 1.6
 * Author: Damilola Ajila
 * Author URI: https://damilola.online
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: fluentform-appcode
 */

if (!defined('ABSPATH'))
    exit;

// Register admin menu
add_action('admin_menu', function () {
    add_menu_page('Application Codes', 'Application Codes', 'manage_options', 'application-codes', 'ffacm_admin_page');
});

// Admin Page
function ffacm_admin_page()
{
    if (isset($_POST['new_codes'])) {
        update_option('ffacm_codes', array_unique(array_filter(array_map('trim', explode("\n", $_POST['new_codes'])))));
        echo '<div class="updated"><p>Codes updated.</p></div>';
    }

    if (isset($_GET['delete_code'])) {
        $codeToDelete = sanitize_text_field($_GET['delete_code']);
        $codes = get_option('ffacm_codes', []);
        $codes = array_diff($codes, [$codeToDelete]);
        update_option('ffacm_codes', $codes);

        $used = get_option('ffacm_used_codes', []);
        unset($used[$codeToDelete]);
        update_option('ffacm_used_codes', $used);
    }

    $codes = get_option('ffacm_codes', []);
    $used = get_option('ffacm_used_codes', []);
    $available = array_diff($codes, array_keys($used));
    ?>
    <div class="wrap">
        <h2>Application Codes Manager</h2>
        <form method="post">
            <textarea name="new_codes" rows="10"
                style="width: 100%;"><?php echo esc_textarea(implode("\n", $codes)); ?></textarea>
            <p><button type="submit" class="button button-primary">Update Codes</button></p>
        </form>

        <h3>Used Codes</h3>
        <table class="widefat">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($used as $code => $name): ?>
                    <tr>
                        <td><?php echo esc_html($code); ?></td>
                        <td><?php echo esc_html($name); ?></td>
                        <td><a href="?page=application-codes&delete_code=<?php echo esc_attr($code); ?>"
                                class="button">Delete</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h3>Available Codes</h3>
        <table class="widefat">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($available as $code): ?>
                    <tr>
                        <td><?php echo esc_html($code); ?></td>
                        <td><a href="?page=application-codes&delete_code=<?php echo esc_attr($code); ?>"
                                class="button">Delete</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}

// AJAX: Verify code
add_action('wp_ajax_nopriv_verify_application_code', 'ffacm_verify_code');
add_action('wp_ajax_verify_application_code', 'ffacm_verify_code');
function ffacm_verify_code()
{
    $code = sanitize_text_field($_POST['code'] ?? '');
    $name = sanitize_text_field($_POST['name'] ?? '');

    $codes = get_option('ffacm_codes', []);
    $used = get_option('ffacm_used_codes', []);

    if (in_array($code, $codes) && !isset($used[$code])) {
        setcookie('ffacm_temp_code', $code, time() + 3600, COOKIEPATH, COOKIE_DOMAIN);
        wp_send_json_success(['code' => $code]);
    } else {
        wp_send_json_error(['message' => 'Invalid or used code']);
    }
}

// Enqueue script
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_script('ffacm-script', plugin_dir_url(__FILE__) . 'form-verify.js', ['jquery'], null, true);
    wp_localize_script('ffacm-script', 'ffacm_ajax', [
        'ajax_url' => admin_url('admin-ajax.php'),
    ]);
});

add_action('fluentform_submission_inserted', function ($entryId, $formId) {
    // Get entry data from Fluent Forms table
    global $wpdb;

    $entry = $wpdb->get_row(
        $wpdb->prepare("SELECT * FROM {$wpdb->prefix}fluentform_submissions WHERE id = %d", $entryId)
    );

    if (!$entry) {
        return;
    }

    $formData = json_decode($entry->response, true);

    $code = isset($formData['application_code_confirmed']) ? sanitize_text_field($formData['application_code_confirmed']) : '';

    if (!$code) {
        return;
    }

    $codes = get_option('ffacm_codes', []);
    $used = get_option('ffacm_used_codes', []);

    // Only mark as used if it exists and wasn't used yet
    if (in_array($code, $codes) && !isset($used[$code])) {
        $name = isset($formData['names']) ? sanitize_text_field($formData['names']) : 'Unknown';
        $used[$code] = $name;
        update_option('ffacm_used_codes', $used);
    }

    // Optional: delete cookie after successful submission
    setcookie('ffacm_temp_code', '', time() - 3600, COOKIEPATH, COOKIE_DOMAIN);
}, 10, 2);




// Add token for confirmation message
add_filter('fluentform_rendering_shortcodes', function ($shortcodes, $form) {
    if (isset($_COOKIE['ffacm_temp_code'])) {
        $shortcodes['application_code'] = sanitize_text_field($_COOKIE['ffacm_temp_code']);
    }
    return $shortcodes;
}, 10, 2);
