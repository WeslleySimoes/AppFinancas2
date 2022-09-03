<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <button id="addCombobox">Adicionar</button>
    <div id="totalCategorias">Total: R$</div>
    <form action="#" id="form_planejamento">
    </form>

    <script>        
        const form_planejamento   = document.querySelector("#form_planejamento");
        const addCombobox         = document.querySelector('#addCombobox');
        const totalCategorias     = document.querySelector('div#totalCategorias');
        
        const limite   = 10;
        let   addTotal = 0;

        form_planejamento.innerHTML = '';

       

        addCombobox.onclick = () => {

            if(addTotal < 10)
            {
                
                form_planejamento.innerHTML += '<div><select name="cars" id="cars">\
                                                    <option value="volvo">Volvo</option>\
                                                    <option value="saab">Saab</option>\
                                                    <option value="mercedes">Mercedes</option>\
                                                    <option value="audi">Audi</option>\
                                                </select> <input type="number" name="test"  class="formValorCate" value="0"></div>';  
                addTotal++;
                
                let formValorCate  = document.querySelectorAll("input.formValorCate");
                
                for (let index = 0; index < formValorCate.length; index++) 
                {
                    formValorCate[index].addEventListener("focusout",() => {

                        if(formValorCate[index].value == '')
                        {
                            formValorCate[index].value = 0;
                        }
                        else{
                            formValorCate[index].value =  formValorCate[index].value;
                        }



                        let soma = 0;
                        for (let index = 0; index < formValorCate.length; index++) 
                        {
                            soma += parseInt(formValorCate[index].value);

                        }
                        totalCategorias.innerHTML = soma;
                    });
                    
                }


                // console.log(formValorCate.length);

            }


        }



    </script>
</body>
</html>