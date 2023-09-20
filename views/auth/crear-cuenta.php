<h1 class="nombre-pagina">Creá tu cuenta!</h1>
<p class="descripcion-pagina">LLená el siguiente formulario</p>

<?php include_once __DIR__ . "/../templates/alertas.php" ?>


<form class="formulario" method="POST" action="/crear-cuenta">
    <div class="campo">
        <label for="nombre">Nombre</label>
        <input type="text" name="nombre" id="nombre" placeholder="Tu nombre" value="<?php echo s($usuario->nombre)?>">
    </div>

    <div class="campo">
        <label for="apellido">Apellido</label>
        <input type="text" name="apellido" id="apellido" placeholder="Tu apellido" value="<?php echo s($usuario->apellido)?>">
    </div>

    <div class="campo">
        <label for="telefono">Teléfono</label>
        <input type="tel" name="telefono" id="telefono" placeholder="Tu teléfono"value="<?php echo s($usuario->telefono)?>">
    </div>

    <div class="campo">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" placeholder="Tu dirección de correo electrónico "value="<?php echo s($usuario->email)?>">
    </div>

    <div class="campo">
        <label for="password">Contraseña</label>
        <input type="password" name="password" id="password" placeholder="Escribe una contraseña" >
    </div>

    <input type="submit" value="Creá tu cuenta" class="boton">
</form>

<div class="acciones">
    <a href="/">¿Ya tenés una cuenta? Inicia sesión </a>
    <a href="/forgot">Olvidé mi contraseña</a>
</div>