export const usePage = () => {
     return JSON.parse(document.getElementById('app').getAttribute('data-page'))
}
