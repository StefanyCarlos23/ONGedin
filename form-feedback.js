const form = document.getElementById('form')
const campos = document.querySelectorAll('.required')
const spans = document.querySelectorAll('.span-required')
const ratingRadios = document.getElementsByName('rating')

function btnRegisterOnClick(event) {
    let hasError = false;

    // Verificar se a avaliação foi selecionada
    let ratingSelected = false;
    for (let i = 0; i < ratingRadios.length; i++) {
        if (ratingRadios[i].checked) {
            ratingSelected = true;
            break;
        }
    }

    if (!ratingSelected) {
        errorAlert('Preenchimento obrigatório: Avaliação', 0);
        hasError = true;
    } else if (!maxLength(campos[1].value)) {
        maxLengthValidate();        
        hasError = true;
    }

    if (hasError) {
        event.preventDefault();
    } else {
        form.submit();
        document.getElementById('submit').disabled = true;
    }
}

function setError(index) {
    campos[index].style.border = '2px solid #e63636'
    spans[index].style.display = 'block'
    campos[index].focus()
}

function removeError(index) {
    campos[index].style.border = ''
    spans[index].style.display = 'none'
}

function errorAlert(message, index) {
    Swal.fire({
        title: 'Erro!',
        text: message,
        icon: 'error',
        confirmButtonText: 'Entendido',
        confirmButtonColor: '#399aa8',
        timer: 7000,
        timerProgressBar: true
    }).then((result) => {
        if (result.isConfirmed) {
            campos[index].focus()
        }
    })
}

function maxLengthValidate() {
    if (campos[1].value === "") {
        removeError(1);
    } else if (!maxLength(campos[1].value)) {
        setError(1);
    } else {
        removeError(1);
    }
}

function maxLength(input) {
    const re = /^.{1,1000}$/;
    return re.test(input);
}

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
