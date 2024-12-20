const form = document.getElementById('form')
const campos = document.querySelectorAll('.required')
const spans = document.querySelectorAll('.span-required')

function btnRegisterOnClick(event){
    let hasError = false;

    if (campos[0].value === "") {
        errorAlert('Preenchimento obrigatório: Nome', 0)
        hasError = true;
    } else if (!inputWithoutNumbers(campos[0].value)){
        inputWithoutNumbersValidate()
        hasError = true;
    } else if (campos[1].value === "") {
        errorAlert('Preenchimento obrigatório: CPF', 1)
        hasError = true;
    } else if (!isCPF(campos[1].value)) {
        cpfValidate()
        hasError = true;
    } else if (campos[2].value === "") {
        errorAlert('Preenchimento obrigatório: Data de Fundação', 2)
        hasError = true;
    } else if (!isValidDate(campos[2].value)) {
        dateValidate()
        hasError = true;
    } else if (campos[3].value === "") {
        errorAlert('Preenchimento obrigatório: Telefone', 3)
        hasError = true;
    } else if (!isTelephone(campos[3].value)) {
        telephoneValidate()
        hasError = true;
    } else if (campos[4].value === "") {
        errorAlert('Preenchimento obrigatório: E-mail', 4)
        hasError = true;
    } else if (!isEmail(campos[4].value)) {
        emailValidate()
        hasError = true;
    } else if (campos[5].value === "") {
        errorAlert('Preenchimento obrigatório: Senha', 5)
        hasError = true;
    } else if (!validPassword(campos[5].value)) {
        passwordValidate()
        hasError = true;
    } else if (campos[6].value === "") {
        errorAlert('Preenchimento obrigatório: Confirme sua senha', 7)
        hasError = true;
    } else if (campos[5].value !== campos[6].value){
        confirmPasswordValidate()
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

function cpfValidate(){
    if (campos[1].value === "") {
        removeError(1)
    } else if (!isCPF(campos[1].value)) {
        setError(1)
    } else{
        removeError(1)
    }
}

function dateValidate() {
    if (campos[2].value === "") {
        removeError(2)
    } else if (!isValidDate(campos[2].value)) {
        setError(2)
    } else{
        removeError(2)
    }
}

function telephoneValidate() {
    if (campos[3].value === "") {
        removeError(3)
    } else if (!isTelephone(campos[3].value)) {
        setError(3)
    } else{
        removeError(3)
    }
}

function emailValidate() {
    if (campos[4].value === "") {
        removeError(4)
    } else if (!isEmail(campos[4].value)) {
        setError(4)
    } else {
        removeError(4)
    }
}

function passwordValidate() {
    if (campos[5].value === "") {
        removeError(5)
    } else if (!validPassword(campos[5].value)) {
        setError(5)
    } else{
        removeError(5)
    }
}

function confirmPasswordValidate() {
    if (campos[6].value === "") {
        removeError(6)
    } else if ((campos[5].value !== campos[6].value)) {
        setError(6)
    } else{
        removeError(6)
    }
}

// ----- REGEX ----- ///
// Function to check if the input contains numbers
function inputWithoutNumbers(name) {
    const re = /^[A-Za-zÀ-ÖØ-öø-ÿ\s]+$/
    return re.test(name)
}

function isCPF(cpf){
    const re = /^\d{3}\.?\d{3}\.?\d{3}-?\d{2}$/
    return re.test(cpf) 
}

// Function to check if is a valid date
function isValidDate(date) {
    const re = /^(\d{2})\/(\d{2})\/(\d{4})$/;

    if (!re.test(date)) {
        return false;
    }

    const [day, month, year] = date.split('/').map(Number);

    if (year < 1895 || year > 2024) {
        return false;
    }

    const daysInMonth = [31, 28 + (isLeapYear(year) ? 1 : 0), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

    return day > 0 && month > 0 && month <= 12 && day <= daysInMonth[month - 1];
}

// Function to check if a year is a leap year
function isLeapYear(year) {
    return (year % 4 === 0 && year % 100 !== 0) || (year % 400 === 0);
}


function isTelephone(telephone) {
    const re = /^(\+55\s?)?(55\s?)?\d{2}\s?9?\d{4}-?\d{4}$/
    return re.test(telephone)
}

// Function to check if is a valid email
function isEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/
    return re.test(email)
}

// Function to check if is a valid password
function validPassword(password) {
    const re =  /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&\.])[A-Za-z\d@$!%*?&\.]{8,}$/
    return re.test(password)
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

// Inicializa o menu mobile
initMobileNavbar(".mobile-menu", ".nav-list", ".nav-list li");