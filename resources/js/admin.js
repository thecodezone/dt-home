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

jQuery(document).ready(function ($) {
    var mediaUploader

    $('#upload_image_button').click(function (e) {
        e.preventDefault()

        if (mediaUploader) {
            mediaUploader.open()
            return
        }

        mediaUploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image',
            },
            multiple: false,
            library: {
                type: 'image', // Ensure only images are selectable
            },
        })

        mediaUploader.on('select', function () {
            var attachment = mediaUploader
                .state()
                .get('selection')
                .first()
                .toJSON()

            if (attachment.type !== 'image') {
                alert('Please select an image file.')
                return
            }

            $('#dt_home_file_upload').val(attachment.url)
            $('#image_preview').html(
                '<img src="' + attachment.url + '" class="image-preview">'
            )
        })

        mediaUploader.open()
    })
})
