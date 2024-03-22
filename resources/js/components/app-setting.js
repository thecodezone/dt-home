// myScript.js
window.showPopup = function () {
  document.getElementById("popup").style.display = "block";
  loadSVGIcons(); // Load icons when the popup is shown
}

window.hidePopup = function () {
  document.getElementById("popup").style.display = "none";
}

/**
 * Dynamically loads and displays SVG icons in a specified container element on the webpage.
 * The SVG icon URLs are expected to be provided externally, typically set on the `window.svgIconUrls` variable.
 * Each SVG icon is presented as an image element. When an icon is clicked, its URL is assigned to
 * an input element with ID 'icon', and a popup (if any) is hidden.
 *
 * This function is designed to be called when there is a need to populate a container with SVG icons,
 * for instance, after receiving SVG URL data from a server.
 *
 * @memberof YourModuleNameOrComponentName  // Replace with the actual module or component name
 * @function loadSVGIcons
 * @returns {void}
 */
function loadSVGIcons() {
  var container = document.getElementById("svgContainer");
  container.innerHTML = '';

  // The SVG URL data will be passed here from the PHP file
  var svgIconUrls = window.svgIconUrls || [];

  svgIconUrls.forEach(function (url) {
    var img = document.createElement("img");
    img.src = url;
    img.classList.add("svg-icon");
    img.onclick = function () {
      document.getElementById("icon").value = url;
      hidePopup();
    };
    container.appendChild(img);
  });
}

window.filterIcons = function () {
  var input, filter, container, img, i, txtValue;
  input = document.getElementById("searchInput");
  filter = input.value.toUpperCase();
  container = document.getElementById("svgContainer");
  img = container.getElementsByTagName("img");

  for (i = 0; i < img.length; i++) {
    txtValue = img[i].src || img[i].alt;
    if (txtValue.toUpperCase().indexOf(filter) > -1) {
      img[i].style.display = "";
    } else {
      img[i].style.display = "none";
    }
  }
}

/**
 * Toggles the visibility of the URL field based on the selected type.
 *
 * @function
 * @name toggleURLField
 * @returns {void}
 */

function toggleURLField() {
  var typeSelect = document.getElementById("type");
  var urlFieldRow = document.getElementById("urlFieldRow");
  if (typeSelect.value === "Custom") {
    urlFieldRow.style.display = "none";
  } else {
    urlFieldRow.style.display = "";
  }
}

document.addEventListener("DOMContentLoaded", function () {
  toggleURLField();
});
