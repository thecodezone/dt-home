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
