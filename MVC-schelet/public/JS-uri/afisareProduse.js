        var id;
        var myArr;
        var produseSelectate = Array();
       // var modal = document.getElementById("modalProduse");

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
                    if (myArr["Success"] === "true") {
                        document.getElementById('products').innerHTML = afisareProduse(myArr);
                    }
                }
            }
            http.send(null);
        }

        load();

        function afisareProduse(produseArray) {
            let productsStr = '';
            for (let i in produseArray.produse) {
                for (let j in produseArray.produse[i]) {
                    if(produseArray.produse[i].indexOf(produseArray.produse[i][j]) === 0)
                        productsStr += `<h3 class="categorie">` + produseArray.produse[i][j].category + `: </h3>` +
                                        `<div class="first" id="${produseArray.produse[i][j].id}">` 
                                          + getProductHtml(produseArray.produse[i][j], id) +
                                        `</div> <div></div> <div></div>`;
                    else
                        productsStr += getProductHtml(produseArray.produse[i][j], id);
                }
            }
            return productsStr;
        }

        function afisareProduseModal(produseArray) {
            let productsStr = '';
            console.log(produseArray);
            for (let i in produseArray) {
                productsStr += getProductHtmlModal(produseArray[i], id);

            }
                return productsStr;
            }


            var span = document.getElementsByClassName("inchide")[0];
            // When the user clicks on <span> (x), close the modal
            span.onclick = function () {
                modal.style.display = "none";
            }
            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function (event) {
                if (event.target === document.getElementById("modalProduse")) {
                    modal.style.display = "none";
                }
            }

            function memorareProdusSelectat(produs_id) {
                for (let i in myArr.produse) {
                    for (let j in myArr.produse[i]) {
                        if (myArr.produse[i][j].id === produs_id)
                            produseSelectate.push(myArr.produse[i][j]);
                    }
                }
                if (produseSelectate.length >= 2) {
                    document.getElementById("produseSelectate").innerHTML = afisareProduseModal(produseSelectate);
                    document.getElementById("modalProduse").style.display = "block";
                    //produseSelectate.length = 0;
                }
            }

            function comparaPret() {
                //ordonam crescator produsele dupa pret
                for (let i in myArr["produse"]) {
                    myArr.produse[i].sort(function (a, b) {
                        return a.price - b.price
                    });
                }
                //afisam produsele ordonate dupa pret
                document.getElementById('products').innerHTML = afisareProduse(myArr);
            }

            function comparaRating() {
                //ordonam descrescator produsele dupa rating
                for (let i in myArr["produse"]) {
                    myArr.produse[i].sort(function (a, b) {
                        return b.rating - a.rating
                    });
                }
                //afisam produsele ordonate dupa rating
                document.getElementById('products').innerHTML = afisareProduse(myArr);
            }

            function getProductHtml(product, user_id) {
                return `<div class="grid-item" onclick="memorareProdusSelectat(${product.id})"> 
                  <span class="close" onclick="sterge(${product.id},'${user_id}')">&times;</span>
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
                  <p><a class="titlu" href="${product.link}">${product.title}</a></p><br>
                </div>`;
            }

            function getProductHtmlModal(product, user_id) {
                return `<div class="grid-item"> 
                  <span class="close" onclick="sterge(${product.id},'${user_id}')">&times;</span>
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
                  <p><a class="titlu" href="${product.link}">${product.title}</a></p><br>
                </div>`;
            }

            function getProductDetailsHtml(product) {
                var productsStr = '';
                productsStr += `<ol>`;
                for (var k in product.details) {
                    if (product.details[k].search(":") >= 0) {
                        productsStr += `<li>` + product.details[k];
                    } else {
                        productsStr += product.details[k];
                    }

                    if (product.details[k + 1] <= product.details.length && product.details[k + 1].search(":") >= 0) {
                        productsStr += `</li>`;
                    }
                }
                productsStr += `</ol>`;
                return productsStr;
            }

            function RSS() {
                let lis = document.getElementsByClassName("first");
                let idList = Array();
                for (let i = 0, len = lis.length | 0; i < len; i = i + 1 | 0) {
                    idList.push(parseInt(lis[i].getAttribute("id")));
                }
                let rssObjects = Array();
                for (let i in myArr.produse) {
                    for (let j in myArr.produse[i]) {
                        if (idList.includes(myArr.produse[i][j].id)) {
                            let rssObject = {
                                title: myArr.produse[i][j].title,
                                link: myArr.produse[i][j].link,
                                description: myArr.produse[i][j].details,
                                category: myArr.produse[i][j].category
                            }
                            rssObjects.push(rssObject);
                        }
                    }
                }
                const xmlhttp = new XMLHttpRequest();
                xmlhttp.open("POST", "http://localhost:800/produse/RSS");
                xmlhttp.setRequestHeader("Content-Type", "application/json");
                xmlhttp.onreadystatechange = function () {
                    if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                        console.log(xmlhttp.responseText);
                        window.location.replace('http://localhost:800/produse/renderRSS?rss=' + xmlhttp.responseText);
                    }
                }
                xmlhttp.send(JSON.stringify(rssObjects));
            }


        
