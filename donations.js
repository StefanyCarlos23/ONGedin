const form = document.getElementById('form')
const campos = document.querySelectorAll('.required')
const spans = document.querySelectorAll('.span-required')

function btnRegisterOnClick(event) {
    let hasError = false;

    if (campos[0].value === "") {
        errorAlert('Preenchimento obrigatório: Nome', 0);
        hasError = true;
    } else if (!inputWithoutNumbers(campos[0].value)) {
        inputWithoutNumbersValidate(0);
        hasError = true;
    } else if (campos[1].value === "") {
        errorAlert('Preenchimento obrigatório: E-mail', 1);
        hasError = true;
    } else if (!isEmail(campos[1].value)) {
        emailValidate();
        hasError = true;
    }

    if (hasError) {
        event.preventDefault();
    } else {

        form.submit();
        document.getElementById('submit').disabled = true;
    }
}

// Function creates a red border on the input where the condition is not met
function setError(index) {
    campos[index].style.border = '2px solid #e63636'
    spans[index].style.display = 'block'
    campos[index].focus()
}

// Function remove the red border
function removeError(index) {
    campos[index].style.border = ''
    spans[index].style.display = 'none'
}

// Function creates error alert for input that is not filled in
function errorAlert(message, index) {
    Swal.fire({
        title: 'Erro!',
        text: message,
        icon: 'error',
        confirmButtonText: 'Entendido' ,
        confirmButtonColor:'#399aa8',
        timer: 7000,
        timerProgressBar: true
    }).then((result) => {
        if (result.isConfirmed) {
            campos[index].focus()
        }
    })
}

// ----- FUNCTIONS TO VALIDATE THE INPUTS ----- ///
function inputWithoutNumbersValidate() {
    if (campos[0].value === "") {
        removeError(0)
    } else if (!inputWithoutNumbers(campos[0].value)) {
        setError(0)
    } else {
        removeError(0)
    }
}

function emailValidate() {
    if (campos[1].value === "") {
        removeError(1)
    } else if (!isEmail(campos[1].value)) {
        setError(1)
    } else {
        removeError(1)
    }
}

// ----- REGEX ----- ///
// Function to check if the input contains numbers
function inputWithoutNumbers(name) {
    const re = /^[A-Za-zÀ-ÖØ-öø-ÿ\s]+$/
    return re.test(name)
}

// Function to check if is a valid email
function isEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/
    return re.test(email)
}

function mostrarCamposEspecificos() {
    const tipoDoacao = document.getElementById('tipo-doacao').value;
    const doacaoDinheiro = document.getElementById('doacao-dinheiro');
    const doacaoAlimentos = document.getElementById('doacao-alimentos');
    const doacaoRoupas = document.getElementById('doacao-roupas');

    doacaoDinheiro.style.display = 'none';
    doacaoAlimentos.style.display = 'none';
    doacaoRoupas.style.display = 'none';

    if (tipoDoacao === 'dinheiro') {
        doacaoDinheiro.style.display = 'block';
    } else if (tipoDoacao === 'alimentos') {
        doacaoAlimentos.style.display = 'block';
    } else if (tipoDoacao === 'roupas') {
        doacaoRoupas.style.display = 'block';
    }
}

window.onload = mostrarCamposEspecificos;

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

function getUrlParameter(param) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(param);
}

document.addEventListener("DOMContentLoaded", () => {
    const alertType = getUrlParameter('alert');
    const alertMessage = getUrlParameter('message');

    if (alertType && alertMessage) {
        Swal.fire({
            icon: alertType,
            title: alertType === 'success' ? 'Sucesso!' : 'Erro!',
            text: decodeURIComponent(alertMessage),
            confirmButtonText: 'Entendido',
            confirmButtonColor:'#399aa8',
            timer: 7000,
            timerProgressBar: true
        });
    }
});
