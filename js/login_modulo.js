document.addEventListener("DOMContentLoaded", () => {
  formRegister.addEventListener("submit", (e) => {
    e.preventDefault();

    console.log('Nombre:', inputUser.value);
    console.log('Correo:', inputEmail.value);
    console.log('Teléfono:', inputTelefono.value);
    console.log('Fecha de Nacimiento:', inputFechaNacimiento.value);
    console.log('Dirección:', inputDireccion.value);
    console.log('Contraseña:', inputPass.value);
    console.log('Re-Contraseña:', inputRePass.value);
    console.log('Términos aceptados:', inputTerminos.checked);

    // Validación del checkbox
    if (!inputTerminos.checked) {
      mostrarError("Debes aceptar los Términos y Condiciones.");
      return;
    }

    validarCampo(userNameRegex, inputUser, "El nombre de usuario debe tener entre 4 y 16 caracteres.");
    validarCampo(emailRegex, inputEmail, "El correo no es válido.");
    validarCampo(passwordRegex, inputPass, "La contraseña debe tener entre 4 y 12 caracteres.");
    validarCampo(telefonoRegex, inputTelefono, "El teléfono debe tener 10 dígitos y comenzar con 3.");
    validarContraseñasIguales(inputPass, inputRePass);
    validarCampoNoVacio(inputFechaNacimiento, "La fecha de nacimiento es obligatoria.");
    validarCampoNoVacio(inputDireccion, "La dirección es obligatoria.");

    // Verificar si todos los campos son válidos
    console.log('Estado de validación:', estadoValidacionCampos);

    if (Object.values(estadoValidacionCampos).every(Boolean)) {
      enviarFormulario(formRegister, alertaError, alertaExito);
    } else {
      mostrarError("Todos los campos son obligatorios.");
    }
  });
});