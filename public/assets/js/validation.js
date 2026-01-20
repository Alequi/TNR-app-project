// Validación del formulario de cambio de contraseña
        document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (newPassword !== confirmPassword) {
                e.preventDefault();
                alert('Las contraseñas no coinciden');
                return false;
            }
            
            if (newPassword.length < 6) {
                e.preventDefault();
                alert('La contraseña debe tener al menos 6 caracteres');
                return false;
            }
        });

        // Auto-cerrar alertas después de 5 segundos
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);