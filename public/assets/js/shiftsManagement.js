document.addEventListener("DOMContentLoaded", () => {
  const filterClinic = document.getElementById("filterClinic");
  const filterTurno = document.getElementById("filterTurno");
  const searchDate = document.getElementById("searchDate");
  const clearFiltersBtn = document.getElementById("clearFilters");

  // Filtrar tabla de turnos
  function filterTable() {
    const clinicValue = filterClinic.value;
    const turnoValue = filterTurno.value;
    const dateValue = searchDate.value;
    const rows = document.querySelectorAll("#shiftsTable tbody tr");

    rows.forEach((row) => {
      const rowClinic = row.getAttribute("data-clinic");
      const rowTurno = row.getAttribute("data-turno");
      const rowFecha = row.getAttribute("data-fecha");

      // Si la fila no tiene atributos data (ej: mensaje "No hay turnos"), ignorarla
      if (rowClinic === null) {
        return;
      }

      const clinicMatch = clinicValue === "" || rowClinic === clinicValue;
      const turnoMatch = turnoValue === "" || rowTurno === turnoValue;
      const dateMatch = dateValue === "" || rowFecha === dateValue;

      if (clinicMatch && turnoMatch && dateMatch) {
        row.style.display = "";
      } else {
        row.style.display = "none";
      }
    });
  }

  // Event listeners para filtros
  if (filterClinic) {
    filterClinic.addEventListener("change", filterTable);
  }

  if (filterTurno) {
    filterTurno.addEventListener("change", filterTable);
  }

  if (searchDate) {
    searchDate.addEventListener("input", filterTable);
  }

  // Limpiar filtros
  clearFiltersBtn.addEventListener("click", () => {
    filterClinic.value = "";
    filterTurno.value = "";
    searchDate.value = "";
    filterTable();
  });

  // Auto-completar capacidad según clínica y turno seleccionados (Nuevo Turno)
  const newClinicSelect = document.getElementById("newClinic");
  const newTurnoSelect = document.getElementById("newTurno");
  const newCapacidadInput = document.getElementById("newCapacidad");

  function updateNewCapacity() {
    const selectedClinic =
      newClinicSelect.options[newClinicSelect.selectedIndex];
    const selectedTurno = newTurnoSelect.value;

    if (selectedClinic && selectedTurno) {
      if (selectedTurno === "M") {
        newCapacidadInput.value = selectedClinic.getAttribute("data-cap-ma");
      } else if (selectedTurno === "T") {
        newCapacidadInput.value = selectedClinic.getAttribute("data-cap-ta");
      }
      // Campo readonly para evitar edición manual
      newCapacidadInput.readOnly = true;
      newCapacidadInput.classList.add('bg-light');
    }
  }

  newClinicSelect.addEventListener("change", updateNewCapacity);
  newTurnoSelect.addEventListener("change", updateNewCapacity);

  // Crear nuevo turno
  const newShiftForm = document.getElementById("newShiftForm");

  newShiftForm.addEventListener("submit", async function (e) {
    e.preventDefault();

    // Deshabilitar botón para evitar doble envío y mostrar spinner
    const submitBtn = document.querySelector('#newShiftModal button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creando...';

    const formData = {
      clinic_id: document.getElementById("newClinic").value,
      campaign_id: document.getElementById("newCampaign").value,
      fecha: document.getElementById("newFecha").value,
      turno: document.getElementById("newTurno").value,
      capacidad: document.getElementById("newCapacidad").value,
    };

    try {
      const response = await fetch(
        "../../app/actions/shifts/create_shift_action.php",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(formData),
        },
      );

      const result = await response.json();

      if (result.success) {
        alert("Turno creado exitosamente");
        location.reload();
      } else {
        alert("Error: " + result.message);
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="bi bi-check-circle me-1"></i>Crear Turno'
      }
    } catch (error) {
      alert("Error al crear el turno: " + error.message);
      submitBtn.disabled = false;
      submitBtn.innerHTML = '<i class="bi bi-check-circle me-1"></i>Crear Turno'
    } 
  });


  // Eliminar turno
  const deleteShiftButtons =  document.querySelectorAll(".delete-shift-btn");
  deleteShiftButtons.forEach((button) => {
    button.addEventListener("click", async function () {
      if (
        !confirm(
          "¿Estás seguro de que deseas eliminar este turno? Esta acción no se puede deshacer.",
        )
      ) {
        return;
      }

      const shiftId = this.getAttribute("data-shift-id");

      try {
        const response = await fetch(
          "../../app/actions/shifts/delete_shift_action.php",
          {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify({ shift_id: shiftId }),
          },
        );

        const result = await response.json();

        if (result.success) {
          location.reload();
        } else {
          alert("Error: " + result.message);
        }
      } catch (error) {
        alert("Error al eliminar el turno: " + error.message);
      }
    });
  });
});
