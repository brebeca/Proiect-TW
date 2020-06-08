function sterge(id_produs,id_user) {
    let el = document.getElementById(id_produs);
    el.remove();
    let url = new URL('http://localhost:800/produse/stergeProdus');
    let params = {
        product_id: id_produs,
        session:id_user

    } ;
    url.search = new URLSearchParams(params).toString();
    const http = new XMLHttpRequest();

    http.open("GET", url, true);
    http.onreadystatechange = function()
    {
        if(http.readyState === 4 && http.status === 200) {
            console.log(http.responseText);
        }
    }
    http.send(null);
}