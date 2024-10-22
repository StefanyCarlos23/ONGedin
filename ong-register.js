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
const complement = document.getElementById('complement')



function btnRegisterOnClick(){
    if (name.value === "") {
        alert('Preenchimento obrigatório: Nome')
        name.focus()
    }
    else if (inputWithoutNumbers(name.value)) {
        alert('Tipo de dado inválido: Nome')
        name.focus()
    }
    else if (email.value === "") {
        alert('Preenchimento obrigatório: E-mail')
        email.focus()
    }
    else if (!isEmail(email.value)) {
        alert('Tipo de dado inválido: E-mail')
        email.focus()
    }
    else if (areaActivity.value === "") {
        alert('Preenchimento obrigatório: Área de Atividdade')
        areaActivity.focus()
    }
    else if (inputWithoutNumbers(areaActivity.value)) {
        alert('Tipo de dado inválido: Área de atividade')
        areaActivity.value = ""
        areaActivity.focus()
    }
    else if (fundationDate.value === "") {
        alert('Preenchimento obrigatório: Data de Fundação')
        fundationDate.focus()
    }
    else if (inputWithoutNumbers(fundationDate.value)) {
        alert('Tipo de dado inválido: Data de Fundação')
        fundationDate.value = ""
        fundationDate.focus()
    }
    else if (telephone.value === "") {
        alert('Preenchimento obrigatório: Telefone')
        telephone.focus()
    }
    else if (!isTelephone(telephone.value)) {
        alert('Tipo de dado inválido: Telefone')
        telephone.focus()
    }
    else if (socialMedia.value === "") {
        alert('Preenchimento obrigatório: Rede Social')
        socialMedia.focus()
    }
    else if (password.value === "") {
        alert('Preenchimento obrigatório: Senha')
        password.focus()
    }
    else if (!validPassword(password.value)) {
        alert('Tipo de de dado inválido: Senha')
        password.focus()
    }
    else if (confirmPass.value === "") {
        alert('Preenchimento obrigatório: Confirme sua senha')
        confirmPass.focus()
    }
    else if (password.value !== confirmPass.value){
        alert('As senhas não coincidem')
        confirmPass.focus()
    }
    else if (cep.value === "") {
        alert('Preenchimento obrigatório: CEP')
        cep.focus()
    }
    else if (!isCEP(cep.value)){
        alert('Tipo de de dado inválido: CEP')
        cep.focus()
    }
    else if (road.value === "") {
        alert('Preenchimento obrigatório: Rua')
        road.focus()
    }
    else if (inputWithoutNumbers(road.value)) {
        alert('Tipo de dado inválido: Rua')
        road.focus()
    }
    else if (num.value === "") {
        alert('Preenchimento obrigatório: Número')
        num.focus()
    }
    else if (!isNum(parseInt(num.value))) {
        alert('Tipo de de dado inválido: Número')
        num.focus()
    }
    else if (neighborhood.value === "") {
        alert('Preenchimento obrigatório: Bairro')
        neighborhood.focus()
    }
    else if (inputWithoutNumbers(neighborhood.value)) {
        alert('Tipo de dado inválido: Bairro')
        neighborhood.focus()
    }
    else if (city.value === "") {
        alert('Preenchimento obrigatório: Cidade')
        city.focus()
    }
    else if (inputWithoutNumbers(city.value)) {
        alert('Tipo de dado inválido: Cidade')
        city.focus()
    }
    else if (state.value === "") {
        alert('Preenchimento obrigatório: Estado')
        state.focus()
    }
    else if (inputWithoutNumbers(state.value)) {
        alert('Tipo de dado inválido: Estado')
        state.focus()
    }
    else if (country.value === "") {
        alert('Preenchimento obrigatório: País')
        country.focus()
    }
    else if (inputWithoutNumbers(country.value)) {
        alert('Tipo de dado inválido: País')
        state.focus()
    }
    else {
        form.submit()
    }
}

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

// Function to check if is a valid password
function validPassword(password) {
    const re =  /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/
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
    return !isNaN(num) && num > 0;
}