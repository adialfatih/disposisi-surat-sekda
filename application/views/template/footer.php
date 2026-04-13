</div>

<script>
    window.BASE_URL = "<?= base_url(); ?>";
</script>

<script src="<?= base_url('assets/js/admin-template.js'); ?>"></script>

<?php if (!empty($page_js)): ?>
    <script src="<?= base_url('assets/js/' . $page_js); ?>"></script>
<?php endif; ?>

</body>
</html>