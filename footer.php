<script src="public/js/jquery.min.js"></script>
<script src="public/js/semantic.min.js"></script>
<script>
    $('.ui.dropdown').dropdown();
    $('.message .close')
        .on('click', function () {
            $(this)
                .closest('.message')
                .transition('fade');
        });
</script>
</body>
</html>