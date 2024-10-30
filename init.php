<?php
/*
  Plugin Name:Categorized Gallery Plugin
  Description:In this you can add images category wise,
  Version: 2.0
  Author: wamasoftware
  Author URI: http://wamasoftware.com
 */
if (!defined('ABSPATH'))
    exit;
define('ROOTDIRPATH', plugin_dir_path(__FILE__));
class Categorised_Gallery_plugin
{

    public $upload;
    public $upload_dir;
    public $dir_path;
    public $folder = 'categoryimg';
    public $basedirurl;

    public function __construct()
    {
        $this->upload = wp_upload_dir();
        $this->upload_dir = $this->upload['basedir'];
        $this->basedirurl = $this->upload['baseurl'] . '/' . $this->folder;
        $this->dir_path = $this->upload_dir . '/' . $this->folder;
        if (file_exists($this->upload_dir)) {
            if (!is_dir($this->dir_path)) {
                mkdir($this->dir_path, 0755);
                chmod($this->dir_path, 0755);
            }
        }
    }

    function CGallery_gallery_options_install()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . "galcategory"; // Create Table name galctegory
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name(
                                            `catid` int(11) NOT NULL AUTO_INCREMENT,
                                            `categorynm` varchar(255) NOT NULL,
                                            `catimage` varchar(255) NOT NULL,
                                            `show_title` BOOLEAN DEFAULT 1,
                                            `show_img_title` BOOLEAN DEFAULT 1,
                                            `date` DATE NOT NULL,
                                            `publish` int(11) NOT NULL,
                                            PRIMARY KEY (`catid`) 
                                            ) $charset_collate;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        $table_name = $wpdb->prefix . "galimage"; // Create Table name galimage
        $charset_collate = $wpdb->get_charset_collate();
        $sql1 = "CREATE TABLE $table_name(
                                            `imgid` int(11) NOT NULL AUTO_INCREMENT,
                                            `catid` int(11) NOT NULL,
                                            `img_title` varchar(255) NULL,
                                            `imagenm` varchar(255) NOT NULL,
                                            `imagecrop` varchar(255) NOT NULL,
                                            `publish` int(11) NOT NULL,
                                            `catpub` int(11) NOT NULL,
                                            `priority` int(11) NULL DEFAULT                                                 0,
                                            `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                            PRIMARY KEY (`imgid`) 
                                            ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql1);
    }

    function CGallery_gallery_menu()
    {

        $addgalleryimages = new CGallery_AddGalleryImage();
        $addgallery = new CGallery_AddNewgallery();
        $deletegalleryimages = new CGallery_DeleteGalleryImages();
        $deletegallerytitle = new CGallery_DeleteGalleryTitle();
        $listgallerytitle = new CGallery_ListGalleryTitle();
        $updatepublishgallery = new CGallery_UpdatePublishGallery();
        $imageresizecrop = new CGallery_ImageresizeCrop();
        $agal_settings = new CGallery_agalsettings();
        add_menu_page('Gallery', 'Gallery', 'manage_options', 'gallery_list', array($listgallerytitle, 'CGallery_list_gallery_images'), plugin_dir_url(__FILE__) . 'icons/gallery-icon.png');
        add_submenu_page('gallery_list', 'Add New Gallery ', 'Add New Gallery ', 'manage_options', 'add_new_gallery_images', array($addgallery, 'CGallery_add_new_gallery_images'));
        add_submenu_page(null, 'List gallery album', 'list gallery album', 'manage_options', 'add_gallary_images', array($addgalleryimages, 'CGallery_add_gallary_images_list'));
        add_submenu_page(null, 'delete gallery album', 'delete gallery album', 'manage_options', 'delete_gallery_album', array($deletegalleryimages, 'CGallery_delete_gallery_album'));
        add_submenu_page(null, 'delete multiple images', 'delete multiple images', 'manage_options', 'delete_multiple_image', array($deletegalleryimages, 'CGallery_delete_multiple_image'));
        add_submenu_page(null, 'delete gallery title', 'delete gallery title', 'manage_options', 'delete_gallery_title', array($deletegallerytitle, 'CGallery_delete_gallery_title'));
        add_submenu_page(null, 'user gallery publish', 'user gallery publish', 'manage_options', 'update_publish_gallery_image', array($updatepublishgallery, 'CGallery_update_publish_gallery_image'));
        add_submenu_page(null, 'user album publish', 'user album publish', 'manage_options', 'update_publish_gallery_album', array($updatepublishgallery, 'CGallery_update_publish_gallery_album'));
        add_submenu_page(null, 'user image crop', 'user image crop', 'manage_options', 'image_resize_crop1', array($imageresizecrop, 'CGallery_image_resize_crop1'));
        add_submenu_page(null, 'user image reset', 'user image reset', 'manage_options', 'reset_image', array($imageresizecrop, 'CGallery_reset_image'));

        add_submenu_page('gallery_list', 'Settings', 'Settings', 'manage_options', 'agal_settings_gallery', array($agal_settings, 'agallery_settings'));
    }
    /**
     * 
     * @global type $wpdb
     */
    function CGallery_gallery_plugin_remove_database()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . "galcategory";
        $sql = "DROP TABLE IF EXISTS $table_name;";
        $wpdb->query($sql);
        delete_option("my_plugin_db_version");

        $table_name = $wpdb->prefix . "galimage";
        $sql1 = "DROP TABLE IF EXISTS $table_name;";
        $wpdb->query($sql1);
        delete_option("my_plugin_db_version");

        // Remove Created Directory and Images
        chmod($this->dir_path, 0755);
        $dp = opendir($this->dir_path);
        while ($File = readdir($dp)) {
            if ($File != "." and $File != "..") {
                if (is_dir($File)) {
                    chmod($File, 0755);
                    chmod_r($this->dir_path . "/" . $File);
                } else {
                    chmod($this->dir_path . "/" . $File, 0755);
                }
            }
        }
        closedir($dp);
        array_map('unlink', glob("$this->dir_path/*.*"));
        rmdir($this->dir_path);
    }

    function CGallery_add_css_js_galleryplug()
    {
        // wp_enqueue_script('cropimagejquery', includes_url() . "/js/jcrop/jquery.Jcrop.min.js");
        wp_enqueue_script('imgareaselect');
        wp_enqueue_script('jcrop');
        wp_enqueue_script('formvalid', plugins_url('/js/form_valid.js', __FILE__));
        wp_enqueue_style('inkthemes3', plugins_url("/css/jquery.Jcrop.css", __FILE__));
        wp_enqueue_style('inkthemes35', plugins_url("/css/style.css", __FILE__));

        //drag and drop sequence
        // wp_enqueue_script('jquery');
        // wp_enqueue_style('smoothness-jqueryui-css', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.24/themes/smoothness/jquery-ui.css');
        // wp_enqueue_script('smoothness-jqueryui-js', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.24/jquery-ui.min.js');
        wp_enqueue_script('gal-custom', plugins_url('/js/custom.js', __FILE__));
    }

    function CGallery_add_js_head()
    {
        wp_enqueue_script('fancyboxjs', plugins_url('js/jquery.fancybox.min.js', __FILE__), array('jquery'), '', false);
        wp_enqueue_style('fancyboxcss', plugins_url("/css/jquery.fancybox.min.css", __FILE__));
    }

    function CGallery_custom_js_css()
    {
        wp_enqueue_script('fancycustomjs', plugins_url("/js/fancybox.js", __FILE__));
        wp_enqueue_style('fancycustomcss', plugins_url("/css/fanybox.css", __FILE__));
    }
    /**
     * Generate shortcode for gallery
     * @global type $wpdb
     * @param type $attr
     */
    function CGallery_category_shortcode($attr)
    {

        //require_once(ROOTDIRPATH . 'html/shortcodescript.html');
        if (!empty($attr)) {
            $cat_id = $attr['field'];
            if (!array_key_exists('column', $attr) || $attr['column'] == null) {
                $column = 3;
            } else {
                $column = $attr['column'];
            }
            global $wpdb;
            $tblname = $wpdb->prefix . "galimage";
            $tblname2 = $wpdb->prefix . "galcategory";
            $publishedImage = $wpdb->get_results("SELECT * from $tblname WHERE catid = '$cat_id' AND publish='1' AND catpub='1' ORDER BY priority, imgid");
            $unpublishedImage = $wpdb->get_results("SELECT * from $tblname WHERE catid = '$cat_id' ORDER BY priority, imgid");
            $gal_details = $wpdb->get_results("SELECT * from $tblname2 WHERE catid = '$cat_id'");

            if (!empty($publishedImage)) {
                $show_title = $gal_details[0]->show_title;
                $show_img_title = $gal_details[0]->show_img_title;
                $categorynm = $gal_details[0]->categorynm;
                // $table = "<table cellpadding='5' >";
                // $ii = 0;
                // foreach ($publishedImage as $res) :
                //     if ($ii == 0) {
                //         $table .= "<tr style='margin-top: 10px' >";
                //     }
                //     $table .= "<td> <div data-fancybox='group'>";
                //     $table .= "<img class='fancybox' src='" . $this->basedirurl . "/" . $res->imagenm . "' data-big='" . $this->basedirurl . "/" . $res->imagenm . "' style='width:150px; height: 110px;'/>";
                //     $table .= "</div> </td>";
                //     $ii++;
                //     if ($ii == $column) {
                //         $table .= "</tr>";
                //         $ii = 0;
                //     }
                // endforeach;
                // $table .= "</table>";

                $table = $show_title ? '<h2>' . $categorynm . '</h2>' : "";
                $table .= '<p class="imglist">';
                foreach ($publishedImage as $res):
                    $img_title = "";
                    if ($show_img_title) {
                        $img_title = $res->img_title;
                    }
                    $table .= '<a href="' . $this->basedirurl . '/' . $res->imagenm . '" data-fancybox="group" class="gallery_thumbnail" data-caption="' . $img_title . '">
                  <img src="' . $this->basedirurl . '/' . $res->imagenm . '" alt="' . $img_title . '" />
                </a>';
                endforeach;

                $table .= '</p>';
            } else {
                if (empty($unpublishedImage)) {
                    $table = "<b style='text-align: center;color:red;'>There is no images in this gallery.</b>";
                } else {
                    $table = "<b style='text-align: center;color:red;'>Please publish this category.</b>";
                }
            }
            return $table;
        }
    }
    function ajax_change_title_callback()
    {
        $new_title = $_POST['new_title'];
        $imgid = $_POST['imgid'];
        $where = array("imgid" => $imgid);
        $data = array("img_title" => $new_title);
        global $wpdb;
        echo $wpdb->update($wpdb->prefix . "galimage", $data, $where);
        wp_die();
    }
    function update_sequence_agallery_callback()
    {
        $new_seq = json_decode(str_replace("\\", "", $_POST['new_seq']), true);
        global $wpdb;
        $tblname = $wpdb->prefix . "galimage";
        $query = "UPDATE " . $tblname . "
        SET priority = 
          CASE";
        if (!empty($new_seq)) {
            foreach ($new_seq as $seq_value) {
                $query .= " WHEN imgid = '" . array_keys($seq_value)[0] . "' THEN " . array_values($seq_value)[0];
            }
        }
        $query .= " ELSE priority";
        $query .= " END";
        $results = $wpdb->get_results($query);
        wp_die($results);
    }

    // add link on plugin list page
    function plugin_addnew_agallery_link($links)
    {
        $gallery_link = "<a href='" . get_admin_url() . "/admin.php?page=add_new_gallery_images'>Add New Gallery</a>";
        array_unshift($links, $gallery_link);
        return $links;
    }
    // add link on plugin list page end
}


$gallery = new Categorised_Gallery_plugin();
$plugin_name = plugin_basename(__FILE__);
register_activation_hook(__FILE__, array($gallery, 'CGallery_gallery_options_install')); // Register Tables
register_deactivation_hook(__FILE__, array($gallery, 'CGallery_gallery_plugin_remove_database')); // UnRegister Tables
add_action('wp_enqueue_scripts', array($gallery, 'CGallery_add_js_head'));
add_action('wp_footer', array($gallery, 'CGallery_custom_js_css'));
add_action('admin_menu', array($gallery, 'CGallery_gallery_menu'));
add_action('admin_enqueue_scripts', array($gallery, 'CGallery_add_css_js_galleryplug'));
add_shortcode('image_gallery', array($gallery, 'CGallery_category_shortcode'));
add_filter("plugin_action_links_" . $plugin_name, array($gallery, 'plugin_addnew_agallery_link'));

add_action("wp_ajax_ajax_change_title_callback", array($gallery, "ajax_change_title_callback"));
add_action("wp_ajax_nopriv_ajax_change_title_callback", array($gallery, "ajax_change_title_callback"));

add_action("wp_ajax_update_sequence_agallery_callback", array($gallery, "update_sequence_agallery_callback"));
add_action("wp_ajax_nopriv_update_sequence_agallery_callback", array($gallery, "update_sequence_agallery_callback"));

require_once(ROOTDIRPATH . 'add_new_images.php');
require_once(ROOTDIRPATH . 'list_gallery_images.php');
require_once(ROOTDIRPATH . 'add_gallery_images.php');
require_once(ROOTDIRPATH . 'delete_gallery_album.php');
require_once(ROOTDIRPATH . 'delete_gallery_title.php');
require_once(ROOTDIRPATH . 'settings.php');
require_once(ROOTDIRPATH . 'update_publish_gallery_image.php');
require_once(ROOTDIRPATH . 'crop_image.php');
