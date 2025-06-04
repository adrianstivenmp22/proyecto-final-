// assets/js/main.js

// Toggle del menú móvil
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuButton = document.querySelector('[aria-controls="mobile-menu"]');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if(mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function() {
            const expanded = this.getAttribute('aria-expanded') === 'true';
            this.setAttribute('aria-expanded', !expanded);
            mobileMenu.classList.toggle('hidden');
        });
    }
    
    // Validación de formularios
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = this.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if(!field.value.trim()) {
                    isValid = false;
                    field.classList.add('border-red-500');
                } else {
                    field.classList.remove('border-red-500');
                }
            });
            
            if(!isValid) {
                e.preventDefault();
                alert('Por favor complete todos los campos requeridos');
            }
        });
    });
    
    // Mostrar/ocultar contraseña
    const togglePasswordButtons = document.querySelectorAll('.toggle-password');
    togglePasswordButtons.forEach(button => {
        button.addEventListener('click', function() {
            const input = this.previousElementSibling;
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    });
});
// Validación mejorada en registro.php
document.getElementById('registroForm').addEventListener('submit', function(e) {
    let valid = true;
    
    // Validar contraseñas
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    if(password !== confirmPassword) {
        alert('Las contraseñas no coinciden');
        valid = false;
    }
    
    // Validar fortaleza de contraseña
    const regex = /^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    if(!regex.test(password)) {
        alert('La contraseña debe tener al menos 8 caracteres, una mayúscula, un número y un carácter especial');
        valid = false;
    }
    
    // Validar términos
    if(!document.getElementById('terminos').checked) {
        alert('Debe aceptar los términos y condiciones');
        valid = false;
    }
    
    if(!valid) {
        e.preventDefault();
        return false;
    }
    
    return true;
});