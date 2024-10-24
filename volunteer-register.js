
const name = document.getElementById('name')
const cpf = document.getElementById('cpf')
const dateBirth = document.getElementById('date-birth')
const telephone = document.getElementById('telephone')
const email = document.getElementById('email')
const password = document.getElementById('password')
const confirmPass = document.getElementById('confirm-pass')



function btnRegisterOnClick(event){
    event.preventDefault();

    if (name.value === "") {
        errorAlert('Preenchimento obrigatório: Nome', name)
    }
    else if (inputWithoutNumbers(name.value)) {
        errorAlert('Nome não pode conter números', name)
    }
    else if (cpf.value === "") {
        errorAlert('Preenchimento obrigatório: CPF', cpf)
    }
    else if (!isCPF(cpf.value)) {
        errorAlert('CPF inválido', cpf)
    }
    else if (dateBirth.value === "") {
        errorAlert('Preenchimento obrigatório: Data de nascimento', dateBirth)
    }
    else if (!isValidDate(dateBirth.value)) {
        errorAlert('Data de nascimento inválida', dateBirth)
    }
    else if (telephone.value === "") {
        errorAlert('Preenchimento obrigatório: Telefone', telephone)
    }
    else if (!isTelephone(telephone.value)) {
        errorAlert('Telefone inválido', telephone)
    }
    else if (email.value === "") {
        errorAlert('Preenchimento obrigatório: E-mail', email)
    }
    else if (!isEmail(email.value)) {
        errorAlert('E-mail inválido', email)
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
    else {
        successAlert('Cadastro realizado com sucesso!');
        setTimeout(() => {
        form.submit()
    }, 5000); 
        setTimeout(() => {
        window.location.href = "home.html";
    }, 1500);
    }
}


// Function to check if the input contains numbers
function inputWithoutNumbers(input) {
    const re = /\d+/
    return re.test(input)
}

// Function to check if is a valid CPF
function isCPF(cpf) {
    const re = /^\d{3}\.?\d{3}\.?\d{3}-?\d{2}$/
    return re.test(cpf)
}

// Function to check if is a valid date of birth
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


// Function to check if is a valid telephone
function isTelephone(telephone) {
    const re = /^(\+55\s?)?(55\s?)?\d{2}\s?9?\d{4}-?\d{4}$/
    return re.test(telephone)
}

// Function to check if is a valid email
function isEmail(email) {
    const re = /^\w.+@\w{3}.*\.\w{2,}$/
    return re.test(email)
}

// Function to check if is a valid password
function validPassword(password) {
    const re =  /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&\.])[A-Za-z\d@$!%*?&\.]{8,}$/
    return re.test(password)
}

// Function displays an error message
function errorAlert(message, input) {
    Swal.fire({
        title: 'Erro!',
        text: message,
        icon: 'error',
        confirmButtonText: 'Entendido',
        confirmButtonColor: "#399aa8"
    }).then((result) => {
        if (result.isConfirmed) {
            input.focus();
        }
    });
}

function successAlert(message) {
    Swal.fire({
        title: 'Parabéns!',
        text: message,
        icon: 'success',
        confirmButtonText: 'Entendido',
        confirmButtonColor: "#399aa8",
    });
}

