        </div><!-- /.admin-content -->
    </div><!-- /.admin-main -->
</div><!-- /.admin-wrap -->
<script>
// Mobile sidebar toggle
document.addEventListener('DOMContentLoaded', function() {
    const btn = document.getElementById('sidebar-toggle');
    const sidebar = document.getElementById('admin-sidebar');
    if (btn && sidebar) {
        btn.addEventListener('click', () => sidebar.classList.toggle('open'));
    }
});
</script>
</body>
</html>
