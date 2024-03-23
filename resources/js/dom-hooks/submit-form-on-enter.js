export default (form) => {
    form.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            form.submit();
        }
    });
}
