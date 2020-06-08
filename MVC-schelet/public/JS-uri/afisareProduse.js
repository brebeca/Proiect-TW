        var id;
        var myArr;
        var produseSelectate = Array();
        var modal = document.getElementById("modalProduse");
        var span = document.getElementsByClassName("inchide")[0];

        span.onclick = function () {
            modal.style.display = "none";
        }
        window.onclick = function (event) {
            if (event.target === document.getElementById("modalProduse"))
                modal.style.display = "none";
        }

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

        function afisareProduseModal(produseArray, product1, product2) {
            let conectivitate = ['Nu','Da'];
            let productsStr = '';
            let scorProd1 = 0;
            let scorProd2 = 0;
            for (let i in produseArray) {
                productsStr += getProductHtmlModal(produseArray[i], id);
            }
            productsStr += `<table style="color: black">`;
            for(let key in product1.details){
                let decorare1=`style="background-color:#85bb65"`;
                let decorare2=`style="background-color:#85bb65"`;
                productsStr += `<tr><td><strong>` + key + `</strong></td>`;
                if(product2.details[key]!==undefined) {
                    if (typeof (product1.details[key]) == 'number') {
                        if (product1.details[key] > product2.details[key]) {
                            decorare2 = ``;
                            scorProd1++;
                        } else if (product1.details[key] < product2.details[key]) {
                            decorare1 = ``;
                            scorProd2++;
                        }
                        if (key === '4G' || key === '2G' || key === '3G') {
                            productsStr += `<td ` + decorare1 + `>` + conectivitate[product1.details[key]] + `</td>
                                        <td ` + decorare2 + `>` + conectivitate[product2.details[key]] + `</td>`;
                        } else {
                            productsStr += `<td ` + decorare1 + `>` + product1.details[key] + `</td>
                                        <td ` + decorare2 + `>` + product2.details[key] + `</td>`;
                        }
                    }
                    else if (key == 'Senzori') {
                            if (product1.details[key].Numar > product2.details[key].Numar) {
                                decorare2 = ``;
                                scorProd1++;
                            } else if (product1.details[key].Numar < product2.details[key].Numar) {
                                decorare1 = ``;
                                scorProd2++;
                            }
                            productsStr += `<td ` + decorare1 + `>` + product1.details[key].Senzori + `</td>
                                        <td ` + decorare2 + `>` + product2.details[key].Senzori + `</td>`;
                            }
                    else if (key == 'Rezolutie video') {
                            decorare1 = ``;
                            decorare2 = ``;
                            if (product1.details[key].includes('4K')) {
                                decorare1 = `style="background-color:#85bb65"`;
                                scorProd1++;
                            } else if (product2.details[key].includes('4K')) {
                                decorare2 = `style="background-color:#85bb65"`;
                                scorProd2++;
                            }
                            productsStr += `<td ` + decorare1 + `>` + product1.details[key] + `</td>
                                        <td ` + decorare2 + `>` + product2.details[key] + `</td>`;
                        }
                    else {
                            productsStr += `<td >` + product1.details[key] + `</td>
                                        <td>` + product2.details[key] + `</td>`;
                        }
                }
                else {
                    productsStr += `<td >` + product1.details[key]+ `</td>
                                        <td >-</td>`;
                }
                    productsStr += `</tr>`;
            }
            let prod2Rezultat = ``;
            let prod1Rezultat = ``;
            if(scorProd2>scorProd1) {
                 prod2Rezultat = `CASTIGATOR`;
                 prod1Rezultat = `mai slab`;
            }else if(scorProd2<scorProd1) {
                 prod1Rezultat = `CASTIGATOR`;
                 prod2Rezultat = `mai slab`;
            }
            else {
                 prod1Rezultat = `Egal`;
                 prod2Rezultat = `Egal`;
            }
            productsStr+=`<tr style="font-size: 20px; size:A3;padding-top: 8px; background: #005cbf;"><td ><strong>Rezultat</strong></td><td>`+prod1Rezultat+`</td><td>`+prod2Rezultat+`</td></tr>`;
            productsStr += `</table>`;
            return productsStr;
        }

         function memorareProdusSelectat(produs_id) {
                for (let i in myArr.produse) {
                    for (let j in myArr.produse[i]) {
                        if (myArr.produse[i][j].id === produs_id)
                            produseSelectate.push(myArr.produse[i][j]);
                    }
                }
                console.log(produs_id);
                if (produseSelectate.length === 2) {
                        document.getElementById("produseSelectate").innerHTML = afisareProduseModal(produseSelectate, produseSelectate[1], produseSelectate[0]);
                    document.getElementById("modalProduse").style.display = "block";
                    produseSelectate.length = 0;
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
        return `<div class="grid-item" id="${product.id}"> 
                  <span class="close" onclick="sterge(${product.id},'${user_id}')">&times;</span>
                  <table onclick="memorareProdusSelectat(${product.id})" >
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
        return `<div class="grid-item" > 
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


        
