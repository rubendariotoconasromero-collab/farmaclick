import Swal from 'sweetalert2';
import '../../css/sweetalert-theme.css';

const baseCustomClass = {
    popup: 'fc-swal',
    confirmButton: 'fc-swal-confirm',
    cancelButton: 'fc-swal-cancel',
    denyButton: 'fc-swal-deny',
};

const appSwal = Swal.mixin({
    buttonsStyling: false,
    customClass: baseCustomClass,
});

export const dangerConfirm = Swal.mixin({
    buttonsStyling: false,
    reverseButtons: true,
    customClass: {
        ...baseCustomClass,
        confirmButton: 'fc-swal-confirm fc-swal-confirm--danger',
    },
});

export const toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 1500,
    timerProgressBar: true,
    customClass: { popup: 'fc-swal fc-swal-toast' },
});

/**
 * Muestra el estado de procesamiento estandar del sistema.
 * Devuelve la promesa de SweetAlert para permitir esperarla cuando sea necesario.
 */
export const showProcessingAlert = ({
    title = 'Procesando solicitud',
    text = 'Estamos preparando la informacion. Esto puede tomar unos segundos.',
} = {}) => appSwal.fire({
    title,
    text,
    allowOutsideClick: false,
    allowEscapeKey: false,
    showConfirmButton: false,
    didOpen: () => Swal.showLoading(),
});

export const closeAlert = () => Swal.close();

export default appSwal;
