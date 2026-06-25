<?php

use App\Libraries\EstadoFinalMateriaCalculator;
use CodeIgniter\Test\CIUnitTestCase;

final class EstadoFinalMateriaCalculatorTest extends CIUnitTestCase
{
    private EstadoFinalMateriaCalculator $calculator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->calculator = new EstadoFinalMateriaCalculator();
    }

    public function testMateriaQuedaAprobadaCuandoTodosLosEspaciosEstanAprobados(): void
    {
        $this->assertSame(
            'aprobado',
            $this->calculator->calcular(['aprobado', 'aprobado'])
        );
    }

    public function testMateriaQuedaEnProcesoCuandoAlMenosUnEspacioEstaEnProceso(): void
    {
        $this->assertSame(
            'en_proceso',
            $this->calculator->calcular(['aprobado', 'en_proceso'])
        );
    }

    public function testMateriaSeRecalculaCuandoCambiaElEstadoPendiente(): void
    {
        $estadoInicial = $this->calculator->calcular(['aprobado', 'en_proceso']);
        $estadoActualizado = $this->calculator->calcular(['aprobado', 'aprobado']);

        $this->assertSame('en_proceso', $estadoInicial);
        $this->assertSame('aprobado', $estadoActualizado);
    }

    public function testUnEspacioCompartidoParticipaEnElCalculoDeDosMaterias(): void
    {
        $resultado = $this->calculator->agruparPorMateria([
            [
                'idMateria' => 1,
                'nombreMateria' => 'Historia',
                'anioMateria' => 1,
                'nombreEspCurr' => 'Laboratorio de Ciencias Sociales I',
                'estadoEspCurr' => 'aprobado',
            ],
            [
                'idMateria' => 1,
                'nombreMateria' => 'Historia',
                'anioMateria' => 1,
                'nombreEspCurr' => 'Laboratorio de Ciencias Sociales II',
                'estadoEspCurr' => 'aprobado',
            ],
            [
                'idMateria' => 2,
                'nombreMateria' => 'Geografía',
                'anioMateria' => 1,
                'nombreEspCurr' => 'Laboratorio de Ciencias Sociales I',
                'estadoEspCurr' => 'aprobado',
            ],
            [
                'idMateria' => 2,
                'nombreMateria' => 'Geografía',
                'anioMateria' => 1,
                'nombreEspCurr' => 'Laboratorio de Ciencias Sociales II',
                'estadoEspCurr' => 'en_proceso',
            ],
        ]);

        $this->assertCount(2, $resultado);
        $this->assertSame('aprobado', $resultado[0]['estado']);
        $this->assertSame('en_proceso', $resultado[1]['estado']);
        $this->assertSame(
            'Laboratorio de Ciencias Sociales I',
            $resultado[0]['espacios_curriculares'][0]['nombre']
        );
        $this->assertSame(
            'Laboratorio de Ciencias Sociales I',
            $resultado[1]['espacios_curriculares'][0]['nombre']
        );
    }

    public function testEspacioSinEstadoRegistradoQuedaSinCalificar(): void
    {
        $resultado = $this->calculator->agruparPorMateria([
            [
                'idMateria' => 1,
                'nombreMateria' => 'Historia',
                'anioMateria' => 1,
                'nombreEspCurr' => 'Laboratorio de Ciencias Sociales I',
                'estadoEspCurr' => 'aprobado',
            ],
            [
                'idMateria' => 1,
                'nombreMateria' => 'Historia',
                'anioMateria' => 1,
                'nombreEspCurr' => 'Laboratorio de Ciencias Sociales II',
                'estadoEspCurr' => null,
            ],
        ]);

        $this->assertSame('sin_calificar', $resultado[0]['estado']);
        $this->assertSame(
            'sin_calificar',
            $resultado[0]['espacios_curriculares'][1]['estado']
        );
    }
}
