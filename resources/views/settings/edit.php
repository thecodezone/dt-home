<?php
$this->layout( 'layouts/settings', compact( 'tab', 'link', 'page_title' ) )
?>

<?php
// Define the path to the SVG directory relative to the theme's directory
$svgDirPath = get_template_directory() . '/dt-assets/images/';

// Check if the directory exists
if ( is_dir( $svgDirPath ) ) {
	// Read files from the directory
	$svgFiles = array_diff( scandir( $svgDirPath ), [ '..', '.' ] );

	// Filter out only SVG files
	$svgIconUrls = array_filter( $svgFiles, function ( $file ) use ( $svgDirPath ) {
		return pathinfo( $svgDirPath . $file, PATHINFO_EXTENSION ) === 'svg';
	} );

	// Convert file paths to URLs
	$svgIconUrls = array_map( function ( $file ) {
		// Use get_template_directory_uri() to convert the file path to a URL
		return get_template_directory_uri() . '/dt-assets/images/' . $file;
	}, $svgIconUrls );
} else {
	// Directory not found, handle this case appropriately
	$svgIconUrls = [];
	// You might want to log this error or notify the user
}

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

<form action="admin.php?page=dt_launcher&tab=app" method="post" enctype="multipart/form-data">
	<?php wp_nonce_field( 'dt_admin_form', 'dt_admin_form_nonce' ) ?>

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
                       value="<?php echo esc_attr( $existing_data['name'] ); ?>" required>
            </td>
        </tr>
        <tr>
            <td style="vertical-align: middle;">Type [&#63;]</td>
            <td colspan="3">
                <select style="min-width: 100%;" id="type" required onchange="toggleURLField()" disabled>
                    <option value="" <?php echo empty( $existing_data['type'] ) ? 'selected' : ''; ?>>Please select
                    </option>
                    <option value="Web View" <?php echo ( $existing_data['type'] === 'Web View' ) ? 'selected' : ''; ?>>
                        Web View
                    </option>
                    <option value="Custom" <?php echo ( $existing_data['type'] === 'Custom' ) ? 'selected' : ''; ?>>
                        Custom
                    </option>
                </select>
                <input style="min-width: 100%;" type="hidden" name="type"
                       value="<?php echo esc_attr( $existing_data['type'] ); ?>"/>
            </td>
        </tr>
        <tr>
            <td style="vertical-align: middle;">Icon (File Upload)</td>
            <td style="vertical-align: middle;">
				<?php if ( ! empty( $existing_data['icon'] ) ) : ?>
                    <img src="<?php echo esc_url( $existing_data['icon'] ); ?>" alt="Icon"
                         style="max-width: 50px; max-height: 50px;">
				<?php endif; ?>
            </td>
            <td style="vertical-align: middle;">
                <input style="min-width: 100%;" type="text" id="icon" name="icon"
                       value="<?php echo esc_url( isset( $existing_data['icon'] ) ? $existing_data['icon'] : '' ); ?>"/>
            </td>
            <td style="vertical-align: middle;">
                <a href="#" class="button change-icon-button" onclick="showPopup(); loadSVGIcons();">
					<?php esc_html_e( 'Change Icon', 'disciple_tools' ); ?>
                </a>
            </td>
        </tr>
		<?php if ( $existing_data['type'] === 'Web View' ) { ?>
            <tr>
                <td style="vertical-align: middle;">URL [&#63;]</td>
                <td colspan="3">
                    <input style="min-width: 100%;" type="text" name="url" id="url" class="form-control"
                           value="<?php echo esc_url( isset( $existing_data['url'] ) ? $existing_data['url'] : '' ); ?>">
                </td>
            </tr>
		<?php } ?>
        <tr>
            <td style="vertical-align: middle;">Sort [&#63;]</td>
            <td colspan="3">
                <input style="min-width: 100%;" type="number" name="sort" id="sort" class="form-control"
                       value="<?php echo esc_attr( $existing_data['sort'] ); ?>">
            </td>
        </tr>
        <tr>
            <td style="vertical-align: middle;">Is Hidden [&#63;]</td>
            <td colspan="3">
                <input type="checkbox" name="is_hidden" id="is_hidden"
                       value="1" <?php checked( $existing_data['is_hidden'], 1 ); ?>>
            </td>
        </tr>
        </tbody>
    </table>

    <br>
    <span style="float:right;">
        <input type="hidden" name="edit_id" value="<?php echo esc_attr( $existing_data['id'] ); ?>">
        <a href="admin.php?page=dt_launcher&tab=app"
           class="button float-right"><?php esc_html_e( 'Cancel', 'disciple_tools' ) ?></a>
        <button type="submit" name="submit" id="submit"
                class="button float-right"><?php esc_html_e( 'Update', 'disciple_tools' ) ?></button>
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

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-U7I5ERqjFqH7CO/SeEe/AJSRJ53oW5sPAsMJWMxXpqpYkyZ3Z0ZEwU5pdA9F6VhN"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgydN1uhFjXM+2lkfOExlqAtFaJ3Rl8cgxgAIf5PpKeVQgb9cVCobmQMj2O"
        crossorigin="anonymous"></script>

<script>
    // Function to show the popup
    function showPopup() {
        document.getElementById("popup").style.display = "block";
        loadSVGIcons(); // Load icons when the popup is shown
    }

    // Function to hide the popup
    function hidePopup() {
        document.getElementById("popup").style.display = "none";
    }

    // Function to load SVG icons into the popup
    function loadSVGIcons() {
        var container = document.getElementById("svgContainer");
        container.innerHTML = ''; // Clear existing content

        var svgIconUrls = <?php echo json_encode( array_values( $svgIconUrls ) ); ?>;

        svgIconUrls.forEach(function (url) {
            var img = document.createElement("img");
            img.src = url;
            img.classList.add("svg-icon");
            img.onclick = function () {
                // Set the icon URL in the text field and close the popup
                document.getElementById("icon").value = url;
                hidePopup();
            };
            container.appendChild(img);
        });
    }

    function filterIcons() {
        var input, filter, container, img, i, txtValue;
        input = document.getElementById("searchInput");
        filter = input.value.toUpperCase();
        container = document.getElementById("svgContainer");
        img = container.getElementsByTagName("img");

        // Loop through all icons and hide those that don't match the search query
        for (i = 0; i < img.length; i++) {
            txtValue = img[i].src || img[i].alt;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                img[i].style.display = "";
            } else {
                img[i].style.display = "none";
            }
        }
    }
</script>

<?php $this->start( 'right' ) ?>

<!-- Add some content to the right side -->

<?php $this->stop() ?>
