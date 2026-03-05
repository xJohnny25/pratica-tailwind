/* const users = [
  {
    nombre: "Juan",
    apellidos: "Pérez García",
    contrasena: "abc12345",
    telefono: "600001111",
    email: "juan.perez@example.com",
    sexo: "masculino",
  },
  {
    nombre: "María",
    apellidos: "López Sánchez",
    contrasena: "maria456",
    telefono: "600002222",
    email: "maria.lopez@example.com",
    sexo: "femenino",
  },
  {
    nombre: "Carlos",
    apellidos: "Ramírez Díaz",
    contrasena: "car123los",
    telefono: "600003333",
    email: "carlos.ramirez@example.com",
    sexo: "masculino",
  },
  {
    nombre: "Lucía",
    apellidos: "Fernández Ruiz",
    contrasena: "lucia789",
    telefono: "600004444",
    email: "lucia.fernandez@example.com",
    sexo: "femenino",
  },
  {
    nombre: "David",
    apellidos: "Martínez Torres",
    contrasena: "david2024",
    telefono: "600005555",
    email: "david.martinez@example.com",
    sexo: "masculino",
  },
]; */

let users = [];
let editingIndex = null; // guardamos el index del usuario que editamos

window.addEventListener("DOMContentLoaded", async () => {
  const tableBody = document.querySelector("tbody");
  const searchInput = document.querySelector('input[name="searchInput"]');

  // Form edición
  const editBox = document.getElementById("editBox");
  const editForm = document.getElementById("editForm");

  try {
    users = await fetchUsers();
    if (!users) users = [];
    loadUsers(users, tableBody);
  } catch (e) {
    Swal.fire("Error", "No se pudieron cargar los usuarios", "error");
  }

  searchInput.addEventListener("input", (event) =>
    handleSearchInput(event, tableBody),
  );

  // Guardar cambios
  editForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    await saveEdit(tableBody);
    editBox.style.display = "none";
  });

  // Cancelar edición
  document.getElementById("btnCancel").addEventListener("click", () => {
    editingIndex = null;
    editBox.style.display = "none";
  });
});

function loadUsers(users, tableBody) {
  tableBody.innerHTML = "";
  users.forEach((user, index) => {
    const row = createUserRow(user, index);
    tableBody.appendChild(row);
  });
}

function createUserRow(user, index) {
  const tr = document.createElement("tr");
  tr.className =
    "odd:bg-transparent even:bg-white/5 hover:bg-white/10 transition-colors";

  const idTd = document.createElement("td");
  idTd.textContent = user.id;
  idTd.className =
    "px-4 py-3 align-middle text-xs font-mono text-slate-400 whitespace-nowrap";
  tr.appendChild(idTd);

  // Campos (sin contraseña, ni id)
  Object.entries(user).forEach(([key, value]) => {
    if (key !== "contrasena" && key !== "id") {
      const td = document.createElement("td");
      td.textContent = value;
      td.className =
        "px-4 py-3 align-middle text-sm text-slate-100 whitespace-nowrap";
      tr.appendChild(td);
    }
  });

  // Acciones
  const actionsTd = document.createElement("td");
  actionsTd.className = "px-4 py-3 text-center whitespace-nowrap space-x-2";

  const tableBody = document.querySelector("tbody");
  const deleteBtn = createDeleteButton(user, tableBody);
  const editBtn = createEditButton(index);

  actionsTd.appendChild(deleteBtn);
  actionsTd.appendChild(editBtn);

  tr.appendChild(actionsTd);

  return tr;
}

