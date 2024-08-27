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

    if (nameInput && slugInput.value) {
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
