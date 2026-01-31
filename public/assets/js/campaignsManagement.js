document.addEventListener("DOMContentLoaded", () => {

    const filterActive = document.getElementById("filterActive");
    const filterInactive = document.getElementById("filterInactive");
    const filterAll = document.getElementById("filterAll");
    const searchName = document.getElementById("searchName");
    const clearFiltersBtn = document.getElementById("clearFilters");


    // Filtrar tabla de campañas

    function filterTable() {

        const activeValue = filterActive.checked;
        const inactiveValue = filterInactive.checked;
        const nameValue = searchName.value.toLowerCase();
        const rows = document.querySelectorAll("#campaignsTable tbody tr");

        rows.forEach((row) => {
            const rowName = (row.getAttribute("data-name") || "").toLowerCase();
            const rowActive = row.getAttribute("data-activa") === "1";

            // Ignorar fila de "no hay datos"
            if (row.querySelector("td[colspan]")) {
                return;
            }

            const activeMatch =
                (activeValue && rowActive) ||
                (inactiveValue && !rowActive) ||
                (!activeValue && !inactiveValue);

            const nameMatch = rowName.includes(nameValue);

            if (activeMatch && nameMatch) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    }
    // Event listeners
    filterActive.addEventListener("change", filterTable);
    filterInactive.addEventListener("change", filterTable);
    filterAll.addEventListener("change", filterTable);
    searchName.addEventListener("input", filterTable);
    clearFiltersBtn.addEventListener("click", () => {
        filterActive.checked = false;
        filterInactive.checked = false;
        filterAll.checked = true;
        searchName.value = "";
        filterTable();
    }
    );

    //Rellenar modal de edición de campaña
    const editCampaignModal = document.getElementById("editCampaignModal");
    if (editCampaignModal) {
        editCampaignModal.addEventListener("show.bs.modal", (event) => {
            const button = event.relatedTarget;
            const campaignId = button.getAttribute("data-campaign-id");
            const campaignNombre = button.getAttribute("data-campaign-nombre");
            const campaignFechaInicio = button.getAttribute("data-campaign-fecha-inicio");
            const campaignFechaFin = button.getAttribute("data-campaign-fecha-fin");

            // Rellenar los campos del modal
            document.getElementById("editCampaignId").value = campaignId;
            document.getElementById("editNombre").value = campaignNombre;
            document.getElementById("editFechaInicio").value = campaignFechaInicio;
            document.getElementById("editFechaFin").value = campaignFechaFin;
        }
        );
    }


    //Enviar formulario de edición de campaña
    const editCampaignForm = document.getElementById("editCampaignForm");
    if (editCampaignForm) {

        editCampaignForm.addEventListener("submit", async (e) => {
            e.preventDefault();
            const formData = {
                campaign_id: document.getElementById("editCampaignId").value,
                nombre: document.getElementById("editNombre").value,
                fecha_inicio: document.getElementById("editFechaInicio").value,
                fecha_fin: document.getElementById("editFechaFin").value,
            };

            try {
                const response = await fetch("../../app/actions/campaigns/update_campaigns_action.php", {
                    method: "POST",
                    headers: {
                        "content-type": "application/json",
                    },
                    body: JSON.stringify(formData),
                });
                const result = await response.json();

                if (result.success) {
                    const modalInstance = bootstrap.Modal.getInstance(editCampaignModal);
                    modalInstance.hide();
                    location.reload();
                } else {
                    alert("Error al actualizar la campaña: " + result.message);
                }
            } catch (error) {
                alert("Error al actualizar la campaña: " + error.message);
            }
        }); 


    }

    //Enviar formulario de nueva campaña
    const newCampaignModal = document.getElementById("newCampaignModal");
    const newCampaignForm = document.getElementById("newCampaignForm");
    if (newCampaignForm) {
        newCampaignForm.addEventListener("submit", async (e) => {
            e.preventDefault();
            const formData = {
                nombre: document.getElementById("newNombre").value,
                fecha_inicio: document.getElementById("newFechaInicio").value,
                fecha_fin: document.getElementById("newFechaFin").value,
            };

            if(formData.fecha_inicio > formData.fecha_fin){
                alert("La fecha de inicio no puede ser mayor a la fecha de fin.");
                return;
            }
            try {
                const response = await fetch("../../app/actions/campaigns/create_campaign_action.php", {
                    method: "POST",
                    headers: {
                        "content-type": "application/json",
                    },
                    body: JSON.stringify(formData),
                });
                const result = await response.json();

                if (result.success) {
                    const modalInstance = bootstrap.Modal.getInstance(newCampaignModal);
                    modalInstance.hide();
                    location.reload();
                } else {
                    alert("Error al crear la campaña: " + result.message);
                }
            } catch (error) {
                alert("Error al crear la campaña: " + error.message);
            }
        });
    }

    // Limpiar modal al cerrarlo
    if (newCampaignModal) {
        newCampaignModal.addEventListener("hidden.bs.modal", () => {
            newCampaignForm.reset();
        });
    }

    //Desactivar campaña

    const deactivateButtons = document.querySelectorAll(".deactivate-campaign-btn");
    deactivateButtons.forEach((button) => {
        button.addEventListener("click", async () => {
            const campaignId = button.getAttribute("data-campaign-id");
            const campaignNombre = button.getAttribute("data-campaign-name");

            if (!confirm(`¿Estás seguro de que deseas desactivar la campaña "${campaignNombre}"? Esta acción no se puede deshacer.`)) {
                return;
            }

            try {
                const response = await fetch("../../app/actions/campaigns/end_campaign_action.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({ campaign_id: campaignId }),
                });

                const result = await response.json();

                if (result.success) {
                    location.reload();
                } else {
                    alert("Error: " + result.message);
                }
            } catch (error) {
                alert("Error al desactivar la campaña: " + error.message);
            }
        });
    });




});