document.getElementById("togglePass").addEventListener("click", pass);

function pass() {
   const passwordInput = document.getElementById('password');
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        this.classList.remove('bi-eye-slash');
        this.classList.add('bi-eye');
      } else {
        passwordInput.type = 'password';
        this.classList.remove('bi-eye');
        this.classList.add('bi-eye-slash');
      }
}