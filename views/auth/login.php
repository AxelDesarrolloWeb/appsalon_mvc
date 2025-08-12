<h1 class="nombre-pagina">Login</h1>
<?php include_once __DIR__ . '/../../views/templates/alertas.php'; ?>
<p class="descripcion-pagina">Inicia sesión con tus datos</p>

<form action="/" class="fornulario" method="POST">
    <div class="campo">
        <label for="email">Email</label>
        <!-- value="  echo s($auth->email); ?>" --> <!-- esto sirve para guardar el campo -->
        <input type="email" id="email" name="email" placeholder="Tu email">
    </div>

    <div class="campo">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Tu password">
    </div>

    <input type="submit" class="boton" value="Iniciar Sesión">
</form>

<div class="acciones">
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crear una</a>
    <a href="/olvide">¿Olvidaste tu password?</a>
</div>