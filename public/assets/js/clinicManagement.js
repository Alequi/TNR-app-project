document.addEventListener('DOMContentLoaded', () => {
    const filterActive = document.getElementById('filterActive');
    const filterInactive = document.getElementById('filterInactive');
    const filterAll = document.getElementById('filterAll');
    const searchName = document.getElementById('searchName');
    const clearFiltersBtn = document.getElementById('clearFilters');

    // Filtrar tabla de clínicas

    function filterTable() {
        const activeValue = filterActive.checked;
        const inactiveValue = filterInactive.checked;
        const nameValue = searchName.value.toLowerCase();
        const rows = document.querySelectorAll('#clinicsTable tbody tr');

        rows.forEach((row) => {
            const rowName = row.getAttribute('data-name').toLowerCase();
            const rowActive = row.getAttribute('data-activa') === '1';

            // Si la fila no tiene atributos data (ej: mensaje "No hay clínicas"), ignorarla
            if (rowName === null) {
                return;
            }

            const activeMatch =
                (activeValue && rowActive) ||
                (inactiveValue && !rowActive) ||
                (!activeValue && !inactiveValue);
                
            const nameMatch = rowName.includes(nameValue);

            if (activeMatch && nameMatch) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    clearFiltersBtn.addEventListener('click', () => {
        filterActive.checked = false;
        filterInactive.checked = false;
        filterAll.checked = true;
        searchName.value = '';
        filterTable();
    });

    filterActive.addEventListener('change', filterTable);
    filterInactive.addEventListener('change', filterTable);
    filterAll.addEventListener('change', filterTable);
    searchName.addEventListener('input', filterTable);



    // Crear nueva clínica - enviar formulario
    const newClinicForm = document.getElementById('newClinicForm');
    if (newClinicForm) {
        newClinicForm.addEventListener('submit', async (e) => {

            e.preventDefault();

            const formData = {
                nombre: document.getElementById('newNombre').value,
                direccion: document.getElementById('newDireccion').value,
                telefono: document.getElementById('newTelefono').value,
                capacidad_ma: document.getElementById('newCapacidadMa').value,
                capacidad_ta: document.getElementById('newCapacidadTa').value
            };

            try {
                const response = await fetch('../../app/actions/clinics/create_clinic_action.php', {
                    method: 'POST',
                    headers: {
                        'content-type' : 'application/json'
                    },

                    body: JSON.stringify(formData)
                });

                const result = await response.json();

                if (result.success) {
                    const modalInstance = bootstrap.Modal.getInstance(newClinicModal);
                    modalInstance.hide();
                    location.reload();
                }else {
                    alert('Error: ' + result.message);
                }
            } catch (error) {
                alert('Error al crear la clínica: ' + error.message);
            
            }
        });
    }

    //Limpiar modal al cerrarlo
    if (newClinicModal) {
        newClinicModal.addEventListener('hidden.bs.modal', () => {
            newClinicForm.reset();
        });
    }

    //Desactivar clínica
    const deactivateClinicButtons = document.querySelectorAll('.deactivate-clinic-btn');
    
    deactivateClinicButtons.forEach((button) => {
        button.addEventListener('click', async function () {
            const clinicId = this.getAttribute('data-clinic-id');
            const clinicName = this.getAttribute('data-clinic-name');

            if (!confirm('¿Estás seguro de que deseas desactivar la clínica ' + clinicName + '?\n\nNo se podrá desactivar si tiene reservas activas en turnos futuros.')) {
                return;
            }
            try {
                const response = await fetch('../../app/actions/clinics/deactivate_clinic_action.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ clinic_id: clinicId })
                });

                const result = await response.json();

                if (result.success) {
                    location.reload();
                } else {
                    alert('Error: ' + result.message);
                }
            } catch (error) {
                alert('Error al desactivar la clínica. Por favor, inténtalo de nuevo.');
                console.error('Error:', error);
            }
        });
    });

    //Activar clínica
    const activateClinicButtons = document.querySelectorAll('.activate-clinic-btn');
    
    activateClinicButtons.forEach((button) => {
        button.addEventListener('click', async function () {
            const clinicId = this.getAttribute('data-clinic-id');
            const clinicName = this.getAttribute('data-clinic-name');

            if (!confirm('¿Estás seguro de que deseas activar la clínica ' + clinicName + '?')) {
                return;
            }
            try {
                const response = await fetch('../../app/actions/clinics/activate_clinic_action.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ clinic_id: clinicId })
                });

                const result = await response.json();

                if (result.success) {
                    location.reload();
                } else {
                    alert('Error: ' + result.message);
                }
            } catch (error) {
                alert('Error al activar la clínica. Por favor, inténtalo de nuevo.');
                console.error('Error:', error);
            }
        });
    });

    //Rellenar modal de edición de clínica

    const editClinicModal = document.getElementById('editClinicModal');

    if (editClinicModal) {

        editClinicModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;

            // Obtener datos de los atributos data-*
            const clinicId = button.getAttribute('data-clinic-id');
            const clinicName = button.getAttribute('data-clinic-nombre');
            const clinicAddress = button.getAttribute('data-clinic-direccion');
            const clinicPhone = button.getAttribute('data-clinic-telefono');
            const clinicCapMa = button.getAttribute('data-clinic-cap-ma');
            const clinicCapTa = button.getAttribute('data-clinic-cap-ta');

            // Rellenar los campos del modal
            document.getElementById('editClinicId').value = clinicId;
            document.getElementById('editNombre').value = clinicName;
            document.getElementById('editDireccion').value = clinicAddress;
            document.getElementById('editTelefono').value = clinicPhone;
            document.getElementById('editCapacidadMa').value = clinicCapMa;
            document.getElementById('editCapacidadTa').value = clinicCapTa;
        }
    );
    }

    //Enviar formulario de edición de clínica

    const editClinicForm = document.getElementById('editClinicForm');

    if (editClinicForm) {
        editClinicForm.addEventListener('submit', async (e) => {

            e.preventDefault();

            const formData = {
                clinic_id: document.getElementById('editClinicId').value,
                nombre: document.getElementById('editNombre').value,
                direccion: document.getElementById('editDireccion').value,
                telefono: document.getElementById('editTelefono').value,
                capacidad_ma: document.getElementById('editCapacidadMa').value,
                capacidad_ta: document.getElementById('editCapacidadTa').value
            };

            try {
                const response = await fetch('../../app/actions/clinics/update_clinic_action.php', {
                    method: 'POST',
                    headers: {
                        'content-type' : 'application/json'
                    },

                    body: JSON.stringify(formData)
                });

                const result = await response.json();

                if (result.success) {
                    const modalInstance = bootstrap.Modal.getInstance(editClinicModal);
                    modalInstance.hide();
                    location.reload();
                }else {
                    alert('Error: ' + result.message);
                }
            } catch (error) {
                alert('Error al actualizar la clínica. Por favor, inténtalo de nuevo.');
                console.error('Error:', error);
            }
        });
    }   

});