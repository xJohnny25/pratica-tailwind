document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("formCrear");

  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const result = await Swal.fire({
      title: "¿Crear usuario?",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Sí",
      cancelButtonText: "Cancelar",
    });

    if (!result.isConfirmed) return;

    const formData = new FormData(form);

    try {
      const res = await fetch("ws/crearUsuario2.php", {
        method: "POST",
        body: formData,
      });

      const data = await res.json();

      if (data.success) {
        await Swal.fire({
          title: "¡Éxito!",
          text: data.message,
          icon: "success",
        });
        form.reset();
      } else {
        await Swal.fire({
          title: "Error",
          text: data.message || "No se pudo crear el usuario",
          icon: "error",
        });
      }
    } catch (error) {
      await Swal.fire({
        title: "Error",
        text: "Error de conexión al crear el usuario",
        icon: "error",
      });
    }
  });
});
