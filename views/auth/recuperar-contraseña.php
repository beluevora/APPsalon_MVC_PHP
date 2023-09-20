<h1 class="nombre-pagina">Recuperá tu contraseña:</h1>
<p class="descripcion-pagina">Escribí tu nueva contraseña a continuación:</p>

<?php include_once __DIR__ . "/../templates/alertas.php" ?>

<?php if($error) return;?>

<form class="formulario" method="POST">
        <div class="campo">
            <label for="password">
                 Contraseña:
            </label>
            <input type="password" id="password" name="password" placeholder="Tu nueva contraseña">
        </div>
        <input type="submit" class="boton" value="Guardar nueva contraseña">
</form>

<div class="acciones">
    <a href="/crear-cuenta">Si aún no tienes una cuenta, creá una acá</a>
    <a href="/">¿Ya tenés una cuenta? Inicia sesión </a>
</div>