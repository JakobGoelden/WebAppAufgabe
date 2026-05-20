function handleSuccessfulLogin(targetUrl) {

    const toastElement = document.getElementById('loginToast');
    

    const toast = new bootstrap.Toast(toastElement);
    
   
    toast.show();
    
    setTimeout(() => {
      window.location.href = targetUrl; 
    }, 200); // wait for 0.2 secs for animation

}

document.querySelectorAll('.subsite').forEach(item => {
    item.addEventListener('click', function() {
        const target = this.getAttribute('href');
        if (target) {
            window.location.href = window.location.origin + "/webapp/" + target.replace(/^\//, "");
        }
    });
});
