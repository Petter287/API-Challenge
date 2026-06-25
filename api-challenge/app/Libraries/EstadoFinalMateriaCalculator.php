<?php

namespace App\Libraries;

class EstadoFinalMateriaCalculator
{
    private const APROBADO = 'aprobado';
    private const EN_PROCESO = 'en_proceso';
    private const NO_INICIADO = 'no_iniciado';
    private const SIN_CALIFICAR = 'sin_calificar';

    private const ESTADOS_VALIDOS = [
        self::APROBADO,
        self::EN_PROCESO,
        self::NO_INICIADO,
        self::SIN_CALIFICAR,
    ];

    public function calcular(array $estados): string
    {
        $estadosNormalizados = array_map(
            fn ($estado) => $this->normalizarEstado($estado),
            $estados
        );

        if ($estadosNormalizados === []) {
            return self::SIN_CALIFICAR;
        }

        if (count(array_unique($estadosNormalizados)) === 1 && $estadosNormalizados[0] === self::APROBADO) {
            return self::APROBADO;
        }

        if (in_array(self::EN_PROCESO, $estadosNormalizados, true)) {
            return self::EN_PROCESO;
        }

        if (in_array(self::NO_INICIADO, $estadosNormalizados, true)) {
            return self::NO_INICIADO;
        }

        return self::SIN_CALIFICAR;
    }

    public function agruparPorMateria(array $filas): array
    {
        $materias = [];

        foreach ($filas as $fila) {
            $idMateria = (int) $fila['idMateria'];
            $estadoEspacio = $this->normalizarEstado($fila['estadoEspCurr'] ?? null);

            if (!isset($materias[$idMateria])) {
                $materias[$idMateria] = [
                    'materia' => $this->formatearNombreMateria(
                        (string) $fila['nombreMateria'],
                        (int) $fila['anioMateria']
                    ),
                    'estados' => [],
                    'espacios_curriculares' => [],
                ];
            }

            $materias[$idMateria]['estados'][] = $estadoEspacio;
            $materias[$idMateria]['espacios_curriculares'][] = [
                'nombre' => (string) $fila['nombreEspCurr'],
                'estado' => $estadoEspacio,
            ];
        }

        return array_values(array_map(function (array $materia): array {
            return [
                'materia' => $materia['materia'],
                'estado' => $this->calcular($materia['estados']),
                'espacios_curriculares' => $materia['espacios_curriculares'],
            ];
        }, $materias));
    }

    private function normalizarEstado(mixed $estado): string
    {
        $estado = (string) $estado;

        return in_array($estado, self::ESTADOS_VALIDOS, true)
            ? $estado
            : self::SIN_CALIFICAR;
    }

    private function formatearNombreMateria(string $nombre, int $anio): string
    {
        return $anio > 0 ? sprintf('%s %d° Año', $nombre, $anio) : $nombre;
    }
}
