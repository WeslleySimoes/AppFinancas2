<style>
    .container-form{
        background-color: white;
        padding: 20px;
        border: 1px solid #cecdcd;
    }
    
    .container-form label{
        color: #464646;
    }

    .title-form{
        margin-bottom: 20px;
    }

    .btn-success
    {
        background-color: #121E2D;
        font-weight: normal;
        font-size: 0.9rem;
    }

    .btn-success:hover{
        background-color: #464646;
    }

</style>


<div class="container-form">
    <h3 class="title-form">Cadastro de conta</h3>
    <hr style="margin-bottom: 20px; border: 0.5px solid #cecdcd;">
    <form action="">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome">
        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email">
        <select name="cars" id="cars">
            <option value="volvo">Volvo</option>
            <option value="saab">Saab</option>
            <option value="mercedes">Mercedes</option>
            <option value="audi">Audi</option>
        </select>        
        <button class="btn-success">Enviar</button>
    </form>
</div>