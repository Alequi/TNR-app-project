document.addEventListener('DOMContentLoaded', () => {
const newUserForm = document.getElementById('newUserForm');
const newUserModal = document.getElementById('newUserModal');
const editUserForm = document.getElementById('editUserForm');




//Enviar formulario de edición de usuario
if(editUserForm) {
    editUserForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = {
            user_id: document.getElementById('edit_user_id').value,
            nombre: document.getElementById('edit_nombre').value,
            apellido: document.getElementById('edit_apellido').value,
            email: document.getElementById('edit_email').value,
            password: document.getElementById('edit_pass').value,
            telefono: document.getElementById('edit_telefono').value,
            rol: document.getElementById('edit_rol').value,
            colony_id: document.getElementById('edit_colony_id').value,
            activo: document.getElementById('edit_activo').value
        };

        try {
            const response = await fetch('../../app/actions/user/update_user_action.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            });

            const result = await response.json();

            if (result.success) {
                const modalInstance = bootstrap.Modal.getInstance(editUserModal);   
                modalInstance.hide();
                location.reload();
            } else {
                alert('Error: ' + result.message);
            }
        } catch (error) {
            alert('Error al actualizar el usuario. Por favor, inténtalo de nuevo.');
            console.error('Error:', error);
        }
    });
}

//Limpiar formulario cuando se cierra el modal
if (editUserModal) {
    editUserModal.addEventListener('hidden.bs.modal', () => {
        editUserForm.reset();
    });
}




//Enviar formulario de nuevo usuario

if(newUserForm) {
    newUserForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = {
            nombre: document.getElementById('nombre').value,
            apellido: document.getElementById('apellido').value,
            email: document.getElementById('email').value,
            password: document.getElementById('pass').value,
            telefono: document.getElementById('telefono').value,
            rol: document.getElementById('rol').value,
            colony_id: document.getElementById('colony_id').value 
        };

        try {
            const response = await fetch('../../app/actions/user/create_user_action.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            });

            const result = await response.json();

            if (result.success) {
                const modalInstance = bootstrap.Modal.getInstance(newUserModal);
                modalInstance.hide();
                location.reload();
            } else {
                alert('Error: ' + result.message);
                
            }
        } catch (error) {
            alert('Error al crear el usuario. Por favor, inténtalo de nuevo.');
            console.error('Error:', error);
        }
    });
}

// Limpiar formulario cuando se cierra el modal
if (newUserModal) {
    newUserModal.addEventListener('hidden.bs.modal', () => {
        newUserForm.reset();
    });
}

});