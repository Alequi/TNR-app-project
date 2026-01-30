// Elementos del DOM
const filterClinic = document.getElementById('filterClinic');
const filterTurno = document.getElementById('filterTurno');
const searchDate = document.getElementById('searchDate');
const clearFiltersBtn = document.getElementById('clearFilters');

// Filtrar tabla de turnos
function filterTable() {
    const clinicValue = filterClinic.value;
    const turnoValue = filterTurno.value;
    const dateValue = searchDate.value;
    const rows = document.querySelectorAll('#shiftsTable tbody tr');

    rows.forEach(row => {
        const rowClinic = row.getAttribute('data-clinic');
        const rowTurno = row.getAttribute('data-turno');
        const rowFecha = row.getAttribute('data-fecha');

        // Si la fila no tiene atributos data (ej: mensaje "No hay turnos"), ignorarla
        if (rowClinic === null) {
            return;
        }

        const clinicMatch = clinicValue === '' || rowClinic === clinicValue;
        const turnoMatch = turnoValue === '' || rowTurno === turnoValue;
        const dateMatch = dateValue === '' || rowFecha === dateValue;

        if (clinicMatch && turnoMatch && dateMatch) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Event listeners para filtros
filterClinic.addEventListener('change', filterTable);
filterTurno.addEventListener('change', filterTable);
searchDate.addEventListener('input', filterTable);

// Limpiar filtros
clearFiltersBtn.addEventListener('click', () => {
    filterClinic.value = '';
    filterTurno.value = '';
    searchDate.value = '';
    filterTable();
});

// Auto-completar capacidad según clínica y turno seleccionados (Nuevo Turno)
const newClinicSelect = document.getElementById('newClinic');
const newTurnoSelect = document.getElementById('newTurno');
const newCapacidadInput = document.getElementById('newCapacidad');

function updateNewCapacity() {
    const selectedClinic = newClinicSelect.options[newClinicSelect.selectedIndex];
    const selectedTurno = newTurnoSelect.value;

    if (selectedClinic && selectedTurno) {
        if (selectedTurno === 'M') {
            newCapacidadInput.value = selectedClinic.getAttribute('data-cap-ma');
        } else if (selectedTurno === 'T') {
            newCapacidadInput.value = selectedClinic.getAttribute('data-cap-ta');
        }
    }
}

newClinicSelect.addEventListener('change', updateNewCapacity);
newTurnoSelect.addEventListener('change', updateNewCapacity);

// Auto-completar capacidad según clínica y turno seleccionados (Editar Turno)
const editClinicSelect = document.getElementById('editClinic');
const editTurnoSelect = document.getElementById('editTurno');
const editCapacidadInput = document.getElementById('editCapacidad');

function updateEditCapacity() {
    const selectedClinic = editClinicSelect.options[editClinicSelect.selectedIndex];
    const selectedTurno = editTurnoSelect.value;

    if (selectedClinic && selectedTurno) {
        if (selectedTurno === 'M') {
            editCapacidadInput.value = selectedClinic.getAttribute('data-cap-ma');
        } else if (selectedTurno === 'T') {
            editCapacidadInput.value = selectedClinic.getAttribute('data-cap-ta');
        }
    }
}

editClinicSelect.addEventListener('change', updateEditCapacity);
editTurnoSelect.addEventListener('change', updateEditCapacity);

// Crear nuevo turno
document.getElementById('newShiftForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const formData = {
        clinic_id: document.getElementById('newClinic').value,
        campaign_id: document.getElementById('newCampaign').value,
        fecha: document.getElementById('newFecha').value,
        turno: document.getElementById('newTurno').value,
        capacidad: document.getElementById('newCapacidad').value
    };

    try {
        const response = await fetch('../../app/actions/shifts/create_shift_action.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formData)
        });

        const result = await response.json();

        if (result.success) {
            alert('Turno creado exitosamente');
            location.reload();
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        alert('Error al crear el turno: ' + error.message);
    }
});

// Cargar datos en el modal de edición
const editShiftModal = document.getElementById('editShiftModal');
editShiftModal.addEventListener('show.bs.modal', async function (event) {
    const button = event.relatedTarget;
    const shiftId = button.getAttribute('data-shift-id');

    try {
        const response = await fetch(`../../app/actions/shifts/get_shift_action.php?id=${shiftId}`);
        const result = await response.json();

        if (result.success) {
            const shift = result.data;
            document.getElementById('editShiftId').value = shift.id;
            document.getElementById('editClinic').value = shift.clinic_id;
            document.getElementById('editCampaign').value = shift.campaign_id;
            document.getElementById('editFecha').value = shift.fecha;
            document.getElementById('editTurno').value = shift.turno;
            document.getElementById('editCapacidad').value = shift.capacidad;
            document.getElementById('editOcupados').value = shift.ocupados;
            
            // Establecer mínimo de capacidad al número de ocupados
            document.getElementById('editCapacidad').min = shift.ocupados;
        } else {
            alert('Error al cargar los datos del turno: ' + result.message);
        }
    } catch (error) {
        alert('Error al cargar el turno: ' + error.message);
    }
});

// Actualizar turno
document.getElementById('editShiftForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const formData = {
        shift_id: document.getElementById('editShiftId').value,
        clinic_id: document.getElementById('editClinic').value,
        campaign_id: document.getElementById('editCampaign').value,
        fecha: document.getElementById('editFecha').value,
        turno: document.getElementById('editTurno').value,
        capacidad: document.getElementById('editCapacidad').value
    };

    try {
        const response = await fetch('../../app/actions/shifts/update_shift_action.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formData)
        });

        const result = await response.json();

        if (result.success) {
            alert('Turno actualizado exitosamente');
            location.reload();
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        alert('Error al actualizar el turno: ' + error.message);
    }
});

// Eliminar turno
document.querySelectorAll('.delete-shift-btn').forEach(button => {
    button.addEventListener('click', async function () {
        if (!confirm('¿Estás seguro de que deseas eliminar este turno? Esta acción no se puede deshacer.')) {
            return;
        }

        const shiftId = this.getAttribute('data-shift-id');

        try {
            const response = await fetch('../../app/actions/shifts/delete_shift_action.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ shift_id: shiftId })
            });

            const result = await response.json();

            if (result.success) {
                alert('Turno eliminado exitosamente');
                location.reload();
            } else {
                alert('Error: ' + result.message);
            }
        } catch (error) {
            alert('Error al eliminar el turno: ' + error.message);
        }
    });
});
