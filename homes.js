
// ----- MENU MOBILE ----- //
const activeClass = "active";

function animateLinks(navLinks) {
  navLinks.forEach((link, index) => {
    link.style.animation
      ? (link.style.animation = "")
      : (link.style.animation = `navLinkFade 0.5s ease forwards ${index / 7 + 0.3}s`);
  });
}

function handleClick(mobileMenu, navList, navLinks) {
  navList.classList.toggle(activeClass);
  mobileMenu.classList.toggle(activeClass);
  animateLinks(navLinks);
}

function addClickEvent(mobileMenu, navList, navLinks) {
  mobileMenu.addEventListener("click", () => handleClick(mobileMenu, navList, navLinks));
}

function initMobileNavbar(mobileMenuSelector, navListSelector, navLinksSelector) {
  const mobileMenu = document.querySelector(mobileMenuSelector);
  const navList = document.querySelector(navListSelector);
  const navLinks = document.querySelectorAll(navLinksSelector);

  if (mobileMenu) {
    addClickEvent(mobileMenu, navList, navLinks);
  }
}

initMobileNavbar(".mobile-menu", ".nav-list", ".nav-list li");


successAlert('Cadastro realizado com sucesso!');

function successAlert(message) {
    Swal.fire({
        title: 'Parabéns!',
        text: message,
        icon: 'success',
        confirmButtonText: 'Entendido',
        confirmButtonColor: '#399aa8',
        timer: 5000, // O alerta desaparecerá após 5 segundos
        timerProgressBar: true, // Exibe uma barra de progresso
    })
}