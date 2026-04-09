        </div> <!-- End Main Content -->
    </div> <!-- End Main Content Wrapper -->
</div> <!-- End d-flex -->

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Auto-hide alerts after 3 seconds and clean URL
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    if (alerts.length > 0) {
        // Remove msg parameter from URL without refreshing
        const url = new URL(window.location);
        if (url.searchParams.has('msg')) {
            url.searchParams.delete('msg');
            window.history.replaceState({}, document.title, url);
        }

        // Auto hide after 3 seconds
        setTimeout(function() {
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 3000);
    }
});
</script>
</body>
</html>

