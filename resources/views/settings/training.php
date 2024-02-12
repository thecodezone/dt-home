<?php
$this->layout('layouts/settings', compact('tab', 'link', 'page_title'))
?>

<form method="post">
    <?php wp_nonce_field('dt_admin_form', 'dt_admin_form_nonce') ?>

    <!-- Add a form -->
</form>

<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                    <span style="float:right;">
                        <a href="admin.php?page=dt_launcher&tab=training&action=create" class="button float-right"><i
                                class="fa fa-plus"></i> Add Training</a>
                    </span>

                <br><br>

                <table class="widefat striped" style="border-collapse: collapse; width: 100%;">
                    <thead>
                    <tr>
                        <th style="border: 1px solid #ddd;">Name</th>
                        <th style="border: 1px solid #ddd;">Embed Code</th>
                        <th style="border: 1px solid #ddd;">Anchor</th>
                        <th style="border: 1px solid #ddd;">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($data as $training) : ?>
                        <tr>
                            <td style="border: 1px solid #ddd;"><?php echo esc_html($training['name']); ?></td>
                            <td style="border: 1px solid #ddd;"><?php echo esc_html($training['embed_video']); ?></td>
                            <td style="border: 1px solid #ddd;">
                                <?php echo esc_html($training['anchor']); ?>
                            </td>

                            <td style="border: 1px solid #ddd;">
                                <a href="admin.php?page=dt_launcher&tab=training&action=up/<?php echo esc_attr($training['id']); ?>">Up</a>| &nbsp;
                                <a href="admin.php?page=dt_launcher&tab=training&action=edit/<?php echo esc_attr($training['id']); ?>">Edit</a>|&nbsp;
                                <a href="#" onclick="confirmDelete(<?php echo esc_attr($training['id']); ?>)">Delete</a>|&nbsp;
                                <a href="admin.php?page=dt_launcher&tab=training&action=down/<?php echo esc_attr($training['id']); ?>">Down</a>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
<script>
    function confirmDelete(trainingId) {
        var confirmation = confirm("Are you sure you want to delete this entry?");
        if (confirmation) {
            // If the user confirms, redirect to the delete URL
            window.location.href = "admin.php?page=dt_launcher&tab=training&action=delete/" + trainingId;
        }
        // If the user cancels, do nothing
    }
</script>
<?php $this->start('right') ?>

<!-- Add some content to the right side -->

<?php $this->stop() ?>
