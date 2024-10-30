<div class="wrap">
    <form method="post" name="f1" Action="<?php echo admin_url('admin.php?page=delete_multiple_image'); ?>" onsubmit="return checkmultipledelete()">
        <?php wp_nonce_field('deletemultipleimages', 'deleteimages'); ?>
        <div class="seq_status"></div>
        <table id="example" class="wp-list-table widefat fixed striped pages" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <td class="manage-column column-cb check-column"><input type="checkbox" name="select_all" id="select_all" value="" onClick="EnableCheckBox(this)" /></td>
                    <th width="40px">No</th>
                    <th>Title</th>
                    <th>Image</th>
                    <th>Image name</th>
                    <th>Publish</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                foreach ($this->result as $res) {
                ?>
                    <tr>
                        <input type="hidden" value="<?php echo $res->catid; ?>" name="catid">
                        <td><input type="checkbox" name="checked_id[]" class="checkbox" value="<?php echo stripslashes($res->imgid); ?>" onClick="EnableSubmit(this)" id="cb1" />
                        </td>
                        <td>
                            <?php echo $i++; ?>
                        </td>
                        <td>
                            <label class="change_title">
                                <?php echo stripslashes($res->img_title) ? stripslashes($res->img_title) : "<span>Add Title</span>"; ?>
                            </label>
                            <p class="new_title" style="display:none;">
                                <input type="text" class="new_title_txt" value="<?php echo stripslashes($res->img_title) ? stripslashes($res->img_title) : ""; ?>">
                                <input type="hidden" class="imgid" value="<?php echo stripslashes($res->imgid); ?>">
                                <span class="close">X</span>
                            </p>
                        </td>
                        <td>
                            <a class="thumbnail-zoom" href="#thumb">
                                <img src="<?php echo $this->gallery->basedirurl . "/$res->imagenm"; ?>" width="150px" height="100px" border="0" />
                                <span>
                                    <img src="<?php echo $this->gallery->basedirurl . "/$res->imagenm"; ?>" height="250px" width="300px" />
                                </span>
                            </a>
                        </td>
                        <td>
                            <?php echo $res->imagecrop; ?>
                            <div>
                                <a href="<?php echo wp_nonce_url('admin.php?page=image_resize_crop1&id=' . $res->imgid, 'cropimages_' . $res->imgid, 'crop_image_nonce'); ?>">Crop</a>
                                <?php if ($res->imagenm != $res->imagecrop) { ?>
                                    &VerticalBar;<a href="<?php echo wp_nonce_url('admin.php?page=reset_image&id=' . $res->imgid, 'resetimage_' . $res->imgid, 'reset_image_nonce'); ?>" onclick="return checkreset()">Reset</a>
                                <?php } ?>
                            </div>
                        </td>
                        <?php
                        if ($res->publish == 1) {
                        ?>
                            <td><a href="<?php echo wp_nonce_url('admin.php?page=update_publish_gallery_album&id=' . $res->imgid . "&pubid=" . $res->publish . "&catid=" . $res->catid, 'publishimage_' . $res->imgid, 'image_publish_nonce'); ?>" title="publish" onclick="return checkunimgPublish()"><img src="<?php echo $this->plugpath . '/icons/publish.png' ?>" height="30" width="30"></a>
                            </td>
                        <?php
                        } else {
                        ?>
                            <td><a href="<?php echo wp_nonce_url('admin.php?page=update_publish_gallery_album&id=' . $res->imgid . "&pubid=" . $res->publish . "&catid=" . $res->catid, 'publishimage_' . $res->imgid, 'image_publish_nonce'); ?>" title="unpublish" onclick="return checkimgPublish()"><img src="<?php echo $this->plugpath . '/icons/unpublish.png' ?>" height="30" width="30"></a>
                            </td>
                        <?php }
                        ?>
                        <td><a href="<?php echo wp_nonce_url('admin.php?page=delete_gallery_album&id=' . $res->imgid, 'deleteimages_' . $res->imgid, 'image_delete_nonce'); ?>" onclick="return checkDeleteimg()" title="Delete"><img src="<?php echo $this->plugpath . '/icons/delete.png' ?>" height="30" width="30"></a>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
        <div class="tablenav bottom">
            <input type="submit" name="btn1" value="Remove" id="btn1" class="button button-primary button-large" disabled>
            <div class="tablenav-pages one-page">
                <span class="displaying-num">
                    <?php echo $i - 1; ?>items
                </span>
            </div>
        </div>
    </form>
</div>

<style>
    span.close {
        background-color: #e14141;
        color: #fff;
        padding: 6px 10px;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 700;
    }

    input[type="text"] {
        width: 80%;
    }

    .change_title {
        cursor: pointer;
    }

    label.change_title span {
        color: #ccc;
    }
</style>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js">
</script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.24/themes/smoothness/jquery-ui.css" />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.24/jquery-ui.min.js">
</script>
<script>
    $(function() {
        $("#example").sortable({
            items: 'tbody tr',
            dropOnEmpty: false,
            start: function(G, ui) {
                ui.item.addClass("select");
            },
            stop: function(G, ui) {
                ui.item.removeClass("select");
                let new_seq1 = [];
                var counter = 0;
                $(this).find("tr").each(function(GFG) {
                    let item = {};
                    counter = counter + 1
                    if (GFG > 0) {
                        $(this).find("td").eq(1).html(GFG);
                    }
                    if ($(this).find(".imgid").val()) {
                        item[$(this).find(".imgid").val()] = GFG;
                        new_seq1.push(item);
                    }
                });
                // console.log(new_seq1);
                jQuery.ajax({
                    type: "POST",
                    url: "<?php echo admin_url('admin-ajax.php'); ?>",
                    data: {
                        action: 'update_sequence_agallery_callback',
                        new_seq: JSON.stringify(new_seq1)
                    },
                    success: function(output) {
                        console.log(output);
                        jQuery(".seq_status").text("Saved.");
                    },
                    error: function(xhr, status, error) {
                        alert("error");
                        jQuery(".seq_status").text(xhr.responseText);
                    }
                });
            }
        });
    });
</script>
<script>
    jQuery(document).ready(function($) {
        jQuery(".change_title").on("click", function() {
            jQuery(this).parent("td").find(".new_title").show();
            jQuery(this).parents("td").find(".change_title").hide();
        });

        jQuery(".close").on("click", function() {
            jQuery(this).parents("td").find(".new_title").hide();
            jQuery(this).parents("td").find(".change_title").show();
        });

        jQuery(".new_title_txt").on("keyup", function() {
            var $this = jQuery(this);
            if ($this.val()) {
                $this.parents("td").find(".change_title").text(jQuery(this).val());
            } else {
                $this.parents("td").find(".change_title").html("<span>Add Title</span>");
            }
        });
        jQuery(".new_title_txt").on("change", function() {
            $this = jQuery(this);
            if ($this.val()) {
                $this.parents("td").find(".change_title").text(jQuery(this).val());
            } else {
                $this.parents("td").find(".change_title").html("<span>Add Title</span>");
            }
            jQuery.ajax({
                type: "POST",
                url: "<?php echo admin_url('admin-ajax.php'); ?>",
                data: {
                    action: 'ajax_change_title_callback',
                    new_title: $this.val(),
                    imgid: $this.parents("td").find(".imgid").val(),
                },
                success: function(output) {
                    if (output) {
                        $this.parents(".new_title").hide();
                        $this.parents("td").find(".change_title").show();



                        // alert("Title updated");
                    }
                }
            });
        });

    });
</script>