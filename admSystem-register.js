const form = document.getElementById('form')
const campos = document.querySelectorAll('.required')
const spans = document.querySelectorAll('.span-required')


function btnRegisterOnClick(event){
    event.preventDefault()

    if (campos[0].value === "") {
        errorAlert('Preenchimento obrigatório: Nome', 0)
    } else if (!inputWithoutNumbers(campos[0].value)){
        inputWithoutNumbersValidate(0)
    } else if (campos[1].value === "") {
        errorAlert('Preenchimento obrigatório: E-mail', 1)
    } else if (!isEmail(campos[1].value)) {
        emailValidate()
    } else if (campos[4].value === "") {
        errorAlert('Preenchimento obrigatório: Telefone', 2)
    } else if (!isTelephone(campos[2].value.value)) {
        telephoneValidate()
    } else if (campos[3].value === "") {
        errorAlert('Preenchimento obrigatório: Senha', 3)
    } else if (!validPassword(campos[3].value)) {
        passwordValidate()
    } else if (campos[4].value === "") {
        errorAlert('Preenchimento obrigatório: Confirme sua senha', 4)
    } else if (campos[4].value !== campos[4].value){
        confirmPasswordValidate()
    } else {
        successAlert('Cadastro realizado com sucesso!')
        setTimeout(() => {
        form.submit()
    }, 5000)
        setTimeout(() => {
        window.location.href = "home.html"
    }, 1500)
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
        confirmButtonColor:'#399aa8'
    }).then((result) => {
        if (result.isConfirmed) {
            campos[index].focus()
        }
    })
}

// Function creates a success alert when de form is submit 
function successAlert(message) {
    Swal.fire({
        title: 'Parabéns!',
        text: message,
        icon: 'success',
        confirmButtonText: 'Entendido',
        confirmButtonColor: '#399aa8'
    })
}

// ----- FUNCTIONS TO VALIDATE THE INPUTS ----- ///
function inputWithoutNumbersValidate(index) {
    if (campos[index].value === "") {
        removeError(index)
    } else if (!inputWithoutNumbers(campos[index].value)) {
        setError(index)
    } else {
        removeError(index)
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

function telephoneValidate() {
    if (campos[2].value === "") {
        removeError(2)
    } else if (!isTelephone(campos[2].value)) {
        setError(2)
    } else{
        removeError(2)
    }
}

function passwordValidate() {
    if (campos[3].value === "") {
        removeError(3)
    } else if (!validPassword(campos[3].value)) {
        setError(3)
    } else{
        removeError(3)
    }
}

function confirmPasswordValidate() {
    if (campos[4].value === "") {
        removeError(4)
    } else if ((campos[3].value !== campos[4].value)) {
        setError(4)
    } else{
        removeError(4)
    }
}


// ----- REGEX ----- ///
// Function to check if the input contains numbers and especial caracter
function inputWithoutNumbers(index) {
    const re = /^[A-Za-z]+$/
    return re.test(index)
}

// Function to check if is a valid email
function isEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/
    return re.test(email)
}

// Function to check if is a valid telephone
function isTelephone(telephone) {
    const re = /^(\+55\s?)?(55\s?)?\d{2}\s?9?\d{4}-?\d{4}$/
    return re.test(telephone)
}

// Function to check if is a valid password
function validPassword(password) {
    const re =  /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&\.])[A-Za-z\d@$!%*?&\.]{8,}$/
    return re.test(password)
}