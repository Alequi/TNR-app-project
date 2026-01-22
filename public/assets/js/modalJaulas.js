// Rellenar datos del modal al abrirlo
      const modalReserva = document.getElementById('modalReserva');
      modalReserva.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        
        // Obtener datos del botón
        const jaulaId = button.getAttribute('data-jaula-id');
        const jaulaNumero = button.getAttribute('data-jaula-numero');
        const jaulaTipo = button.getAttribute('data-jaula-tipo');
        const jaulaClinica = button.getAttribute('data-jaula-clinica');
        
        // Rellenar el modal
        document.getElementById('jaula_id').value = jaulaId;
        document.getElementById('jaula_numero').textContent = jaulaNumero;
        document.getElementById('jaula_tipo').textContent = jaulaTipo;
        document.getElementById('jaula_clinica').textContent = jaulaClinica;
        
        // Establecer fecha mínima (hoy)
        const hoy = new Date().toISOString().split('T')[0];
        document.getElementById('fecha_prestamo').setAttribute('min', hoy);
        document.getElementById('fecha_devolucion').setAttribute('min', hoy);
      });
      
      // Validar que fecha de devolución sea posterior a fecha de préstamo
      document.getElementById('fecha_prestamo').addEventListener('change', function() {
        const fechaPrestamo = this.value;
        document.getElementById('fecha_devolucion').setAttribute('min', fechaPrestamo);
      });