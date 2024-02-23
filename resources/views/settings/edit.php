<?php
// phpcs:ignoreFile
/**
 * @var string $tab
 * @var string $link
 * @var string $page_title
 */
$this->layout('layouts/settings', compact('tab', 'link', 'page_title', 'svg_icon_urls'));
?>

<?php
// Pass the PHP data to JavaScript
echo '<script type="text/javascript">';
echo 'window.svgIconUrls = ' . json_encode($svg_icon_urls) . ';';
echo '</script>';
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

<form action="admin.php?page=dt_home&tab=app&action=edit/<?php echo esc_attr($existing_data['id']); ?>" method="post"
      enctype="multipart/form-data">
    <?php wp_nonce_field('dt_admin_form', 'dt_admin_form_nonce') ?>

    <table class="widefat striped" id="ml_email_main_col_config">
        <thead>
        <tr>
            <th>Apps</th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td style="vertical-align: middle;">Name [&#63;]</td>
            <td colspan="3">
                <input style="min-width: 100%;" type="text" name="name" id="name" class="form-control"
                       value="<?php echo esc_attr($existing_data['name']); ?>" required>
            </td>
        </tr>
        <tr>
            <td style="vertical-align: middle;">Type [&#63;]</td>
            <td colspan="3">
                <select style="min-width: 100%;" id="type" required onchange="toggleURLField()" disabled>
                    <option value="" <?php echo empty($existing_data['type']) ? 'selected' : ''; ?>>Please select
                    </option>
                    <option value="Web View" <?php echo ($existing_data['type'] === 'Web View') ? 'selected' : ''; ?>>
                        Web View
                    </option>
                    <option value="Custom" <?php echo ($existing_data['type'] === 'Custom') ? 'selected' : ''; ?>>
                        Custom
                    </option>
                </select>
                <input style="min-width: 100%;" type="hidden" name="type"
                       value="<?php echo esc_attr($existing_data['type']); ?>"/>
            </td>
        </tr>
        <tr>
            <td style="vertical-align: middle;">Icon (File Upload)</td>
            <td style="vertical-align: middle;">
                <?php if (!empty($existing_data['icon'])) : ?>
                    <img src="<?php echo esc_url($existing_data['icon']); ?>" alt="Icon"
                         style="max-width: 50px; max-height: 50px;">
                <?php endif; ?>
            </td>
            <td style="vertical-align: middle;">
                <input style="min-width: 100%;" type="text" id="icon" name="icon"
                       value="<?php echo esc_url(isset($existing_data['icon']) ? $existing_data['icon'] : ''); ?>"/>
            </td>
            <td style="vertical-align: middle;">
                <a href="#" class="button change-icon-button" onclick="showPopup(); loadSVGIcons();">
                    <?php esc_html_e('Change Icon', 'disciple_tools'); ?>
                </a>
            </td>
        </tr>
        <?php if ($existing_data['type'] === 'Web View') { ?>
            <tr>
                <td style="vertical-align: middle;">URL [&#63;]</td>
                <td colspan="3">
                    <input style="min-width: 100%;" type="text" name="url" id="url" class="form-control"
                           value="<?php echo esc_url(isset($existing_data['url']) ? $existing_data['url'] : ''); ?>">
                </td>
            </tr>
        <?php } ?>
        <!--<tr>
            <td style="vertical-align: middle;">Sort [&#63;]</td>
            <td colspan="3">
                <input style="min-width: 100%;" type="number" name="sort" id="sort" class="form-control"
                       value="<?php /*echo esc_attr( $existing_data['sort'] ); */ ?>">
            </td>
        </tr>-->
        <tr>
            <td style="vertical-align: middle;">Is Hidden [&#63;]</td>
            <td colspan="3">
                <input type="checkbox" name="is_hidden" id="is_hidden"
                       value="1" <?php checked($existing_data['is_hidden'], 1); ?>>
            </td>
        </tr>
        </tbody>
    </table>

    <br>
    <span style="float:right;">
        <input type="hidden" name="edit_id" value="<?php echo esc_attr($existing_data['id']); ?>">
        <a href="admin.php?page=dt_home&tab=app"
           class="button float-right"><?php esc_html_e('Cancel', 'disciple_tools') ?></a>
        <button type="submit" name="submit" id="submit"
                class="button float-right"><?php esc_html_e('Update', 'disciple_tools') ?></button>
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


<?php //phpcs:ignoreEnd ?>

<?php $this->start('right') ?>

<!-- Add some content to the right side -->

<?php $this->stop() ?>
