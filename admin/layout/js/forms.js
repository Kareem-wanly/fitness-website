// let textInput = document.querySelector(".text-input");

// let passInput = document.querySelector(".pass-input");

// let nameErrorMessage = document.querySelector(".name-error-message");

// let passErrorMessage = document.querySelector(".pass-error-message");

// let eye = document.querySelector(".eye");

// let closeEye = document.querySelector(".close-eye")

// textInput.onblur = () => {

//     if(textInput.value.length < 4) {

//         nameErrorMessage.textContent = "full name must be higher than 4 characters";
        
//     } else {

//         nameErrorMessage.textContent ="";

//     }
// }

// passInput.onblur = () => {

//     if(passInput.value.length < 5) {

//         passErrorMessage.textContent = "password must be higher than 6 characters";
        
//     } else {
        
//         passErrorMessage.textContent ="";

//     }
// }

// eye.onclick = () => {

//     if(passInput.type === "password") {
//         passInput.type = "text";
//     }

//     if(eye.classList.contains("active")) {
//         eye.classList.remove("active");
//         closeEye.classList.add("active");
//     } else {
//         eye.classList.add("active");
//         closeEye.classList.remove("active");
//     }
// }
// closeEye.onclick = () => {

//     if(passInput.type === "text") {
//         passInput.type = "password";
//     }

//     if(closeEye.classList.contains("active")) {
//         closeEye.classList.remove("active");
//         eye.classList.add("active");
//     } else {
//         closeEye.classList.add("active");
//         eye.classList.remove("active");
//     }
// }