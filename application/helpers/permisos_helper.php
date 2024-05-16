<?php
// Helper para definir permisos del usuario
function verificarPermisos($gruposPermitidos) {
    $CI = &get_instance();

    if (!$CI->ion_auth->logged_in()) {
        return false; // El usuario no está autenticado
    }

    foreach ($gruposPermitidos as $grupo) {
        if ($CI->ion_auth->in_group($grupo)) {
            return true; // El usuario tiene al menos uno de los grupos permitidos
        }
    }

    return false; // El usuario no tiene ninguno de los grupos permitidos
}