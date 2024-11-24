'use strict';
function agregarIngrediente() {
    const ingredientesDiv = document.getElementById('ingredientes');
    const nuevoIngrediente = document.createElement('div');
    nuevoIngrediente.className = 'ingrediente';
    nuevoIngrediente.innerHTML = `
                <input type="text" name="ingredientes[nombre][]" placeholder="Nombre del ingrediente" required>
                <input type="text" name="ingredientes[cantidad][]" placeholder="Cantidad" required>
                <button type="button" onclick="eliminarIngrediente(this)">Eliminar</button>
            `;
    ingredientesDiv.appendChild(nuevoIngrediente);
}

function eliminarIngrediente(button) {
    const ingredienteDiv = button.parentElement;
    ingredienteDiv.remove();
}