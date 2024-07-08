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
echo '<script type="text/javascript">';
echo 'window.svgIconUrls = ' . json_encode($svg_icon_urls) . ';';
echo '</script>';
?>

<form action="admin.php?page=dt_home&tab=app&action=edit/<?php echo esc_attr($existing_data['slug']); ?>" method="post"
      enctype="multipart/form-data">
    <?php wp_nonce_field('dt_admin_form_nonce') ?>

    <table class="widefat striped" id="ml_email_main_col_config">
        <thead>
        <tr>
            <th><?php esc_html_e('Apps') ?></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td style="vertical-align: middle;"><?php esc_html_e('Name') ?> [&#63;]</td>
            <td colspan="3">
                <input style="min-width: 100%;" type="text" name="name" id="name" class="form-control"
                       value="<?php echo esc_attr($existing_data['name']); ?>" required>
            </td>
        </tr>
        <tr>
            <td style="vertical-align: middle;"><?php esc_html_e('Type') ?> [&#63;]</td>
            <td colspan="3">
                <select style="min-width: 100%;" id="type" required onchange="toggleURLField()">
                    <option
                        value="" <?php echo empty($existing_data['type']) ? 'selected' : ''; ?>><?php esc_html_e('Please select') ?>
                    </option>
                    <option value="Web View" <?php echo ($existing_data['type'] === 'Web View') ? 'selected' : ''; ?>>
                        <?php esc_html_e('Web View') ?>
                    </option>
                    <option value="Link" <?php echo ($existing_data['type'] === 'Link') ? 'selected' : ''; ?>>
                        <?php esc_html_e('Link') ?>
                    </option>
                </select>
                <input style="min-width: 100%;" type="hidden" name="type"
                       value="<?php echo esc_attr($existing_data['type']); ?>"/>
            </td>
        </tr>
        <tr>
            <td style="vertical-align: middle;"><?php esc_html_e('Open link in new tab') ?> [&#63;]</td>
            <td colspan="2">
                <input type="checkbox" name="open_in_new_tab" id="open_in_new_tab" value="1"
                    <?php checked($existing_data['open_in_new_tab'] ?? 0, 1); ?>>
            </td>
        </tr>
        <tr>
            <td style="vertical-align: middle;"><?php esc_html_e('Icon (File Upload)') ?></td>
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

        <?php if ($existing_data['type'] === 'Web View' || $existing_data['type'] === 'Link') { ?>
            <tr>
                <td style="vertical-align: middle;"><?php esc_html_e('URL') ?> [&#63;]</td>
                <td colspan="3">
                    <input style="min-width: 100%;" type="text" name="url" id="url" class="form-control"
                           value="<?php echo esc_url(isset($existing_data['url']) ? $existing_data['url'] : ''); ?>">
                </td>
            </tr>
        <?php } ?>

        <tr>
            <td style="vertical-align: middle;"><?php esc_html_e('Slug') ?> [&#63;]</td>
            <td colspan="2">
                <input style="min-width: 100%;" type="text" name="slug" id="slug"
                       <?php if ($existing_data['type'] !== 'Web View'): ?>readonly<?php endif; ?>
                       value="<?php echo esc_attr(isset($existing_data['slug']) ? $existing_data['slug'] : ''); ?>"
                       required/>
            </td>
        </tr>
        <tr>
            <td style="vertical-align: middle;"><?php esc_html_e('Is Hidden') ?> [&#63;]</td>
            <td colspan="3">
                <input type="checkbox" name="is_hidden" id="is_hidden"
                       value="1" <?php checked($existing_data['is_hidden'], 1); ?>>
            </td>
        </tr>
        </tbody>
    </table>

    <br>
    <span style="float:right;">
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
    <button class="btn btn-secondary" onclick="hidePopup()"><?php esc_html_e('Close') ?></button>
</div>


<?php //phpcs:ignoreEnd ?>

<?php $this->start('right') ?>

<!-- Add some content to the right side -->

<?php $this->stop() ?>
