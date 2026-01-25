document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('bookingBtn').addEventListener('click', newBooking);
});

async function newBooking(event) {
    event.preventDefault();
    const shiftId = document.getElementById('shift_id').value;
    const bookingDate = document.getElementById('shift_fecha').textContent;
    const turno = document.getElementById('shift_turno').textContent;
    const clinic = document.getElementById('shift_clinica').textContent;
    const numeroGatos = document.getElementById('numero_gatos').value;
    const colonyId = document.getElementById('user_colony_id').value;

    try {
        const response = await fetch('../app/actions/bookings/new_booking_action.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                shift_id: shiftId,
                shift_fecha: bookingDate,
                shift_turno: turno,
                shift_clinica: clinic,
                numero_gatos: numeroGatos,
                colony_id: colonyId
            })
        });

        const result = await response.json();

        if (result.success) {
            location.reload(); // Recargar para actualizar la tabla y mostrar mensaje de éxito
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        alert('Error al realizar la reserva: ' + error.message);
    }
}