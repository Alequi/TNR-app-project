document.getElementById('searchInput').addEventListener('input', function() {
    const query = this.value.toLowerCase();
    const items = document.getElementById('table').getElementsByTagName('tbody')[0].getElementsByTagName('tr');

    filterItems(query, items);
});

function filterItems(query, items) {
    for (let i = 0; i < items.length; i++) {
        const cells = items[i].getElementsByTagName('td');
        let found = false;
        
        // Buscar en todas las celdas de la fila
        for (let j = 0; j < cells.length; j++) {
            const cellText = cells[j].textContent.toLowerCase();
            if (cellText.includes(query)) {
                found = true;
                break;
            }
        }
        
        // Mostrar u ocultar la fila según el resultado
        items[i].style.display = found ? '' : 'none';
    }
}