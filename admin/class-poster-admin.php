<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://artsunique.de
 * @since      1.0.0
 *
 * @package    Poster
 * @subpackage Poster/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Poster
 * @subpackage Poster/admin
 * @author     Andreas <a.burget@artsunique.de>
 */
class Poster_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     * @since    1.0.0
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/poster-admin.css', array(), $this->version, 'all');
    }

    public function enqueue_scripts()
    {
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/poster-admin.js', array('jquery'), $this->version, false);
        wp_enqueue_script('alpinejs', 'https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js', array(), '2.8.2', true);
    }


}

add_action('admin_menu', 'poster_admin_menu');

function poster_admin_menu()
{
    add_menu_page(
        'Quick SEO Text Generator', // Page Title
        'QSEO', // Menu Title
        'manage_options', // Capability
        'qseo', // Menu Slug
        'poster_admin_page', // Function
        'dashicons-beer', // Icon URL
        100 // Position
    );
}


function poster_admin_page()
{
    // Explanation of what the "Quick SEO Text Generator" plugin does
    $description = "The Quick SEO Text Generator plugin simplifies the process of creating custom posts with a focus on generating SEO-friendly content. With this tool, you can easily define post titles, content, and even include images. It provides options to choose post types, templates, and add tags and categories. This plugin streamlines the creation of multiple posts with SEO optimization in mind, making it a valuable asset for content creators and website administrators.";

    // Check if a transient is set
    $created_posts = get_transient('poster_created_posts');

    if ($created_posts) {
        echo '<div class="notice notice-success is-dismissible">
            <p>The following posts have been created:</p>
            <ul>';
        foreach ($created_posts as $title) {
            echo '<li>' . esc_html($title) . '</li>';
        }
        echo '</ul>
        </div>';

        // Delete the transient to avoid showing the message again
        delete_transient('poster_created_posts');
    }
    ?>
    <style>
        .poster-form-container {
            background-color: #f1f1f1;
            padding: 6rem;
            border-radius: 5px;
            width: 80%;
            margin: auto;
        }

        .poster-form-container h1 {
            color: #333;
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .poster-form-container h2 {
            color: #333;
            font-size: 1rem;
            font-weight: 800;
            margin-bottom: 4px;
            padding-bottom: 0;
        }

        .poster-form-container input[type="text"],
        .poster-form-container select,
        .poster-form-container input[type="date"] {
            width: 100%;
            padding: 10px;
            margin: 2px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .poster-form-container input[type="submit"] {
            background-color: #007cba;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .poster-form-container input[type="submit"]:hover {
            background-color: #005f8d;
        }

        /* Container für jedes Input-Feld und seine Buttons */
        .input-group {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        /* Stil für die Input-Felder */
        .input-group input[type="text"] {
            flex-grow: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-right: 10px;
            /* Abstand zwischen Input-Feld und Button */
        }

        /* Gemeinsamer Stil für beide Buttons */
        .input-group button {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            color: white;
            margin-left: 10px;
            font-size: 16px;
        }

        .input-group button:hover {
            opacity: 0.8;
        }

        /* Spezifischer Stil für 'Add'-Button */
        .input-group .btn-add {
            background-color: limegreen;
            /* Blau */
        }

        /* Spezifischer Stil für 'Remove'-Button */
        .input-group .btn-remove {
            background-color: #dc3545;
            /* Rot */
        }

        /* Optional: Stil für SVG-Icons in den Buttons */
        .input-group svg {
            width: 20px;
            height: 20px;
            vertical-align: middle;
        }
    </style>
    <div class="wrap">
        <div class="poster-form-container">
            <h1>Quick SEO Text Generator</h1>
            <p><?php echo $description; ?></p>
            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" enctype="multipart/form-data">

            <?php wp_nonce_field('my_custom_posts_action', 'my_custom_posts_nonce'); ?>
                <input
                        type="hidden"
                        name="action"
                        value="my_custom_posts_submission"
                >
                <h2>Post Date (for all Posts)</h2>
                <p>If empty = Current Date</p>
                <input
                        type="date"
                        name="post_date"
                        value="<?php echo date('Y-m-d'); ?>"
                >
                <h2>Post Title</h2>
                <div x-data="postTitleHandler()">
                    <template
                            x-for="(postTitle, index) in postTitles"
                            :key="index"
                    >
                        <div class="input-group">
                            <input
                                    type="text"
                                    x-model="postTitles[index]"
                                    name="post_titles[]"
                                    placeholder="Title"
                            >
                            <button
                                    type="button"
                                    class="btn-remove"
                                    @click="removeTitle(index)"
                            >
                                <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                        class="w-6 h-6"
                                >
                                    <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"
                                    />
                                </svg>

                            </button>

                            <button
                                    type="button"
                                    class="btn-add"
                                    @click="addTitle()"
                                    x-show="postTitles.length - 1 === index"
                            >
                                <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                        class="w-6 h-6"
                                >
                                    <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M12 4.5v15m7.5-7.5h-15"
                                    />
                                </svg>

                            </button>
                        </div>
                    </template>
                </div>

                <script>
                    function postTitleHandler() {
                        return {
                            postTitles: [''],
                            addTitle() {
                                this.postTitles.push('');
                            },
                            removeTitle(index) {
                                this.postTitles.splice(index, 1);
                            }
                        };
                    }
                </script>
                <h2>Image URL</h2>
                <p>Images will be renamed by post titles</p>
                <input type="file" name="post_image_1" accept="image/*">


                <br>
                <div style="display: flex; justify-content: space-between;">
                    <div style="flex: auto; width:100%">
                        <h2>Custom Post Type</h2>
                        <select name="post_type">
                            <?php
                            $post_types = get_post_types(array('public' => true), 'objects');
                            foreach ($post_types as $post_type) {
                                echo '<option value="' . $post_type->name . '">' . $post_type->labels->singular_name . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div style="flex: auto; width:100%">
                        <h2>Template Selection</h2>
                        <select name="post_template">
                            <?php
                            $templates = get_page_templates();
                            foreach ($templates as $template_name => $template_filename) {
                                echo '<option value="' . $template_filename . '">' . $template_name . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div style="display: flex; justify-content: space-between;">
                    <div style="flex: auto; width:100%">
                        <h2>Tags</h2>
                        <select
                                name="post_tags[]"
                                multiple="multiple"
                                style="height: 150px;"
                        >
                            <?php
                            $args = array(
                                'hide_empty' => false, // Show tags that haven't been used yet
                            );
                            $tags = get_tags($args);
                            foreach ($tags as $tag) {
                                echo '<option value="' . $tag->term_id . '">' . esc_html($tag->name) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div style="flex: auto; width:100%">
                        <h2>Categories</h2>
                        <select
                                name="post_category[]"
                                multiple="multiple"
                                style="height: 150px;"
                        >
                            <?php
                            $args = array(
                                'hide_empty' => false, // Show categories that haven't been used yet
                                'taxonomy' => 'category'
                            );
                            $categories = get_categories($args);
                            foreach ($categories as $category) {
                                echo '<option value="' . $category->term_id . '">' . esc_html($category->name) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <h2>Post Content</h2>
                <p>Use @title as Placeholder for the title, @Keyword1 … 5 for Replace, further Placeholder</p>
                <?php
                $content = '';
                $editor_id = 'post_content';
                $settings = array('textarea_name' => 'post_content');
                wp_editor($content, $editor_id, $settings);
                ?>

                <!-- Additional Keyword Fields -->
                <h2>Keywords</h2>
                <div x-data="{ keywords: [''] }">
                    <template
                            x-for="(keyword, index) in keywords"
                            :key="index"
                    >
                        <div class="input-group">
                            <h2
                                    style="width: 10rem; display:block;"
                                    x-text="'Keyword ' + (index + 1)"
                            ></h2>
                            <input
                                    type="text"
                                    x-model="keywords[index]"
                                    :name="'keywords[' + index + ']'"
                                    :placeholder="'Keyword ' + (index + 1)"
                            >

                            <!-- Remove Button -->
                            <button
                                    type="button"
                                    class="btn-remove"
                                    @click="keywords.splice(index, 1)"
                                    x-show="keywords.length > 1"
                            >
                                <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                        class="w-6 h-6"
                                >
                                    <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"
                                    />
                                </svg>

                            </button>

                            <!-- Add Button (nur für das letzte Element sichtbar) -->
                            <button
                                    type="button"
                                    class="btn-add"
                                    @click="keywords.push('')"
                                    x-show="keywords.length - 1 === index"
                            >
                                <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                        class="w-6 h-6"
                                >
                                    <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M12 4.5v15m7.5-7.5h-15"
                                    />
                                </svg>

                            </button>
                        </div>
                    </template>
                </div>


                <input
                        type="submit"
                        value="Create Posts"
                        class="button button-primary"
                >
            </form>
        </div>
    </div>

    <?php
}

add_action('admin_post_my_custom_posts_submission', 'handle_form_submission');

function handle_form_submission() {
    if (!current_user_can('publish_posts')) {
        wp_die('Permission Denied');
    }

    check_admin_referer('my_custom_posts_action', 'my_custom_posts_nonce');

    if (!isset($_POST['post_titles']) || !is_array($_POST['post_titles'])) {
        // Handle error: No Post Titles provided
        return;
    }

    require_once(ABSPATH . 'wp-admin/includes/image.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');

    $post_type = sanitize_text_field($_POST['post_type']);
    $post_template = sanitize_text_field($_POST['post_template']);
    $post_date = !empty($_POST['post_date']) ? sanitize_text_field($_POST['post_date']) : date('Y-m-d H:i:s');
    $template_content = isset($_POST['post_content']) ? wp_kses_post($_POST['post_content']) : '';
    $created_posts_titles = [];

    $keywords = $_POST['keywords'] ?? [];

    $attachment_id = null;
    $original_file = null;

    if (isset($_FILES['post_image_1']) && $_FILES['post_image_1']['error'] === UPLOAD_ERR_OK) {
        $attachment_id = media_handle_upload('post_image_1', 0);
        if (!is_wp_error($attachment_id)) {
            $original_file = get_attached_file($attachment_id);
        }
    }

    foreach ($_POST['post_titles'] as $index => $post_title) {
        $post_title = sanitize_text_field($post_title);

        $post_content = str_replace('@title', $post_title, $template_content);
        foreach ($keywords as $keywordIndex => $keyword) {
            $placeholder = "@Keyword" . ($keywordIndex + 1);
            $post_content = str_replace($placeholder, sanitize_text_field($keyword), $post_content);
        }

        $post_id = wp_insert_post([
            'post_title'    => $post_title,
            'post_content'  => $post_content,
            'post_type'     => $post_type,
            'post_status'   => 'publish',
            'post_date'     => $post_date,
            'page_template' => $post_template
        ]);

        if ($post_id && $original_file) {
            $new_filename = sanitize_title($post_title) . '.' . pathinfo($original_file, PATHINFO_EXTENSION);
            $new_file_path = dirname($original_file) . '/' . $new_filename;
            copy($original_file, $new_file_path);

            $new_attachment_id = wp_insert_attachment([
                'guid'           => $new_file_path,
                'post_mime_type' => wp_check_filetype($new_file_path)['type'],
                'post_title'     => $new_filename,
                'post_content'   => '',
                'post_status'    => 'inherit'
            ], $new_file_path, $post_id);

            $attach_data = wp_generate_attachment_metadata($new_attachment_id, $new_file_path);
            wp_update_attachment_metadata($new_attachment_id, $attach_data);

            set_post_thumbnail($post_id, $new_attachment_id);
        }

        if (isset($_POST['post_category'])) {
            $post_categories = array_map('intval', $_POST['post_category']);
            wp_set_post_categories($post_id, $post_categories);
        }

        if (isset($_POST['post_tags'])) {
            // Retrieve tag names from their IDs
            $tag_ids = $_POST['post_tags'];
            $tag_names = array_map(function($id) {
                $tag = get_tag($id);
                return $tag ? $tag->name : '';
            }, $tag_ids);

            wp_set_post_tags($post_id, $tag_names);
        }

        $created_posts_titles[] = $post_title;
    }

    set_transient('poster_created_posts', $created_posts_titles, 60);
    wp_redirect(esc_url(admin_url('admin.php?page=qseo&success=1')));
    exit;
}

