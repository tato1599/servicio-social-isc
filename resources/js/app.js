import './bootstrap';
import Swal from 'sweetalert2';

window.Swal = Swal;

const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    customClass: {
        popup: 'colored-toast font-bold rounded-xl shadow-xl border border-gray-100',
    },
    didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
    }
});

window.Toast = Toast;

document.addEventListener('livewire:initialized', () => {
    Livewire.on('toast-success', (event) => {
        Toast.fire({
            icon: 'success',
            title: event[0]?.message ?? event.message ?? 'Éxito'
        });
    });

    Livewire.on('toast-error', (event) => {
        Toast.fire({
            icon: 'error',
            title: event[0]?.message ?? event.message ?? 'Error'
        });
    });

    Livewire.on('toast-info', (event) => {
        Toast.fire({
            icon: 'info',
            title: event[0]?.message ?? event.message ?? 'Atención'
        });
    });
});
