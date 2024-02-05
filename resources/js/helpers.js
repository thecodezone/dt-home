export const isInstalled = function() {
  return (window.matchMedia('(display-mode: standalone)').matches) ||
    (window.navigator.standalone) ||
    document.referrer.includes('android-app://');
};
