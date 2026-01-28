document.addEventListener('DOMContentLoaded', function() {
    const statusSelects = document.querySelectorAll('.changeStatusBtn');

    statusSelects.forEach(function(select) {
        select.addEventListener('change', async function() {
            const bookingId = this.getAttribute('data-booking-id');
            const newStatus = this.value;

            if (confirm('¿Desea cambiar el estado de esta reserva?')) {
                try {

                    const response = await fetch('../../app/actions/bookings/update_booking_action.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            bookingId: bookingId,
                            newStatus: newStatus
                        })
                    });
                    
                    const result = await response.json();
                    if (result.success) {
                        alert('El estado de la reserva ha sido actualizado correctamente.');
                        location.reload();
                    } else {
                        alert('Error al actualizar el estado de la reserva: ' + result.message);
                        location.reload();
                    }
                } catch (error) {
                    alert('Error de red al actualizar el estado de la reserva.');
                    location.reload();
                }


            }
        });
    });
});

