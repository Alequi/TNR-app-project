document.addEventListener('DOMContentLoaded', () => {
    const filterType = document.getElementById('filterType');
    const filterClinic = document.getElementById('filterClinic');
    const filterAvailability = document.getElementById('filterAvailability');
    const clearFilters = document.getElementById('clearFilters');
    const cagesTable = document.getElementById('cagesTable');
    const newCageForm = document.getElementById('newCageForm');
    const newCageModal = document.getElementById('newCageModal');
    const editCageModal = document.getElementById('editCageModal');
    const editCageForm = document.getElementById('editCageForm');

    // Filtrar tabla
    function filterTable() {
        const typeValue = filterType.value;
        const clinicValue = filterClinic.value;
        const availabilityValue = filterAvailability.value;
        const rows = cagesTable.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const rowType = row.getAttribute('data-type');
            const rowClinic = row.getAttribute('data-clinic');
            const rowState = row.getAttribute('data-state');

            // Si la fila no tiene atributos data (ej: mensaje "No hay jaulas"), ignorarla
            if (!rowType || !rowClinic) {
                return;
            }

            let showRow = true;

            if (typeValue && rowType !== typeValue) {
                showRow = false;
            }

            if (clinicValue !== '' && rowClinic !== clinicValue) {
                showRow = false;
            }

            if (availabilityValue) {
                if (availabilityValue === 'disponible' && rowState === 'prestado') {
                    showRow = false;
                } else if (availabilityValue === 'prestada' && rowState !== 'prestado') {
                    showRow = false;
                }
            }

            row.style.display = showRow ? '' : 'none';
        });
    }

    // Event listeners para filtros
    if (filterType) {
        filterType.addEventListener('change', filterTable);
    }

    if (filterClinic) {
        filterClinic.addEventListener('change', filterTable);
    }

    if (filterAvailability) {
        filterAvailability.addEventListener('change', filterTable);
    }

    if (clearFilters) {
        clearFilters.addEventListener('click', () => {
            filterType.value = '';
            filterClinic.value = '';
            filterAvailability.value = '';
            filterTable();
        });
    }

    // Enviar formulario de nueva jaula
    if (newCageForm) {
        newCageForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = {
                clinic_id: document.getElementById('clinic_id').value,
                cage_type_id: document.getElementById('cage_type_id').value,
                numero_interno: document.getElementById('numero_interno').value
            };

            try {
                const response = await fetch('../../app/actions/jaulas/create_cage_action.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });

                const result = await response.json();

                if (result.success) {
                    // Cerrar modal
                    const modalInstance = bootstrap.Modal.getInstance(newCageModal);
                    modalInstance.hide();
                    
                    // Recargar página para mostrar nueva jaula
                    location.reload();
                } else {
                    alert('Error: ' + result.message);
                }
            } catch (error) {
                alert('Error al crear la jaula. Por favor, inténtalo de nuevo.');
                console.error('Error:', error);
            }
        });
    }

    // Limpiar formulario cuando se cierra el modal
    if (newCageModal) {
        newCageModal.addEventListener('hidden.bs.modal', () => {
            newCageForm.reset();
        });
    }

    // Poblar modal de edición
    if (editCageModal) {
        editCageModal.addEventListener('show.bs.modal', (event) => {
            const button = event.relatedTarget;
            
            // Obtener datos del botón
            const cageId = button.getAttribute('data-cage-id');
            const clinicName = button.getAttribute('data-cage-clinic-name');
            const cageTypeName = button.getAttribute('data-cage-type-name');
            const numeroInterno = button.getAttribute('data-cage-number');
            const isActive = button.getAttribute('data-cage-active') === '1';
            
            // Poblar campos del modal
            document.getElementById('editCageId').value = cageId;
            document.getElementById('editClinicName').value = clinicName;
            document.getElementById('editCageTypeName').value = cageTypeName;
            document.getElementById('editNumeroInterno').value = numeroInterno;
            document.getElementById('editCageActive').checked = isActive;
        });
    }

    // Enviar formulario de edición
    if (editCageForm) {
        editCageForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = {
                cage_id: document.getElementById('editCageId').value,
                numero_interno: document.getElementById('editNumeroInterno').value,
                activo: document.getElementById('editCageActive').checked ? 1 : 0
            };

            try {
                const response = await fetch('../../app/actions/jaulas/update_cage_action.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });

                const result = await response.json();

                if (result.success) {
                    // Cerrar modal
                    const modalInstance = bootstrap.Modal.getInstance(editCageModal);
                    modalInstance.hide();
                    
                    // Recargar página para mostrar cambios
                    location.reload();
                } else {
                    alert('Error: ' + result.message);
                }
            } catch (error) {
                alert('Error al actualizar la jaula. Por favor, inténtalo de nuevo.');
                console.error('Error:', error);
            }
        });
    }

    // Limpiar formulario de edición cuando se cierra el modal
    if (editCageModal) {
        editCageModal.addEventListener('hidden.bs.modal', () => {
            editCageForm.reset();
        });
    }



    

});
