import Swal from 'sweetalert2'


const Toast = Swal.mixin({
    toast: true,
    position: 'top-right',
    showConfirmButton: false,
    timer: 1500,
    timerProgressBar: true,
  });

Livewire.on('success', (msg) => {
    Toast.fire({
        icon: 'success',
        title: msg,
      });
    console.log(msg);
})

Livewire.on('info', (msg) => {
    Toast.fire({
        icon: 'info',
        title: msg,
      });
})

Livewire.on('error', (msg) => {
    Toast.fire({
        icon: 'error',
        title: msg,
      });

})
