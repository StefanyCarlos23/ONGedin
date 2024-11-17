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
        errorAlert('Preenchimento obrigatório: Descrição', 1);
        hasError = true;
    } else if (!maxLength(campos[1].value)) {
        maxLengthValidate();        
        hasError = true;
    } else if (campos[2].value === "") {
        errorAlert('Preenchimento obrigatório: Data do Evento', 2);
        hasError = true;
    } else if (!isValidDate(campos[2].value)) {
        dateValidate()
        hasError = true;
    } else if (campos[3].value === "") {
        errorAlert('Preenchimento obrigatório: Rua', 3);
        hasError = true;
    } else if (!isRoad(campos[3].value)) {
        roadValidate();
        hasError = true;
    } else if (campos[4].value === "") {
        errorAlert('Preenchimento obrigatório: Número', 4);
        hasError = true;
    } else if (!isNum(parseInt(campos[4].value))) {
        numValidate();
        hasError = true;
    } else if (campos[5].value === "") {
        errorAlert('Preenchimento obrigatório: Bairro', 5);
        hasError = true;
    } else if (!inputWithoutNumbers(campos[5].value)) {
        inputWithoutNumbersValidate(5);
        hasError = true;
    } else if (campos[6].value === "") {
        errorAlert('Preenchimento obrigatório: Cidade', 6);
        hasError = true;
    } else if (!inputWithoutNumbers(campos[6].value)) {
        inputWithoutNumbersValidate(6);
        hasError = true;
    } else if (campos[7].value === "") {
        errorAlert('Preenchimento obrigatório: Estado', 7);
        hasError = true;
    } else if (!inputWithoutNumbers(campos[7].value)) {
        inputWithoutNumbersValidate(7);
        hasError = true;
    } else if (campos[8].value === "") {
        errorAlert('Preenchimento obrigatório: País', 8);
        hasError = true;
    } else if (!inputWithoutNumbers(campos[8].value)) {
        inputWithoutNumbersValidate(8);
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

// ----- FUNCTIONS TO VALIDATE THE INPUTS ----- //
function inputWithoutNumbersValidate(index) {
    if (campos[index].value === "") {
        removeError(index)
    } else if (!inputWithoutNumbers(campos[index].value)) {
        setError(index)
    } else {
        removeError(index)
    }
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

function dateValidate() {
    if (campos[2].value === "") {
        removeError(2)
    } else if (!isValidDate(campos[2].value)) {
        setError(2)
    } else{
        removeError(2)
    }
}


function roadValidate() {
    if (campos[3].value === "") {
        removeError(3)
    } else if (!isRoad(campos[3].value)) {
        setError(3)
    } else{
        removeError(3)
    }
}

function numValidate() {
    if (campos[4].value === "") {
        removeError(4)
    } else if (!isNum(campos[4].value)) {
        setError(4)
    } else{
        removeError(4)
    }
}

// ----- REGEX ----- //

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

// Function to check if the input contains numbers
function inputWithoutNumbers(index) {
    const re = /^[A-Za-zÀ-ÖØ-öø-ÿ\s]+$/
    return re.test(index)
}

// Function to check the max length
function maxLength(input) {
    const re = /^.{1,1000}$/
    return re.test(input);
}

// Function to check if is a valid road
function isRoad(road){
    const re = /^[A-Za-z0-9\s]+$/
    return re.test(road)
}

// Function to check if is positive numbers
function isNum(num) {
    return !isNaN(num) && num > 0
}

//----- MENU MOBILE -----//
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