<?php

class GeneradorDeGraficos
{
    private $ancho;
    private $alto;

    public function __construct($ancho = 600, $alto = 400)
    {
        $this->ancho = $ancho;
        $this->alto = $alto;
    }

    public function crearGraficoDeLineas(array $datos, string $titulo, string $rutaSalida)
    {
        require_once(__DIR__ . '/../vendor/jpgraph/src/jpgraph.php');
        require_once(__DIR__ . '/../vendor/jpgraph/src/jpgraph_line.php');

        $grafico = new Graph($this->ancho, $this->alto);
        $grafico->SetScale('intlin');
        $grafico->title->Set($titulo);
        $grafico->title->SetFont(FF_FONT1, FS_BOLD);

        $linea = new LinePlot($datos);
        $grafico->Add($linea);

        $grafico->Stroke($rutaSalida);
    }

    public function crearGraficoDeBarras(array $datos, string $titulo, string $rutaSalida)
    {
        require_once(__DIR__ . '/../vendor/jpgraph/src/jpgraph.php');
        require_once(__DIR__ . '/../vendor/jpgraph/src/jpgraph_bar.php');

        $grafico = new Graph($this->ancho, $this->alto);
        $grafico->SetScale('textlin');
        $grafico->title->Set($titulo);
        $grafico->title->SetFont(FF_FONT1, FS_BOLD);

        $barras = new BarPlot($datos);
        $grafico->Add($barras);

        $grafico->Stroke($rutaSalida);
    }

    public function crearGraficoDeTorta(array $datos, array $etiquetas, string $titulo, string $rutaSalida)
    {
        require_once(__DIR__ . '/../vendor/jpgraph/src/jpgraph.php');
        require_once(__DIR__ . '/../vendor/jpgraph/src/jpgraph_pie.php');

        $grafico = new PieGraph($this->ancho, $this->alto);
        $grafico->title->Set($titulo);
        $grafico->title->SetFont(FF_FONT1, FS_BOLD);

        $piedata = new PiePlot($datos);
        $piedata->SetLegends($etiquetas);
        $piedata->SetCenter(0.5);

        $grafico->Add($piedata);
        $grafico->Stroke($rutaSalida);
    }


}
