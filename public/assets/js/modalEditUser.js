document.addEventListener('DOMContentLoaded', () => {
    const editUserModal = document.getElementById('editUserModal');

    // Rellenar el modal cuando se abre
    if (editUserModal) {
        editUserModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget; // Botón que disparó el modal

            // Obtener datos del botón
            const userId = button.getAttribute('data-user-id');
            const nombre = button.getAttribute('data-user-nombre');
            const apellido = button.getAttribute('data-user-apellido');
            const email = button.getAttribute('data-user-email');
            const telefono = button.getAttribute('data-user-telefono');
            const rol = button.getAttribute('data-user-rol');
            const colony = button.getAttribute('data-user-colony');
            const activo = button.getAttribute('data-user-activo');

            // Rellenar los campos del formulario
            document.getElementById('edit_user_id').value = userId;
            document.getElementById('edit_nombre').value = nombre;
            document.getElementById('edit_apellido').value = apellido;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_telefono').value = telefono;
            document.getElementById('edit_rol').value = rol;
            document.getElementById('edit_colony_id').value = colony || '';
            document.getElementById('edit_activo').value = activo;
            
            // Limpiar el campo de contraseña
            document.getElementById('edit_pass').value = '';
        });
    }
});