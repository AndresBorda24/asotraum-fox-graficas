/** @type {?HTMLDivElement} LOADER */
let LOADER = null;

export function showLoader() {
    const loader = document.getElementById("loader");

    loader.classList.remove('d-none');
}

export function hideLoader() {
    const loader = document.getElementById("loader");

    loader.classList.add('d-none');
}

/**
 * Crea dinamicamente loaders dentro de un elemento parent. Estos elementos 
 * contenedores deben tener position relative.
 * 
 * @param {string} parent Identificador css del contenedor en el que se creará 
 *                        el loader
 */
export function createLoader(parent = 'body') {
    const el = document.querySelector(parent);

    if (el === null) {
        console.warn("No se puede crear el loader, parent no existe");
        return;
    }
    
    if (LOADER === null) {
        console.log("Creando el loader: ", Date.now());
        LOADER = document.createElement("div");
        LOADER.className = "loader-created h-100 w-100 position-absolute bg-black bg-opacity-75 flex top-0 start-0";
        LOADER.style.zIndex = 3000;
        LOADER.innerHTML = `<div class="m-auto text-center">
            <img src="${process.env.APP_PATH}/img/aso-loader.png" alt="loader" width="50">
            <span class="text-light d-block">Cargando...</span>
        </div>`;
    }
    
    el.appendChild(LOADER.cloneNode(true));
}

/**
 * Elimina un loader creado con anterioridad. 
 * 
 * @param {string} parent Identificador css del contenedor en el que se creará 
 *                        el loader
 */
export function removeLoader(parent = 'body') {
    const el = document.querySelector(parent);

    if (el === null) {
        console.warn("No se puede crear el loader, parent no existe");
        return;
    }

    el.querySelectorAll(".loader-created").forEach(loader => loader.remove());
}