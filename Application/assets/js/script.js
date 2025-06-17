document.getElementById('burgerBtn').addEventListener('click', function() {
  const navLinks = document.getElementById('navLinks');
  this.classList.toggle('active');
  navLinks.classList.toggle('active');
  
document.querySelectorAll('.nav-links a').forEach(link => {
  link.addEventListener('click', () => {
    navLinks.classList.remove('active');
    document.getElementById('burgerBtn').classList.remove('active');
    });
  });
});

document.getElementById('foto_profil').addEventListener('change', function(e) {
    var fileName = e.target.files[0] ? e.target.files[0].name : "Belum ada file dipilih";
    document.querySelector('.file-name').textContent = fileName;
});