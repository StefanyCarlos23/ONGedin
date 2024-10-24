
const name = document.getElementById('name')
const email = document.getElementById('e-mail')
const telephone = document.getElementById('telephone')
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
    else if (email.value === "") {
        errorAlert('Preenchimento obrigatório: E-mail', email)
    }
    else if (!isEmail(email.value)) {
        errorAlert('E-mail inválido', email)
    }
    else if (telephone.value === "") {
        errorAlert('Preenchimento obrigatório: Telefone', telephone)
    }
    else if (!isTelephone(telephone.value)) {
        errorAlert('Telefone inválido', telephone)
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

// Function to check if is a valid telephone
function isTelephone(telephone) {
    const re = /^(\+55\s?)?(55\s?)?\d{2}\s?9?\d{4}-?\d{4}$/
    return re.test(telephone)
}

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
        confirmButtonColor: '#399aa8'
    });
}

