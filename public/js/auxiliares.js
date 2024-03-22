export function mensajeAlert(title , text , icon)
{
    Swal.fire({
        title: title,
        text: text,
        icon: icon
      });
}
