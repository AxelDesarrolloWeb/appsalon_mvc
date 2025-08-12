<h1 class="nombre-pagina">Crear cuenta</h1>
<?php include_once __DIR__ . '/../../views/templates/alertas.php'; ?>
<p class="descripcion-pagina">Llena el formulario para crear una cuenta</p>

<form action="/crear-cuenta" method="POST" class="formulario">
    <div class="campo">
        <label for="nombre">Nombre</label>
        <input value="<?php echo s($usuario->nombre) ?>" type="text" id="nombre" name="nombre" placeholder="Tu Nombre">

    </div>
    <div class="campo">
        <label for="apellido">Apellido</label>
        <input value="<?php echo s($usuario->apellido) ?>" type="text" id="apellido" name="apellido" placeholder="Tu Apellido">

    </div>
    <div class="campo">
        <label for="telefono">Telefono</label>
        <input value="<?php echo s($usuario->telefono) ?>" type="tel" id="telefono" name="telefono" placeholder="Tu Telefono">
    </div>

    <div class="campo">
        <label for="email">Email</label>
        <input value="<?php echo s($usuario->email) ?>" type="email" id="email" name="email" placeholder="Tu Email">
    </div>

    <div class="campo">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Tu Password">
    </div>

    <input type="submit" class="boton" value="Crear Cuenta">
    </form>

    <div class="acciones">
        <a href="/">¿Ya tienes una cuenta? Inicia Sesión</a>
        <a href="/olvide">¿Olvidaste tu password?</a>
    </div>