document.addEventListener("DOMContentLoaded", () => {
    const filterZona = document.getElementById("filterZona");
    const searchName = document.getElementById("searchName");
    const clearFiltersBtn = document.getElementById("clearFilters");
    const table = document.getElementById("coloniesTable");
    const editColonyForm = document.getElementById("editColonyForm");

    // Filtrar tabla de colonias

    function filterTable() {
        const zonaValue = filterZona.value.toLowerCase();
        const nameValue = searchName.value.toLowerCase();
        const rows = table.querySelectorAll("tbody tr");

        rows.forEach((row) => {
            const zona = (row.getAttribute("data-zona") || "").toLowerCase();
            const nombre = (row.getAttribute("data-name") || "").toLowerCase();

            // Ignorar fila de "no hay datos"
            if (row.querySelector("td[colspan]")) {
                return;
            }

            const zonaMatch = zona.includes(zonaValue);
            const nameMatch = nombre.includes(nameValue);

            if (zonaMatch && nameMatch) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    }

    // Event listeners
    filterZona.addEventListener("input", filterTable);
    searchName.addEventListener("input", filterTable);

    clearFiltersBtn.addEventListener("click", () => {
        filterZona.value = "";
        searchName.value = "";
        filterTable();
    });



    //Rellenar modal de edición
    const editColonyModal = document.getElementById("editColonyModal");

    if (editColonyModal) {
        editColonyModal.addEventListener("show.bs.modal", (event) => {
            const button = event.relatedTarget;

            // Obtener datos del boton que abrió el modal
            const colonyId = button.getAttribute("data-colony-id");
            const colonyCode = button.getAttribute("data-colony-code");
            const colonyName = button.getAttribute("data-colony-name");
            const colonyZone = button.getAttribute("data-colony-zone");
            const colonyGestor = button.getAttribute("data-colony-gestor");
           

            // Rellenar los campos del modal

            document.getElementById("editColonyId").value = colonyId;
            document.getElementById("editCode").value = colonyCode;
            document.getElementById("editNombre").value = colonyName;
            document.getElementById("editZona").value = colonyZone;
            document.getElementById("editGestor").value = colonyGestor;
           
        });
    }

    if (editColonyForm) {
        editColonyForm.addEventListener("submit", async(e) => {
           
            e.preventDefault();

            const formData = {
                id: document.getElementById("editColonyId").value,
                code: document.getElementById("editCode").value,
                nombre: document.getElementById("editNombre").value,
                zona: document.getElementById("editZona").value,
                gestor_id: document.getElementById("editGestor").value,
            };

            try {
                const response = await fetch('../../app/actions/colonies/update_colony_action.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });

                const result = await response.json();

                if (result.success) {
                    const modalInstance = bootstrap.Modal.getInstance(editColonyModal);
                    modalInstance.hide();
                    location.reload();
                } else {
                    alert("Error: " + result.message);
                }

            } catch (error) {
                alert("Error al actualizar la colonia: " + error.message);
            }
            
        });
    }

    //Limpiar modal al cerrarlo
    if (editColonyModal) {
        editColonyModal.addEventListener("hidden.bs.modal", () => {
            editColonyForm.reset();
        });
    }


})
