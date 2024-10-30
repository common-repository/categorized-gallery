<div class="wrap">
    <h1>List Of Gallery Images</h1>
    <hr />
</div>
<form method="post" enctype="multipart/form-data">
    <?php wp_nonce_field('addimages', 'addimg'); ?>
    <div class="wrap manage-menus">
        <h3 class="">Add Gallery Image</h3>
        <div class="upload-images">
            <span class="dragBox">
                Drag and Drop image here
                <input type="file" multiple name="fileup[]" id="img1" required
                    onchange="validateImage('img1'); drop();">
            </span>
            <font color='red'>
                <div id="error"> </div>
            </font>
        </div>
        <div class="upload_btns">
            <input type="submit" value="Save" name="btnsave" class="button button-primary button-large upload_image">
            <button type="Button" onclick="javascript:window.location = '<?php echo esc_url($this->url) ?>';"
                class="button button-primary button-large">Back</button>
            <img class="uploading_loader"
                src="<?php echo plugins_url('categorized-gallery') . '/icons/optimized_loader.gif'; ?>">
        </div>
    </div>
</form>

<style>
    .uploading_loader {
        max-height: 30px;
        display: none;
    }

    .upload_btns {
        padding: 10px 0;
    }

    .dragBox {
        width: 100%;
        height: 130px;
        margin: 5px auto;
        position: relative;
        text-align: center;
        font-weight: bold;
        line-height: 120px;
        color: #999;
        border: 2px dashed #ccc;
        display: inline-block;
        transition: transform 0.3s;
        font-size: 16px;

        input[type="file"] {
            position: absolute;
            height: 100%;
            width: 100%;
            opacity: 0;
            top: 0;
            left: 0;
        }
    }

    .draging {
        transform: scale(1.1);
    }

    #preview {
        text-align: center;

        img {
            max-width: 100%
        }
    }
</style>

<script>
    function drop() {
        jQuery(".uploading_loader").show();
        setTimeout(function () {
            var upload_data = jQuery(".upload_image").trigger("click");
            if (jQuery("#error").text() == "") {
                jQuery("#error").text("Images Uploaded.");
            }
        }, 2000);
    }
</script>