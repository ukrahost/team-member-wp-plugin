<?php
namespace TeamManager;

if (!defined('ABSPATH')) {
    exit;
}

class Metaboxes
{
    const META_DESCRIPTION = '_team_member_description';
    const META_EMAIL = '_team_member_email';
    const META_LINKEDIN = '_team_member_linkedin';

    public static function register()
    {
        add_meta_box(
            'team_manager_details',
            __('Team Member Details', 'team-manager'),
            [__CLASS__, 'render'],
            'team_member',
            'normal',
            'default'
        );
    }

    public static function render($post)
    {
        wp_nonce_field('team_manager_save_meta', 'team_manager_meta_nonce');

        $description = get_post_meta($post->ID, self::META_DESCRIPTION, true);
        $email = get_post_meta($post->ID, self::META_EMAIL, true);
        $linkedin = get_post_meta($post->ID, self::META_LINKEDIN, true);

        ?>
        <p>
            <label for="team_member_description"><?php echo esc_html__('Description / Bio', 'team-manager'); ?></label><br>
            <textarea id="team_member_description" name="team_member_description" rows="4" style="width:100%;"><?php echo esc_textarea($description); ?></textarea>
        </p>
        <p>
            <label for="team_member_email"><?php echo esc_html__('Email', 'team-manager'); ?></label><br>
            <input type="email" id="team_member_email" name="team_member_email" value="<?php echo esc_attr($email); ?>" style="width:100%;">
        </p>
        <p>
            <label for="team_member_linkedin"><?php echo esc_html__('LinkedIn Profile URL', 'team-manager'); ?></label><br>
            <input type="url" id="team_member_linkedin" name="team_member_linkedin" value="<?php echo esc_attr($linkedin); ?>" style="width:100%;">
        </p>
        <?php
    }

    public static function save($post_id, $post)
    {
        if (!isset($_POST['team_manager_meta_nonce'])) {
            return;
        }

        if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['team_manager_meta_nonce'])), 'team_manager_save_meta')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if ($post->post_type !== 'team_member') {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        $description = '';
        $email = '';
        $linkedin = '';

        if (isset($_POST['team_member_description'])) {
            $description = sanitize_textarea_field(wp_unslash($_POST['team_member_description']));
        }

        if (isset($_POST['team_member_email'])) {
            $email_candidate = sanitize_email(wp_unslash($_POST['team_member_email']));
            if ($email_candidate !== '' && is_email($email_candidate)) {
                $email = $email_candidate;
            }
        }

        if (isset($_POST['team_member_linkedin'])) {
            $linkedin_candidate = esc_url_raw(wp_unslash($_POST['team_member_linkedin']));
            if ($linkedin_candidate !== '' && strpos($linkedin_candidate, 'linkedin.com') !== false) {
                $linkedin = $linkedin_candidate;
            }
        }

        update_post_meta($post_id, self::META_DESCRIPTION, $description);

        if ($email === '') {
            delete_post_meta($post_id, self::META_EMAIL);
        } else {
            update_post_meta($post_id, self::META_EMAIL, $email);
        }

        if ($linkedin === '') {
            delete_post_meta($post_id, self::META_LINKEDIN);
        } else {
            update_post_meta($post_id, self::META_LINKEDIN, $linkedin);
        }
    }

    public static function delete($post_id)
    {
        if (get_post_type($post_id) !== 'team_member') {
            return;
        }

        delete_post_meta($post_id, self::META_DESCRIPTION);
        delete_post_meta($post_id, self::META_EMAIL);
        delete_post_meta($post_id, self::META_LINKEDIN);
    }
}
