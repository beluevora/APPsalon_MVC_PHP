<h1 class="nombre-pagina">Login</h1>
<p class="descripcion-pagina">Inicia sesión con tus datos</p>

<?php include_once __DIR__ . "/../templates/alertas.php" ?>


<form class="formulario" method="POST" action="/">
    <div class="campo">
        <label for="email">Email</label>
        <input type="email" id="email" placeholder="Tu email" name="email" value="<?php echo s($auth->email); ?>">
    </div>

    <div class="campo">
        <label for="password">Contraseña</label>
        <input type="password" name="password" id="password" placeholder="Tu contraseña">
    </div>

    <input type="submit" class="boton" value="Iniciar sesión">
</form>

<div class="acciones">
    <a href="/crear-cuenta">Si aún no tienes una cuenta, creá una acá</a>
    <a href="/forgot">Olvidé mi contraseña</a>
</div>