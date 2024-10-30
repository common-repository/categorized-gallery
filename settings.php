<?php
if (!defined('ABSPATH'))
    exit;
class CGallery_agalsettings
{
    function agallery_settings()
    {
        echo "<h1>Settings</h1>";
        ?>
        <style>
            .gal_settingsbox {
                padding: 10px 20px;
                background-color: #fff;
            }

            .gal_allsettings {
                padding: 10px 20px;
            }
        </style>
        <div class="gal_settingsbox">
            <div class="set_title">
                <h2>General Settings</h2>
            </div>
            <div class="gal_allsettings">
                <h4>Show Title</h4>
                <input type="checkbox" id="show_title" class="show_title">
                <label for="show_title">Show Title</label>
            </div>
        </div>
        <?php
    }
}

