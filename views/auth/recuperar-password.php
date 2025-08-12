<h1 class="nombre-pagina">Recuperar Password</h1>
<?php include_once __DIR__ . '/../../views/templates/alertas.php'; ?>

<p class="descripcion-pagina">Coloca tu nuevo password a continuación</p>

<?php if($error) return; ?>
<form class="formulario"method="POST">

<div class="campo">
    <label for="password">Password</label>
    <input type="password" id="password" name="password" placeholder="Tu Nuevo Password">
    
</div>

<input type="submit" class="boton" value="Guardar Nuevo Password">

</form>

<div class="acciones">
    <a href="/">¿Ya tienes cuenta? Inicar Sesión</a>
    <a href="/">¿Aún no tienes cuenta? Obtener una</a>
</div>