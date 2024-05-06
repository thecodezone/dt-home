/**
 * Checks if the web application is installed on the user's device.
 *
 * @returns {boolean} - True if the web application is installed, false otherwise.
 */
export const isInstalled = function () {
    return (
        window.matchMedia('(display-mode: standalone)').matches ||
        window.navigator.standalone ||
        document.referrer.includes('android-app://')
    )
}

/**
 * Checks if the user's device is an Android device.
 *
 * @returns {boolean} True if the user's device is an Android device, false otherwise.
 */
export const isAndroid = function () {
    const userAgent = navigator.userAgent
    return userAgent.includes('Android')
}

/**
 * Executes the callback function when the document has finished loading.
 *
 * @param {function} callback - The function to be executed.
 */
export const loaded = function (callback) {
    if (document.readyState === 'complete') {
        callback(document)
    } else {
        document.onreadystatechange = function () {
            if (document.readyState === 'complete') {
                callback(document)
            }
        }
    }
}

/**
 * Translates a key based on the selected language.
 *
 * @param {string} key - The key to be translated.
 * @param language
 * @returns {string} - The translated value of the key.
 */
export const translate = function (key, language = 'en') {
    const translations = {
        en: {
            installAppLabel: 'Install as App',
            hiddenAppLabel: 'Hidden Apps',
            buttonLabel: 'Ok',
        },
    }

    // Check if the key exists in translations for the selected language
    if (translations[language] && translations[language][key]) {
        return translations[language][key]
    } else {
        // If the translation is not found, return the key itself
        return key
    }
}

export const route_url = function (route) {
    return $home.route_url + route.replace(/^\/+/, '')
}

export const magic_url = function (route) {
    return $home.magic_url + '/' + route.replace(/^\/+/, '')
}
