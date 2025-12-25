<?php
// app/views/footer.php
?>

    </div> <!-- Closing the main content div opened in header.php (with sidebar layout) -->

    <!-- Footer -->
    <footer class="bg-light border-top mt-auto">
        <div class="container-fluid py-4">
            <div class="row align-items-center">
                <div class="col-lg-6 text-center text-lg-start">
                    <p class="mb-0 text-muted">
                        &copy; <?php echo date('Y'); ?> Barangay Management System. All rights reserved.
                    </p>
                </div>
                <div class="col-lg-6 text-center text-lg-end">
                    <p class="mb-0 text-muted">
                        Developed with <i class="bi bi-heart-fill text-danger"></i> for efficient barangay administration
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Optional: Custom JavaScript -->
    <script>
        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function () {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function (alert) {
                setTimeout(function () {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>

</body>
</html>
