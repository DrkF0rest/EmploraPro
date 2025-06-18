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

document.querySelectorAll('.td-btn').forEach(tdBtn => {
  tdBtn.addEventListener('click', function() {
    const tdContent = this.nextElementSibling;
    this.classList.toggle('active');
    tdContent.classList.toggle('active');

    document.querySelectorAll('.td-content a').forEach(link => {
      link.addEventListener('click', () => {
        tdContent.classList.remove('active');
        tdBtn.classList.remove('active');
      });
    });

    document.querySelectorAll('.td-content').forEach(dropdown => {
      if (dropdown !== tdContent) dropdown.classList.remove('active');
    });

    document.addEventListener('click', function(e) {
      if (!e.target.closest('.td-btn') && !e.target.closest('.td-content')) {
        document.querySelectorAll('.td-btn, .td-content').forEach(el => {
          el.classList.remove('active');
        });
      }
    });
  });
});

document.getElementById('foto_profil').addEventListener('change', function(e) {
    var fileName = e.target.files[0] ? e.target.files[0].name : "Belum ada file dipilih";
    document.querySelector('.file-name').textContent = fileName;
});