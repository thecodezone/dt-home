import '../css/admin.css'
import '@disciple.tools/web-components'

/**
 * Toggles the visibility of the URL field based on the selected type.
 *
 * @function
 * @name toggleURLField
 * @returns {void}
 */

function toggleURLField() {
    var typeSelect = document.getElementById('type')
    var urlFieldRow = document.getElementById('urlFieldRow')

    if (!typeSelect || !urlFieldRow) {
        return
    }

    if (typeSelect.value === 'Custom') {
        urlFieldRow.style.display = 'none'
    } else {
        urlFieldRow.style.display = ''
    }
}

document.addEventListener('DOMContentLoaded', function () {
    toggleURLField()
})

document.addEventListener('DOMContentLoaded', function () {
    var nameInput = document.getElementById('name')
    var slugInput = document.getElementById('slug')

    if (!slugInput || !nameInput) {
        return
    }

    if (slugInput.readOnly) {
        return
    }

    if ((nameInput && slugInput.value) || !slugInput.value) {
        nameInput.addEventListener('input', function () {
            // Convert to lowercase and replace spaces with underscores

            var slug = nameInput.value
                .toLowerCase()
                .replace(/\s+/g, '_')
                // Remove characters that are not alphanumeric, underscores, or dashes
                .replace(/[^a-z0-9_\-]/g, '')
            slugInput.value = slug
        })
    }
})

document.addEventListener('DOMContentLoaded', function () {
    var inputField = document.getElementById('slug')

    if (!inputField) {
        return
    }

    inputField.addEventListener('keydown', function (event) {
        // Allow controls such as backspace
        if (
            event.key === 'Backspace' ||
            event.key === 'ArrowLeft' ||
            event.key === 'ArrowRight' ||
            event.key === 'Tab'
        ) {
            return // Allow these keys
        }

        // Build the allowed pattern
        var regex = /^[a-zA-Z0-9-_]+$/

        // Check if the pressed key combined with the current value matches the allowed pattern
        // Create the future value of the input to test with regex
        var futureValue = inputField.value + event.key
        if (!regex.test(futureValue)) {
            event.preventDefault() // Prevent the character if it does not match
        }
    })
})

/**
 * Initializes the media uploader and handles the image selection process.
 *
 * @function
 * @name initializeMediaUploader
 * @returns {void}
 */
document.addEventListener('DOMContentLoaded', function () {
    var mediaUploader

    // Add click event listener to the upload image button
    document
        .getElementById('upload_image_button')
        .addEventListener('click', function (e) {
            e.preventDefault() // Prevent the default form submission behavior

            // If the media uploader already exists, open it
            if (mediaUploader) {
                mediaUploader.open()
                return
            }

            // Create a new media uploader instance
            mediaUploader = wp.media.frames.file_frame = wp.media({
                title: 'Choose Image', // Title of the media uploader
                button: {
                    text: 'Choose Image', // Text of the select button
                },
                multiple: false, // Disable multiple file selection
                library: {
                    type: 'image', // Ensure only images are selectable
                },
            })

            // Handle the image selection event
            mediaUploader.on('select', function () {
                var attachment = mediaUploader
                    .state()
                    .get('selection')
                    .first()
                    .toJSON() // Get the selected image details

                // Check if the selected file is an image
                if (attachment.type !== 'image') {
                    alert('Please select an image file.')
                    return
                }

                // Set the selected image URL to the input field
                document.getElementById('dt_home_file_upload').value =
                    attachment.url
                // Display the selected image as a preview
                document.getElementById('image_preview').innerHTML =
                    '<img src="' + attachment.url + '" class="image-preview">'
            })

            // Open the media uploader
            mediaUploader.open()
        })
})

/**
 * Handle bulk selecting/deselecting of Apps user role options.
 *
 * @function
 * @name bulkAppsUserRoleSelections
 * @returns {void}
 */
document.addEventListener('DOMContentLoaded', function () {
  const select_all = document.getElementById('select_all_user_roles');

  if (!select_all) {
    return;
  }

  // Listen for select all app user role clicks.
  select_all.addEventListener('click', (e) => {
    const roles = document.querySelectorAll('input.apps-user-role');
    for (let i = 0; i < roles.length; i++) {

      // Accordingly select/deselect user roles.
      roles[i].checked = select_all.checked;
    }
  });

  // Listen for individual user role clicks and update parent all option accordingly.
  for (const role of document.querySelectorAll('input.apps-user-role')) {
    role.addEventListener('click', (e) => {
      if (!role.checked) {
        select_all.checked = false;
      }
    });
  }

  // Execute final pre-submission tasks.
  document
  .getElementById('submit')
  .addEventListener('click', (e) => {

    // Capture unselected roles, to ensure they are removed within the backend.
    const deleted_roles_element = document.getElementById('deleted_roles');
    if (deleted_roles_element) {

      let deleted_roles = [];
      for (const role of document.querySelectorAll('input.apps-user-role')) {
        if (!role.checked) {
          deleted_roles.push(role.value);
        }
      }

      // Update deleted roles hidden field, ahead of final submission.
      deleted_roles_element.value = JSON.stringify( deleted_roles );
    }
  });
});

/**
 * Handle apps importing flow functionality.
 *
 * @function
 * @name importApps
 * @returns {void}
 */
jQuery(document).ready(function ($) {
  const import_apps_but = $('#import_apps_but');

  if (!import_apps_but) {
    return;
  }

  // Listen for import apps button clicks.
  $(import_apps_but).click(function (e) {
    const dialog = $('#apps_settings_dialog_placeholder');
    if (dialog) {

      // Configure new dialog instance.
      dialog.dialog({
        modal: true,
        autoOpen: false,
        hide: 'fade',
        show: 'fade',
        height: 'auto',
        width: 'auto',
        resizable: false,
        title: 'Import Apps',
        buttons: [
          {
            text: 'Cancel',
            icon: 'ui-icon-close',
            click: function (e) {
              $(this).dialog('close');
            }
          },
          {
            text: 'Import',
            icon: 'ui-icon-circle-arrow-n',
            click: function (e) {
              import_apps($(this));
            }
          }
        ],
        open: function (event, ui) {
        },
        close: function (event, ui) {
        }
      });

      // Populate main dialog body.
      dialog.html(build_dialog_import_apps_html());

      // Display configured dialog.
      dialog.dialog('open');
    }
  });

  function build_dialog_import_apps_html() {
    return `
        <p>Please enter below the apps settings json structure to be imported.</p>
        <textarea id="import_apps_textarea" rows="25" cols="75"></textarea>
    `;
  }

  function import_apps(dialog) {

    // Obtain handle to textarea and fetch contents.
    const import_apps_textarea = $('#import_apps_textarea');

    try {

      // Sanity check by parsing submitted content; which should be a json structure.
      const json = $.parseJSON(import_apps_textarea.val());

      // On a successful parse, proceed with import post request.
      $.ajax({
        type: 'POST',
        contentType: 'application/json; charset=utf-8',
        dataType: 'json',
        data: JSON.stringify(json),
        url: `${window.dt_admin_scripts.site_url}/wp-admin/admin.php?page=dt_home&tab=app&action=import`,
        beforeSend: (xhr) => {
          xhr.setRequestHeader('X-WP-Nonce', window.dt_admin_scripts.nonce);
        }
      }).done(function(response){
        window.location.reload();

      }).fail(function (fail) {
        window.location.reload();

      });

    } catch (err) {

      // Return focus to textarea, to prompt admin of error.
      import_apps_textarea.focus();
    }
  }

});
