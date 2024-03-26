/**
 *
 *
 * @param {Esta informacion la obtenemos a partir de una peticion al servidor. Dicho valor es un json} json
 */

export function crearExcelDetails(json, nombreArchivo) {
    const workbook = XLSX.utils.book_new();

    //Crea una hoja de Excel
    const worksheet = XLSX.utils.json_to_sheet(json);

    // Agrega la hoja al libro
    XLSX.utils.book_append_sheet(workbook, worksheet, "Resumen vacaciones");

    // Descarga el archivo Excel
    XLSX.writeFile(workbook, `${nombreArchivo}`);
}
