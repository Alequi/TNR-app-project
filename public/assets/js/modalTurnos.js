// Modal reserva turno
document.addEventListener('DOMContentLoaded', function() {
  const reserveModal = document.getElementById('reserveModal');
  if (reserveModal) {
    reserveModal.addEventListener('show.bs.modal', function (event) {
      const button = event.relatedTarget;
      
      const shiftId = button.getAttribute('data-shift-id');
      const shiftFecha = button.getAttribute('data-shift-fecha');
      const shiftClinica = button.getAttribute('data-shift-clinica');
      const shiftTurno = button.getAttribute('data-shift-turno');
      
      document.getElementById('shift_id').value = shiftId;
      document.getElementById('shift_fecha').textContent = shiftFecha;
      document.getElementById('shift_clinica').textContent = shiftClinica;
      document.getElementById('shift_turno').textContent = shiftTurno;
      document.getElementById('numero_gatos').value = '';
    });
  }
});