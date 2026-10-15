<?php

namespace App;

class ReglasPlan
{
    public static function puedeCancelar($userId, $estado, $usuarioId)
    {
        if ((int) $userId !== (int) $usuarioId) {
            return false;
        }

        if ($estado === 'cancelado') {
            return false;
        }

        return $estado === 'borrador' || $estado === 'confirmado';
    }

    public static function puedeConfirmar($userId, $estado, $usuarioId)
    {
        if ((int) $userId !== (int) $usuarioId) {
            return false;
        }

        return $estado === 'borrador';
    }

    public static function puedeEditar($userId, $estado, $usuarioId)
    {
        if ((int) $userId !== (int) $usuarioId) {
            return false;
        }

        return $estado === 'borrador' || $estado === 'confirmado';
    }
}
