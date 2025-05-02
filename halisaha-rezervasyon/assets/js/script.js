document.addEventListener('DOMContentLoaded', function() {
  // Navbar aktif link ayarı
  const currentPage = location.pathname.split('/').pop();
  document.querySelectorAll('.nav-link').forEach(link => {
    if (link.getAttribute('href') === currentPage) {
      link.classList.add('active');
    }
  });

  // Tarih seçici için min ayarı
  const dateInputs = document.querySelectorAll('input[type="date"]');
  if (dateInputs) {
    const today = new Date().toISOString().split('T')[0];
    dateInputs.forEach(input => {
      input.min = today;
    });
  }

  // Kart hover efekti (vintage efekt ekli)
  const cards = document.querySelectorAll('.card');
  cards.forEach(card => {
    card.addEventListener('mouseenter', () => {
      const img = card.querySelector('.card-img-top');
      if (img) {
        img.style.transform = 'scale(1.05)';
        img.style.filter = 'sepia(0.5) contrast(1.2)';
      }
    });
    
    card.addEventListener('mouseleave', () => {
      const img = card.querySelector('.card-img-top');
      if (img) {
        img.style.transform = 'scale(1)';
        img.style.filter = 'sepia(0.3) contrast(1.1)';
      }
    });
  });

  // Form validasyonu
  const forms = document.querySelectorAll('form');
  forms.forEach(form => {
    form.addEventListener('submit', function(e) {
      const password = this.querySelector('input[type="password"]');
      if (password && password.value.length < 6) {
        e.preventDefault();
        showAlert('Şifre en az 6 karakter olmalıdır!', 'danger');
      }
    });
  });

  // Retro Alert Fonksiyonu
  function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} position-fixed top-0 end-0 m-3`;
    alertDiv.style.backgroundColor = type === 'danger' ? 'var(--vintage-red)' : 'var(--vintage-blue)';
    alertDiv.style.color = 'var(--line)';
    alertDiv.style.border = '2px solid var(--vintage-dark)';
    alertDiv.style.borderRadius = '0';
    alertDiv.style.fontFamily = "'Bebas Neue', sans-serif";
    alertDiv.style.letterSpacing = '1px';
    alertDiv.textContent = message;
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
      alertDiv.style.opacity = '0';
      setTimeout(() => alertDiv.remove(), 500);
    }, 3000);
  }

  // Halı saha çizgileri efekti
  const pitchElements = document.querySelectorAll('.pitch-bg');
  pitchElements.forEach(pitch => {
    pitch.addEventListener('click', function() {
      this.style.transform = 'scale(0.98)';
      setTimeout(() => {
        this.style.transform = 'scale(1)';
      }, 200);
    });
  });
}); 