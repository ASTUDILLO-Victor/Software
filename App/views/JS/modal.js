function openUpdateModal(id, nombre, apellido, username, email) {
    document.getElementById('userId').value = id;
    document.getElementById('userNombre').value = nombre;
    document.getElementById('userApellido').value = apellido;
    document.getElementById('userUsername').value = username;
    document.getElementById('userEmail').value = email;
    
    document.getElementById('updateModal').classList.add('is-active');
}

function closeUpdateModal() {
    document.getElementById('updateModal').classList.remove('is-active');
}

