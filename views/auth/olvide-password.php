<h1 class="nombre-pagina">Olvide Password</h1>
<p class="descripcion-pagina">Reestablece tu password escribiendo tu email a continuación</p>

<?php include_once __DIR__ . '/../../views/templates/alertas.php'; ?>

<?php if(empty($alertas['exito'])): ?>
    <form action="/olvide" method="POST" class="formulario">
        <div class="campo">
            <label for="email">Email</label>
            <input type="text" id="email" name="email" placeholder="Tu email">
        </div>

        <input type="submit" class="boton" value="Enviar Instrucciones">
    </form>
<?php endif; ?>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inicia Sesión</a>
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crear una</a>
</div>