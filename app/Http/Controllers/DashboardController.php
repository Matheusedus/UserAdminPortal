<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(): JsonResponse
    {
        $totalUsuarios = User::count();

        $usuariosPorCidade = User::select('cidade', DB::raw('count(*) as total'))
            ->groupBy('cidade')
            ->orderByDesc('total')
            ->get();

        $usuariosPorPerfil = User::select('role', DB::raw('count(*) as total'))
            ->groupBy('role')
            ->get();

        return response()->json([
            'total_usuarios' => $totalUsuarios,
            'usuarios_por_cidade' => $usuariosPorCidade,
            'usuarios_por_perfil' => $usuariosPorPerfil,
        ]);
    }
}