// Botón eliminar
function createDeleteButton(user, tableBody) {
  const button = document.createElement("button");
  button.textContent = "Eliminar";
  button.className =
    "inline-flex items-center justify-center rounded-full px-3 py-1.5 text-xs font-semibold text-red-100 bg-red-500/80 hover:bg-red-400 active:bg-red-600 shadow-sm shadow-red-900/40 transition-all duration-200 cursor-pointer";

  button.addEventListener("click", async () => {
    const ok = await Swal.fire({
      title: "¿Eliminar?",
      text: "¿Borrar a " + user.nombre + "?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Sí, eliminar",
      cancelButtonText: "Cancelar",
    });
    if (!ok.isConfirmed) return;

    try {
      const res = await fetch("ws/deleteUsuario.php?id=" + user.id);
      const data = await res.json();
      if (data.success) {
        Swal.fire("Listo", data.message, "success");
        users = await fetchUsers();
        loadUsers(users, tableBody);
      } else {
        Swal.fire("Error", data.message, "error");
      }
    } catch (e) {
      Swal.fire("Error", "No se pudo eliminar", "error");
    }
  });

  return button;
}

// Botón modificar
function createEditButton(index) {
  const button = document.createElement("button");
  button.textContent = "Modificar";
  button.className =
    "inline-flex items-center justify-center rounded-full px-4 py-1.5 text-xs font-semibold text-white bg-fuchsia-500 hover:bg-fuchsia-400 active:bg-fuchsia-600 shadow-sm shadow-fuchsia-900/40 transition-all duration-200 cursor-pointer ml-2";

  button.addEventListener("click", () => openEditForm(index));
  return button;
}

// Abrir form con datos
function openEditForm(index) {
  editingIndex = index;
  const user = users[index];

  document.getElementById("f_nombre").value = user.nombre;
  document.getElementById("f_apellidos").value = user.apellidos;
  document.getElementById("f_telefono").value = user.telefono;
  document.getElementById("f_email").value = user.email;
  document.getElementById("f_sexo").value = user.genero;

  document.getElementById("editBox").style.display = "block";
}

// Guardar (envía a la BD)
async function saveEdit(tableBody) {
  if (editingIndex === null) return;

  const ok = await Swal.fire({
    title: "¿Guardar cambios?",
    icon: "question",
    showCancelButton: true,
    confirmButtonText: "Sí",
    cancelButtonText: "Cancelar",
  });
  if (!ok.isConfirmed) return;

  const user = users[editingIndex];
  const formData = new FormData();
  formData.append("nombre", document.getElementById("f_nombre").value.trim());
  formData.append(
    "apellidos",
    document.getElementById("f_apellidos").value.trim(),
  );
  formData.append(
    "telefono",
    document.getElementById("f_telefono").value.trim(),
  );
  formData.append("email", document.getElementById("f_email").value.trim());
  formData.append("genero", document.getElementById("f_sexo").value);

  try {
    const res = await fetch("ws/modificarUsuario.php?id=" + user.id, {
      method: "POST",
      body: formData,
    });
    const data = await res.json();
    if (data.success) {
      Swal.fire("Guardado", data.message, "success");
      users = await fetchUsers();
      loadUsers(users, tableBody);
    } else {
      Swal.fire("Error", data.message, "error");
    }
  } catch (e) {
    Swal.fire("Error", "No se pudo guardar", "error");
  }
  editingIndex = null;
}

function filterTable(searchTerm, tableBody) {
  const rows = tableBody.querySelectorAll("tr");

  rows.forEach((row) => {
    const cells = row.querySelectorAll("td");
    const name = cells[1]?.textContent?.toLowerCase() || "";
    const lastName = cells[2]?.textContent?.toLowerCase() || "";
    const fullName = `${name} ${lastName}`;

    const shouldShow = fullName.includes(searchTerm.toLowerCase());
    row.style.display = shouldShow ? "" : "none";
  });
}

function handleSearchInput(event, tableBody) {
  const searchTerm = event.target.value.trim();

  if (searchTerm.length === 0) {
    showAllRows(tableBody);
    return;
  }

  if (searchTerm.length < 3) {
    return;
  }

  filterTable(searchTerm, tableBody);
}

function showAllRows(tableBody) {
  const rows = tableBody.querySelectorAll("tr");
  rows.forEach((row) => (row.style.display = ""));
}

async function fetchUsers() {
  const res = await fetch("ws/getUsuario.php");

  if (!res.ok) {
    throw new Error("Error al traer los usuarios");
  }

  const json = await res.json();

  return json.data;
}
