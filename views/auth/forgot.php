<h1 class="nombre-pagina">Olvidé mi contraseña</h1>
<p class="descripcion-pagina">Reestablece tu contraseña escribiendo tu email a continuación:</p>

<?php include_once __DIR__ . "/../templates/alertas.php" ?>

<form action="/forgot" class="formulario" method="POST">
    <div class="campo">
        <label for="email">Tu email</label>
        <input type="email" name="email" id="email" placeholder="Tu dirección de correo electrónico">
    </div>

    <input type="submit" class="boton" value="Enviar instrucciones">
</form>


<div class="acciones">
    <a href="/crear-cuenta">Si aún no tienes una cuenta, creá una acá</a>
    <a href="/">¿Ya tenés una cuenta? Inicia sesión </a>
</div>