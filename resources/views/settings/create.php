<?php
// phpcs:ignoreFile
/**
 * @var string $tab
 * @var string $link
 * @var string $page_title
 */
$this->layout('layouts/settings', compact('tab', 'link', 'page_title', 'svgIconUrls'));
?>
<?php
// Pass the PHP data to JavaScript
echo '<script type="text/javascript">';
echo 'window.svgIconUrls = ' . json_encode($svgIconUrls) . ';';
echo '</script>';
/*echo '<script src="../wp-content/plugins/dt-home/resources/js/components/app-setting.js"></script>';*/
?>
<style>
    /* Custom styles */
    .popup {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        padding: 20px;
        background-color: #fff;
        border: 1px solid #ccc;
        z-index: 1000;
        width: 60%; /* Adjust as per your requirement */
        height: 70%; /* Adjust as per your requirement */
        overflow-y: auto; /* Enables vertical scrolling */
    }

    .svg-container {
        display: flex;
        flex-wrap: wrap;
    }

    .svg-icon {
        width: 50px;
        height: 50px;
        margin: 5px;
        cursor: pointer;
    }
</style>
<form action="admin.php?page=dt_home&tab=app&action=create" method="post" enctype="multipart/form-data">
    <?php wp_nonce_field('dt_admin_form', 'dt_admin_form_nonce') ?>

    <table class="widefat striped" id="ml_email_main_col_config">
        <thead>
        <tr>
            <th>Apps</th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        <tbody>action
        <tr>
            <td style="vertical-align: middle;">Name [&#63;]</td>
            <td colspan="2">
                <input style="min-width: 100%;" class="form-control" type="text" name="name" id="name" required/>
            </td>
        </tr>
        <tr>
            <td style="vertical-align: middle;">Type [&#63;]</td>
            <td colspan="2">
                <select style="min-width: 100%;" name="type" id="type" required onchange="toggleURLField()">
                    <option value="">Please select</option>
                    <option value="Web View">Web View</option>
                    <!--<option value="Custom">Custom</option>-->
                </select>
            </td>
        </tr>
        <tr>
            <td style="vertical-align: middle;">Icon (File Upload)</td>
            <td style="vertical-align: middle;"><input style="min-width: 100%;" type="text" id="icon" name="icon"
                                                       required/></td>
            <td style="vertical-align: middle;">
                <a href="#" class="button change-icon-button" onclick="showPopup(); loadSVGIcons();">
                    <?php esc_html_e('Change Icon', 'disciple_tools'); ?>
                </a>
            </td>
        </tr>
        <tr id="urlFieldRow">
            <td style="vertical-align: middle;">URL [&#63;]</td>
            <td colspan="2">
                <input style="min-width: 100%;" type="text" name="url" id="url"/>
            </td>
        </tr>
        <!--<tr>
            <td style="vertical-align: middle;">Sort [&#63;]</td>
            <td colspan="2">
                <input style="min-width: 100%;" type="number" name="sort" id="sort" required/>
            </td>
        </tr>-->
        <tr>
            <td style="vertical-align: middle;">Is Hidden [&#63;]</td>
            <td colspan="2">
                <input type="checkbox" name="is_hidden" id="is_hidden" value="1">
            </td>
        </tr>
        </tbody>
    </table>

    <br>
    <span id="ml_email_main_col_update_msg" style="font-weight: bold; color: red;"></span>
    <span style="float:right;">
        <a href="admin.php?page=dt_home&tab=app"
           class="button float-right"><?php esc_html_e('Cancel', 'disciple_tools') ?></a>
        <button type="submit" id="ml_email_main_col_update_but"
                class="button float-right"><?php esc_html_e('Submit', 'disciple_tools') ?></button>
    </span>
</form>

<div id="popup" class="popup">
    <input type="text" id="searchInput" onkeyup="filterIcons()" placeholder="Search for icons..."
           style="width: 100%; padding: 10px; margin-bottom: 10px;">
    <div class="svg-container" id="svgContainer">
        <!-- SVG icons will be dynamically inserted here -->
    </div>
    <br>
    <button class="btn btn-secondary" onclick="hidePopup()">Close</button>
</div>

<?php $this->start('right') ?>

<!-- Add some content to the right side -->

<?php $this->stop() ?>
