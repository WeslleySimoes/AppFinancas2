<!-- <?php 

// namespace app\helpers;

// class Alert
// {
//     private static $tipos = ['info','success','warning','error'];
    
//     public static function display($tipo,$msg)
//     {
//         if(!in_array($tipo,self::$tipos))
//         {
//             throw new \Exception('Tipo definido não encontrado!');
//         }

//         return; 
//     }
// }

?> -->


<style>
    .alert{
        border: 1px solid black;
        padding: 10px;
        border-radius: 5px;
        font-weight: bold;
        font-size: 17px;
        margin: 20px 0;
        display: flex;
        justify-content: space-between;
    }

    .alert button{
         border: none;
        padding: 0 5px;
        background-color: Transparent;
        cursor: pointer;
        font-weight: bold;
    }

    .info-alert
    {  
        background-color:#CCE5FF;
        border-color: #B8DAFF;
        color: #004085; 
    }

    .success-alert
    {  
        background-color:#D4EDDA;
        border-color: #C3E5CB;
        color: #155724; 
    }

    .warning-alert
    {  
        background-color:#ffeeba;
        border-color: #FFF3CD;
        color:#856404; 
    }

    .error-alert
    {  
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }
</style>

<div class="alert info-alert"><div>Informação</div><Button class="btn-alert-close">X</Button></div>
<div class="alert success-alert"><div>Sucesso!</div><Button class="btn-alert-close">X</Button></div>
<div class="alert warning-alert"><div>Atenção!</div><Button class="btn-alert-close">X</Button></div>
<div class="alert error-alert"><div>Erro!</div><Button class="btn-alert-close">X</Button></div>

<script>
    const btnAlertClose = document.querySelectorAll('.btn-alert-close');

    for (let index = 0; index < btnAlertClose.length; index++) {

        btnAlertClose[index].onclick = () => {
            var parent = btnAlertClose[index].parentNode;
    
            parent.style.display = 'none';
        }        
    }

</script>