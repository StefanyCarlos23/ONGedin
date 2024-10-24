
const name = document.getElementById('name')
const email = document.getElementById('e-mail')
const areaActivity = document.getElementById('area-activity')
const fundationDate = document.getElementById('fundation-date')
const telephone = document.getElementById('telephone')
const socialMedia = document.getElementById('social-media')
const password = document.getElementById('password')
const confirmPass = document.getElementById('confirm-pass')
// Address
const cep = document.getElementById('CEP')
const road = document.getElementById('road')
const num = document.getElementById('num')
const neighborhood = document.getElementById('neighborhood')
const city = document.getElementById('city')
const state = document.getElementById('state')
const country = document.getElementById('country')


const form = document.getElementById('form')
const campos = document.querySelectorAll('.required')
const spans = document.querySelectorAll('.span-required')


function btnRegisterOnClick(event){
    event.preventDefault()

    if (name.value === "") {
        errorAlert('Preenchimento obrigatório: Nome', name)
    }
    else if (email.value === "") {
        errorAlert('Preenchimento obrigatório: E-mail', email)
    }
    else if (areaActivity.value === "") {
        errorAlert('Preenchimento obrigatório: Área de atuação', areaActivity)
    }
    else if (fundationDate.value === "") {
        errorAlert('Preenchimento obrigatório: Data de Fundação', fundationDate)
    }
    else if (telephone.value === "") {
        errorAlert('Preenchimento obrigatório: Telefone', telephone)
    }
    else if (socialMedia.value === "") {
        errorAlert('Preenchimento obrigatório: Rede Social', socialMedia)
    }    
    else if (password.value === "") {
        errorAlert('Preenchimento obrigatório: Senha', password)
    }
    else if (!validPassword(password.value)) {
        errorAlert('Senha inválida', password)
    }
    else if (confirmPass.value === "") {
        errorAlert('Preenchimento obrigatório: Confirme sua senha', confirmPass)
    }
    else if (password.value !== confirmPass.value){
        errorAlert('As senhas não coincidem', confirmPass)
    }
    else if (cep.value === "") {
        errorAlert('Preenchimento obrigatório: CEP', cep)
    }
    else if (!isCEP(cep.value)){
        errorAlert('CEP inválido', cep)
    }
    else if (road.value === "") {
        errorAlert('Preenchimento obrigatório: Rua', road)
    }
    else if (num.value === "") {
        errorAlert('Preenchimento obrigatório: Número', num)
    }
    else if (!isNum(parseInt(num.value))) {
        errorAlert('Número não pode conter letras', num)
    }
    else if (neighborhood.value === "") {
        errorAlert('Preenchimento obrigatório: Bairro', neighborhood)
    }
    else if (inputWithoutNumbers(neighborhood.value)) {
        errorAlert('Bairro não pode conter números',neighborhood)
    }
    else if (city.value === "") {
        errorAlert('Preenchimento obrigatório: Cidade', city)
    }
    else if (inputWithoutNumbers(city.value)) {
        errorAlert('Cidade não pode conter números',city)
    }
    else if (state.value === "") {
        errorAlert('Preenchimento obrigatório: Estado', state)
    }
    else if (inputWithoutNumbers(state.value)) {
        errorAlert('Estado não pode conter números',state)
    }
    else if (country.value === "") {
        errorAlert('Preenchimento obrigatório: Páis', country)
    }
    else if (inputWithoutNumbers(country.value)) {
        errorAlert('Páis não pode conter números',country)
    }
    else {
        successAlert('Cadastro realizado com sucesso!');
        setTimeout(() => {
        form.submit()
    }, 5000); 
        setTimeout(() => {
        window.location.href = "home.html"
    }, 1500);
    }
}


// Function creates a red border on the input where the condition is not met
function setError(index) {
    campos[index].style.border = '2px solid #e63636'
    spans[index].style.display = 'block'
    campos[index].focus();
}

// Function remove the red border
function removeError(index) {
    campos[index].style.border = ''
    spans[index].style.display = 'none'
}

// Function creates error alert for input that is not filled in
function errorAlert(message, input) {
    Swal.fire({
        title: 'Erro!',
        text: message,
        icon: 'error',
        confirmButtonText: 'Entendido' ,
        confirmButtonColor:'#399aa8'
    }).then((result) => {
        if (result.isConfirmed) {
            input.focus();
        }
    });
}

// Function creates a success alert when de form is submit 
function successAlert(message) {
    Swal.fire({
        title: 'Parabéns!',
        text: message,
        icon: 'success',
        confirmButtonText: 'Entendido',
        confirmButtonColor: '#399aa8'
    });
}

// Function 
function nameValidate() {
    if (inputWithoutNumbers(campos[0].value)) {
        setError(0);
    }
    else{
        removeError(0)
    }
}

function emailValidate() {
    if (!isEmail(campos[1].value)) {
        setError(1);
    }
    else{
        removeError(1)
    }
}

function areaActivityValiate() {
    if (inputWithoutNumbers(campos[2].value)) {
        setError(2);
    }
    else{
        removeError(2)
    }
}

function fundationDateValiate() {
    if (!isValidDate(campos[3].value)) {
        setError(3);
    }
    else{
        removeError(3)
    }
}

function telephoneValiate() {
    if (!isValidDate(campos[4].value)) {
        setError(4);
    }
    else{
        removeError(4)
    }
}


// ----- REGEX ----- ///

// Function to check if the input contains numbers
function inputWithoutNumbers(input) {
    const re = /\d+/
    return re.test(input)
}

// Function to check if is a valid email
function isEmail(email) {
    const re = /^\w.+@\w{3}.*\.\w{2,}$/
    return re.test(email)
}

// Function to check if is a valid date
function isValidDate(date) {
    const re = /^(0[1-9]|[12]\d|3[01])\/?(0[1-9]|1[0-2])\/?(19|20)\d\d$/;

    if (!re.test(date)) {
        return false; 
    }

    let day, month, year;
    if (date.includes('/')) {
        [day, month, year] = date.split('/').map(Number);
    } else {
        day = Number(date.slice(0, 2)); 
        month = Number(date.slice(2, 4));  
        year = Number(date.slice(4, 8)); 
    }

    const daysInMonth = [31, 28 + (isLeapYear(year) ? 1 : 0), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

    return day <= daysInMonth[month - 1];
}

// Function to check if a year is a leap year
function isLeapYear(year) {
    return (year % 4 === 0 && year % 100 !== 0) || (year % 400 === 0);
}

// Function to check if is a valid password
function validPassword(password) {
    const re =  /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&\.])[A-Za-z\d@$!%*?&\.]{8,}$/
    return re.test(password)
}

// Function to check if is a valid telephone
function isTelephone(telephone) {
    const re = /^(\+55\s?)?(55\s?)?\d{2}\s?9?\d{4}-?\d{4}$/
    return re.test(telephone)
}

// Function to check if is a valid CEP
function isCEP(cep){
    const re = /^\d{2}\.?\d{3}-?\d{3}$/
    return re.test(cep)
}

// Function to check if is positive numbers
function isNum(num) {
    return !isNaN(num) && num > 0
}