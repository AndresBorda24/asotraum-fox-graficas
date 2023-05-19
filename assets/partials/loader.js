export function showLoader() {
    const loader = document.getElementById("loader");

    loader.classList.remove('d-none');
}

export function hideLoader() {
    const loader = document.getElementById("loader");

    loader.classList.add('d-none');
}
