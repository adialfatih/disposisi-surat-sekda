</div><!-- /#content -->
</div><!-- /#main -->
 
<script>
    window.BASE_URL = "<?= base_url(); ?>";
</script>
 
<script src="<?= base_url('assets/js/admin-template.js'); ?>"></script>
 
<!-- Fitur collapse sidebar desktop (setelah admin-template.js) -->
<script src="<?= base_url('assets/js/sidebar-collapse.js'); ?>"></script>
 
<?php if (!empty($page_js)): ?>
    <script src="<?= base_url('assets/js/' . $page_js); ?>?=v2"></script>
<?php endif; ?>
 
</body>
</html>
