export function translate(key) {
  const translations = {
    "en": {
      "installAppLabel": "Install as App",
      "hiddenAppLabel": "Hidden Apps"
    },
    // pass other land here
    /*"fr": {
      "hiddenAppLabel": "Application cachée"
    }*/
  };

  // Assuming 'en' is the default language
  const language = 'en';

  // Check if the key exists in translations for the selected language
  if (translations[language] && translations[language][key]) {
    return translations[language][key];
  } else {
    // If the translation is not found, return the key itself
    return key;
  }
}
