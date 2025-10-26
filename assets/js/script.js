// Mobile menu toggle
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    const navMenu = document.querySelector('.nav-menu');
    
    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', function() {
            navMenu.classList.toggle('active');
        });
    }
    
    // Auto-hide mobile menu on link click
    const navLinks = document.querySelectorAll('.nav-menu a');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            navMenu.classList.remove('active');
        });
    });
    
    // Form validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let valid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    valid = false;
                    field.style.borderColor = '#ef4444';
                } else {
                    field.style.borderColor = '';
                }
            });
            
            if (!valid) {
                e.preventDefault();
                alert('Harap lengkapi semua field yang wajib diisi!');
            }
        });
    });
    
    // Password confirmation validation
    const passwordForm = document.querySelector('form[action*="register"]');
    if (passwordForm) {
        passwordForm.addEventListener('submit', function(e) {
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirm_password');
            
            if (password.value !== confirmPassword.value) {
                e.preventDefault();
                alert('Password dan konfirmasi password tidak cocok!');
                confirmPassword.style.borderColor = '#ef4444';
            }
        });
    }
    
    // Auto-format phone number
    const phoneInputs = document.querySelectorAll('input[type="tel"]');
    phoneInputs.forEach(input => {
        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 0) {
                value = value.substring(0, 13);
                if (value.length > 4) {
                    value = value.replace(/(\d{4})(\d{4})/, '$1-$2');
                }
                if (value.length > 9) {
                    value = value.replace(/(\d{4})(\d{4})(\d{4})/, '$1-$2-$3');
                }
            }
            e.target.value = value;
        });
    });
    
    // Date validation - prevent past dates
    const dateInputs = document.querySelectorAll('input[type="date"]');
    const today = new Date().toISOString().split('T')[0];
    dateInputs.forEach(input => {
        input.min = today;
    });
});

// Flight search auto-suggest
function initFlightSearch() {
    const departureInput = document.getElementById('departure');
    const arrivalInput = document.getElementById('arrival');
    
    const cities = [
        'Jakarta', 'Bali', 'Surabaya', 'Yogyakarta', 'Medan', 
        'Makassar', 'Bandung', 'Semarang', 'Palembang', 'Batam'
    ];
    
    function createDropdown(input, items) {
        let dropdown = document.createElement('div');
        dropdown.className = 'autocomplete-dropdown';
        dropdown.style.cssText = `
            position: absolute;
            background: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            width: ${input.offsetWidth}px;
            display: none;
        `;
        
        input.parentNode.style.position = 'relative';
        input.parentNode.appendChild(dropdown);
        
        input.addEventListener('input', function() {
            const value = this.value.toLowerCase();
            const filtered = items.filter(item => 
                item.toLowerCase().includes(value)
            );
            
            dropdown.innerHTML = '';
            dropdown.style.display = filtered.length ? 'block' : 'none';
            
            filtered.forEach(item => {
                const div = document.createElement('div');
                div.textContent = item;
                div.style.padding = '8px 12px';
                div.style.cursor = 'pointer';
                div.addEventListener('mouseenter', function() {
                    this.style.background = '#f0f0f0';
                });
                div.addEventListener('mouseleave', function() {
                    this.style.background = '';
                });
                div.addEventListener('click', function() {
                    input.value = item;
                    dropdown.style.display = 'none';
                });
                dropdown.appendChild(div);
            });
        });
        
        document.addEventListener('click', function(e) {
            if (!input.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.style.display = 'none';
            }
        });
    }
    
    if (departureInput) createDropdown(departureInput, cities);
    if (arrivalInput) createDropdown(arrivalInput, cities);
}

// Initialize when document is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initFlightSearch);
} else {
    initFlightSearch();
}