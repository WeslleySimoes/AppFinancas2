//POPUP-form-remove
   
const removeRFP = document.querySelectorAll('.remove-RFP');
const fecharPopup = document.querySelectorAll('.fechar-popup');
const fundoEscuroPopup = document.querySelector('#fundo-escuro-popup');
const idR = document.querySelector('#idR');

for (let index = 0; index < removeRFP.length; index++) {
    removeRFP[index].onclick = () =>
    {
        //console.log(removeRFP[index].getAttribute('value'));
        
        idR.value = removeRFP[index].getAttribute('value');
        fundoEscuroPopup.style.display = 'block';
    }
}

for (let index = 0; index < fecharPopup.length; index++) {
    fecharPopup[index].onclick = () =>
    {
        fundoEscuroPopup.style.display = 'none';
    }
}