document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('.cancelBookingBtn');
    buttons.forEach(button => {
        button.addEventListener('click', cancelBooking);
    });
});

async function cancelBooking(event) {
    event.preventDefault();
    const button = event.currentTarget;
    const bookingId = button.getAttribute('data-booking-id');

    if (!confirm('¿Estás seguro de que deseas cancelar esta reserva?')) {
        return;
    }

    try {
        const response = await fetch('../app/actions/cancel_booking_action.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ booking_id: bookingId })
        });

        const result = await response.json();

        if (result.success) {
            location.reload(); // Recargar para actualizar la tabla y mostrar mensaje de éxito
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        alert('Error al cancelar la reserva. Por favor, inténtalo de nuevo más tarde.');
        console.error('Error:', error);
    }
}