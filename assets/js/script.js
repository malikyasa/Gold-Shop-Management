// Auto-dismiss flash alerts after 5 seconds
document.querySelectorAll('.alert').forEach(function (el) {
    setTimeout(function () { el.style.display = 'none'; }, 5000);
});
