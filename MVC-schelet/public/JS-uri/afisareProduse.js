        var id;
        var myArr;

        function load() {
            let url = new URL('http://localhost:800/produse/incarcaProduse');
            let params = {
                id: id
            } ;
            url.search = new URLSearchParams(params).toString();
            const http = new XMLHttpRequest();

            http.open("GET", url, true);
            http.onreadystatechange = function()
            {
                if(http.readyState === 4 && http.status === 200) {
                    myArr = JSON.parse(http.responseText);
                    console.log(myArr);
                    let productsStr = '';
                    if (myArr["Success"] === "true") {
                        for (var i in myArr.produse) {
                          for (var j in myArr.produse[i]) {
                              if(myArr.produse[i].indexOf(myArr.produse[i][j]) === 0)
                                  productsStr += `<p><div class="first">` + getProductHtml(myArr.produse[i][j]) + `</div></p>`;
                              else
                                  productsStr += getProductHtml(myArr.produse[i][j]);
                          }
                        }
                    }
                    document.getElementById('products').innerHTML = productsStr;
                }
            }
            http.send(null);
        }

        load();

        function getProductHtml(product){
         return `<div class="grid-item">
                  <span class="close">&times;</span>
                  <table>
                   <tr>
                    <td><img class="aimg" src= "${product.img_link}"></img><td>
                    <td>
                     <ul class="pret">
                       <li>Pret: ${product.price} </li>
                       <li>Rating: <div class="rating" style="--rating:${product.rating};"></div></li>
                     </ul>
                    </td>
                   </tr>
                  </table>
                  <br>
                  <p class="titlu"> ${product.title}</p><br>
                  <p> <a href="${product.link}">${product.link}</a></p>
                </div>`;
        }

        function getProductDetailsHtml(product){
            var productsStr = '';
            productsStr += `<ol>`;
            for(var k in product.details){
                if(product.details[k].search(":") >= 0){
                    productsStr += `<li>` + product.details[k];
                }
                else{
                    productsStr += product.details[k];
                }

                if(product.details[k+1] <= product.details.length  && product.details[k+1].search(":") >= 0){
                    productsStr += `</li>`;
                }
            }
            productsStr += `</ol>`; 
            return productsStr;
        }
